<?php

namespace OroCRM\Bundle\PartnerBundle\Form\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

use Doctrine\ORM\EntityRepository;

class PartnerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'account',
                'orocrm_account_select',
                [
                    'required' => true,
                    'label' => 'orocrm.partner.account.label',
                    'autocomplete_alias' => 'partners_accounts',
                    'grid_name' => 'partner-accounts-select-grid',
                ]
            )
            ->add(
                'contract',
                'oro_file',
                [
                    'required' => false,
                    'label' => 'orocrm.partner.contract.label',
                ]
            )
            ->add(
                'partnerCondition',
                'textarea',
                [
                    'required' => false,
                    'label' => 'orocrm.partner.partner_condition.label',
                ]
            )
            ->add(
                'status',
                'entity',
                [
                    'label'         => 'orocrm.partner.status.label',
                    'class'         => 'OroCRMPartnerBundle:PartnerStatus',
                    'query_builder' => function (EntityRepository $entityRepository) {
                        return $entityRepository->createQueryBuilder('partnerStatus')
                            ->orderBy('partnerStatus.order', 'ASC');
                    },
                    'property'      => 'label'
                ]
            )->add(
                'gitHubAccounts',
                'orocrm_partner_github_account_collection',
                array(
                    'label'    => 'orocrm.partner.form.github_account',
                    'type'     => 'orocrm_partner_github_account',
                    'required' => false,
                    'options'  => array('data_class' => 'OroCRM\Bundle\PartnerBundle\Entity\GitHubAccount')
                )
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => 'OroCRM\Bundle\PartnerBundle\Entity\Partner',
                'intention' => 'partner',
                'extra_fields_message' => 'This form should not contain extra fields: "{{ extra_fields }}"',
                'cascade_validation' => true
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'orocrm_partner';
    }
}
