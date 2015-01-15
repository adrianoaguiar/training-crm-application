<?php

namespace Webinar\Bundle\DemoBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TicketType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('seatNum', null, ['label' => 'webinar.ticket.form.seat_num.label'])
            ->add('description', null, ['label' => 'webinar.ticket.form.description.label'])
            ->add('eventName', null, ['label' => 'webinar.ticket.form.event_name.label'])
            ->add('eventDate', null, ['label' => 'webinar.ticket.form.event_date.label'])
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Webinar\Bundle\DemoBundle\Entity\Ticket'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'webinar_demobundle_ticket';
    }
}
