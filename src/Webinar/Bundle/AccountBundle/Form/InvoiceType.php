<?php

namespace Webinar\Bundle\AccountBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class InvoiceType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('note', null, ['label' => 'webinar.invoice.form.note.label'])
            ->add('datePaid', null, ['label' => 'webinar.invoice.form.datePaid.label'])
            ->add('total', null, ['label' => 'webinar.invoice.form.total.label'])
            ->add('discount', null, ['label' => 'webinar.invoice.form.discount.label'])
            ->add('tax', null, ['label' => 'webinar.invoice.form.tax.label'])
        ;
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webinar\Bundle\AccountBundle\Entity\Invoice'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'webinar_accountbundle_invoice';
    }
}
