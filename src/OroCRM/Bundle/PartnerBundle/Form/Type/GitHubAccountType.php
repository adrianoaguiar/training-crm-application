<?php

namespace OroCRM\Bundle\PartnerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GitHubAccountType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'username',
                'text',
                array(
                    'required' => true,
                    'label' => 'orocrm.partner.githubaccount.username.label'
                )
            )->add(
                'email',
                'email',
                array(
                    'required' => false,
                    'label' => 'orocrm.partner.githubaccount.email.label'
                )
            )->add(
                'name',
                'text',
                array(
                    'required' => false,
                    'label' => 'orocrm.partner.githubaccount.name.label'
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'OroCRM\Bundle\PartnerBundle\Entity\GitHubAccount'
            )
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'orocrm_partner_github_account';
    }
}
