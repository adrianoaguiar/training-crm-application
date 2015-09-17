<?php

namespace OroCRM\Bundle\PartnerBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GitHubIssueType extends AbstractType
{
    const INTEGRATION_TYPE = 'github';

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'title',
                'text',
                [
                    'required' => true,
                    'label'    => 'orocrm.partner.githubissue.title.label'
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => true,
                    'label'    => 'orocrm.partner.githubissue.description.label'
                ]
            )->add(
                'assignedTo',
                'entity',
                [
                    'required' => true,
                    'label'    => 'orocrm.partner.form.github_account.label',
                    'class'    => 'OroCRMPartnerBundle:GitHubAccount'
                ]
            )->add(
                'channel',
                'oro_integration_select'
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue',
            )
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'orocrm_partner_github_issue';
    }
}
