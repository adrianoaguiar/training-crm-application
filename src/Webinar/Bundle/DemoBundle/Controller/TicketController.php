<?php

namespace Webinar\Bundle\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

use Webinar\Bundle\DemoBundle\Entity\Ticket;

/**
 * Ticket controller.
 *
 * @Route("/ticket")
 */
class TicketController extends Controller
{
    /**
     * @Route("/info/{id}", name="webinar_ticket_info", requirements={"id"="\d+"})
     * @AclAncestor("webinar_demo_ticket_view")
     * @Template()
     */
    public function infoAction(Ticket $ticket)
    {
        return array(
            'entity'  => $ticket
        );
    }

    /**
     * @Route(
     *      "/{_format}",
     *      name="webinar_demo_ticket_index",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format" = "html"}
     * )
     * @Template
     * @AclAncestor("webinar_demo_ticket_view")
     */
    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('webinar_demo.ticket.class')
        ];
    }

    /**
     * @Route("/view/{id}", name="webinar_demo_ticket_view", requirements={"id"="\d+"})
     * @Template
     * @Acl(
     *      id="webinar_demo_ticket_view",
     *      type="entity",
     *      permission="VIEW",
     *      class="WebinarDemoBundle:Ticket"
     * )
     */
    public function viewAction(Ticket $entity)
    {
        return array(
            'entity' => $entity,
        );
    }

    /**
     * @Route("/create", name="webinar_demo_ticket_create")
     * @Template("WebinarDemoBundle:Ticket:update.html.twig")
     * @Acl(
     *      id="webinar_demo_ticket_create",
     *      type="entity",
     *      permission="CREATE",
     *      class="WebinarDemoBundle:Ticket"
     * )
     */
    public function createAction()
    {
        return $this->update(new Ticket());
    }

    /**
     * @Route("/update/{id}", name="webinar_demo_ticket_update", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template
     * @Acl(
     *      id="webinar_demo_ticket_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="WebinarDemoBundle:Ticket"
     * )
     */
    public function updateAction(Ticket $entity)
    {
        return $this->update($entity);
    }

    /**
     * @param  Ticket $entity
     *
     * @return array
     */
    protected function update(Ticket $entity)
    {
        return $this->get('oro_form.model.update_handler')->handleUpdate(
            $entity,
            $this->get('webinar_demo.ticket.form'),
            function (Ticket $entity) {
                return array(
                    'route' => 'webinar_demo_ticket_update',
                    'parameters' => array('id' => $entity->getId())
                );
            },
            function (Ticket $entity) {
                return array(
                    'route' => 'webinar_demo_ticket_view',
                    'parameters' => array('id' => $entity->getId())
                );
            },
            $this->get('translator')->trans('webinar.ticket.saved.message'),
            $this->get('webinar_demo.ticket.form.handler')
        );
    }
}
