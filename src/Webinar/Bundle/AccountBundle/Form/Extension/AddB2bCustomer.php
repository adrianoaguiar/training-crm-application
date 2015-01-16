<?php

namespace Webinar\Bundle\AccountBundle\Form\Extension;

use Doctrine\Common\Persistence\ManagerRegistry;

use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use OroCRM\Bundle\AccountBundle\Entity\Account;
use OroCRM\Bundle\SalesBundle\Entity\B2bCustomer;

/**
 * Adds B2bCustomer fields to root form of Account. Persists B2bCustomer.
 */
class AddB2bCustomer extends AbstractTypeExtension
{
    /**
     * @var ManagerRegistry
     */
    protected $managerRegistry;

    /**
     * @param ManagerRegistry $managerRegistry
     */
    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(
            [
                'validation_groups' => ['invoiced_account'],
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(
            FormEvents::POST_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();

                if ($form->getParent()) {
                    // modify only root form
                    return;
                }

                $form->add(
                    'b2bcustomer',
                    'orocrm_sales_b2bcustomer',
                    [
                        'mapped' => false,
                        'required' => true,
                    ]
                );

                $form->get('b2bcustomer')
                    ->remove('owner')
                    ->remove('account')
                    ->remove('contact');

                $account = $form->getData();
                if ($account && !$form->get('b2bcustomer')->getData()) {
                    $b2bCustomer = $this->managerRegistry->getRepository('OroCRMSalesBundle:B2bCustomer')
                        ->findOneBy(['account' => $account]);

                    if (!$b2bCustomer) {
                        $b2bCustomer = new B2bCustomer();
                    }
                    $form->get('b2bcustomer')->setData($b2bCustomer);
                }
            }
        );

        $builder->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();

                if ($form->getParent()) {
                    // modify only root form
                    return;
                }

                /** @var Account $account */
                $account = $event->getData();
                /** @var B2bCustomer $b2bCustomer */
                $b2bCustomer = $event->getForm()->get('b2bcustomer')->getData();

                if ($account && $b2bCustomer) {
                    $b2bCustomer->setAccount($account);
                    $b2bCustomer->setOwner($account->getOwner());
                    $b2bCustomer->setName($account->getName());

                    $contact = $account->getDefaultContact();
                    if ($contact) {
                        $b2bCustomer->setContact($contact);
                    }

                    $this->managerRegistry->getManager()->persist($b2bCustomer);
                }
            },
            10 // should be higher than any other listener that check isValid for this form
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return 'orocrm_account';
    }
}