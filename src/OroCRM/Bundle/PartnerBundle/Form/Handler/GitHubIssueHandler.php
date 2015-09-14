<?php

namespace OroCRM\Bundle\PartnerBundle\Form\Handler;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Persistence\ObjectManager;

use Oro\Bundle\ActivityBundle\Manager\ActivityManager;
use Oro\Bundle\EntityBundle\Tools\EntityRoutingHelper;
use Oro\Bundle\SoapBundle\Form\Handler\ApiFormHandler;

class GitHubIssueHandler extends ApiFormHandler
{
    /** @var ActivityManager */
    protected $activityManager;

    /** @var EntityRoutingHelper */
    protected $entityRoutingHelper;

    /**
     * @param FormInterface       $form
     * @param Request             $request
     * @param ObjectManager       $entityManager
     * @param ActivityManager     $activityManager
     * @param EntityRoutingHelper $entityRoutingHelper
     */
    public function __construct(
        FormInterface       $form,
        Request             $request,
        ObjectManager       $entityManager,
        ActivityManager     $activityManager,
        EntityRoutingHelper $entityRoutingHelper
    ) {
        parent::__construct($form, $request, $entityManager);
        $this->activityManager = $activityManager;
        $this->entityRoutingHelper = $entityRoutingHelper;
    }

    /**
     * {@inheritdoc}
     */
    protected function onSuccess($entity)
    {
        $action = $this->entityRoutingHelper->getAction($this->request);
        if ($action === 'activity') {
            $this->activityManager->addActivityTarget($entity, $this->getTargetEntity());
        }

        parent::onSuccess($entity);
    }

    /**
     * Get target entity to associate with the github issue
     *
     * @return null|object
     */
    protected function getTargetEntity()
    {
        $targetEntity = null;
        $targetEntityClass = $this->entityRoutingHelper->getEntityClassName($this->request);
        if ($targetEntityClass) {
            $targetEntityId = $this->entityRoutingHelper->getEntityId($this->request);
            $targetEntity   = $this->entityRoutingHelper->getEntityReference($targetEntityClass, $targetEntityId);
        }

        return $targetEntity;
    }
}
