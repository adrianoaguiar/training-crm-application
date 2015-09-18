<?php

namespace OroCRM\Bundle\PartnerBundle\Form\EventListener;

use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;
use OroCRM\Bundle\PartnerBundle\Entity\Partner;
use OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue;

class GitHubIssueSubscriber implements EventSubscriberInterface
{
    /** @var EntityRoutingHelper */
    protected $entityRoutingHelper;

    /**
     * @param EntityRoutingHelper $entityRoutingHelper
     */
    public function __construct(
        EntityRoutingHelper $entityRoutingHelper
    ) {
        $this->entityRoutingHelper = $entityRoutingHelper;
    }

    /** @var Request */
    protected $request;

    /**
     * @param Request|null $request
     */
    public function setRequest(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            FormEvents::PRE_SET_DATA  => 'preSet',
        ];
    }

    /**
     * @param FormEvent $event
     */
    public function preSet(FormEvent $event)
    {
        $form = $event->getForm();

        /** @var GitHubIssue $data */
        $data = $event->getData();
        if ($data === null) {
            return;
        }
        $this->addAssignedToField($form, $data);

        // If there is just one GitHub integration configured, we set it to the entity and remove the field.
        // Also we remove the integration field if it is already set for an entity.
        $integrationChoices = $form->get('channel')->getConfig()->getOption('choice_list')->getChoices();
        if ($data->getChannel()) {
            $form->remove('channel');
        } elseif (count($integrationChoices) <= 1) {
            $data->setChannel(reset($integrationChoices));
            $form->remove('channel');
        }

        // Remove 'status' field for newly created issues since you can create issue in "Open" status
        if (!$data->getId() && $form->has('status')) {
            $statuses = $form->get('status')->getConfig()->getOption('choice_list')->getAdaptedList()->getChoices();
            $data->setStatus(reset($statuses));
            $form->remove('status');
        }
    }


    /**
     * @param FormInterface $form
     * @param GitHubIssue   $data
     */
    protected function addAssignedToField($form, $data)
    {
        if ($data->getAssignedTo()) {
            $targetEntity = $data->getAssignedTo()->getPartner();
        } else {
            $targetEntity = $this->getTargetEntityFromRequest();
        }
        if ($targetEntity instanceof Partner) {
            $gitHubAccounts = $targetEntity->getGitHubAccounts();
        }
        // Add selection of GitHub accounts to assign the issue to,
        // if there are more than one available for the target entity
        if ($gitHubAccounts) {
            if (count($gitHubAccounts) > 1) {
                $form->add(
                    'assignedTo',
                    'entity',
                    [
                        'required' => true,
                        'label'    => 'orocrm.partner.form.github_account.label',
                        'class'    => 'OroCRMPartnerBundle:GitHubAccount',
                        'choices'  => $gitHubAccounts,
                    ]
                );
            } elseif (!$data->getAssignedTo() && count($gitHubAccounts) == 1) {
                $data->setAssignedTo($gitHubAccounts->first());
            }
        }
    }

    /**
     * Get the entity that will be associated to the GitHub issue
     *
     * @return null|object
     */
    protected function getTargetEntityFromRequest()
    {
        $targetEntity = null;
        $targetEntityClass = $this->entityRoutingHelper->getEntityClassName($this->request);
        if ($targetEntityClass) {
            $targetEntityId = $this->entityRoutingHelper->getEntityId($this->request);
            $targetEntity = $this->entityRoutingHelper->getEntityReference(
                $targetEntityClass,
                $targetEntityId
            );
        }

        return $targetEntity;
    }
}
