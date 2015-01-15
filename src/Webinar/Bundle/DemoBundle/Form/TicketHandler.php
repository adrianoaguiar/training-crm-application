<?php

namespace Webinar\Bundle\DemoBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

use Doctrine\Common\Persistence\ObjectManager;

use Webinar\Bundle\DemoBundle\Entity\Ticket;

class TicketHandler
{
    /** @var FormInterface */
    protected $form;

    /** @var Request */
    protected $request;

    /** @var ObjectManager */
    protected $manager;

    /**
     * @param FormInterface $form
     * @param Request       $request
     * @param ObjectManager $manager
     */
    public function __construct(FormInterface $form, Request $request, ObjectManager $manager)
    {
        $this->form = $form;
        $this->request = $request;
        $this->manager = $manager;
    }

    /**
     * @param  Ticket $entity
     *
     * @return bool
     */
    public function process(Ticket $entity)
    {
        $this->form->setData($entity);

        if (in_array($this->request->getMethod(), array('POST', 'PUT'))) {
            $this->form->submit($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($entity);

                return true;
            }
        }

        return false;
    }

    /**
     * @param Ticket $entity
     */
    protected function onSuccess(Ticket $entity)
    {
        $this->manager->persist($entity);
        $this->manager->flush();
    }
}
