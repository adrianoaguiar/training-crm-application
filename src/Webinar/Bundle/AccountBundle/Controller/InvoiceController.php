<?php

namespace Webinar\Bundle\AccountBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

use Webinar\Bundle\AccountBundle\Entity\Invoice;

/**
 * Invoice controller.
 *
 * @Route("/invoice")
 */
class InvoiceController extends Controller
{
    /**
     * @Route("/info/{id}", name="webinar_invoice_info", requirements={"id"="\d+"})
     * @AclAncestor("webinar_account_invoice_view")
     * @Template()
     */
    public function infoAction(Invoice $invoice)
    {
        return array(
            'entity'  => $invoice
        );
    }

    /**
     * @Route(
     *      "/{_format}",
     *      name="webinar_account_invoice_index",
     *      requirements={"_format"="html|json"},
     *      defaults={"_format" = "html"}
     * )
     * @Template
     * @AclAncestor("webinar_account_invoice_view")
     */
    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('webinar_account.invoice.class')
        ];
    }

    /**
     * @Route("/view/{id}", name="webinar_account_invoice_view", requirements={"id"="\d+"})
     * @Template
     * @Acl(
     *      id="webinar_account_invoice_view",
     *      type="entity",
     *      permission="VIEW",
     *      class="WebinarAccountBundle:Invoice"
     * )
     */
    public function viewAction(Invoice $invoice)
    {
        return array(
            'entity' => $invoice,
        );
    }

    /**
     * @Route("/create", name="webinar_account_invoice_create")
     * @Template("WebinarAccountBundle:Invoice:update.html.twig")
     * @Acl(
     *      id="webinar_account_invoice_create",
     *      type="entity",
     *      permission="CREATE",
     *      class="WebinarAccountBundle:Invoice"
     * )
     */
    public function createAction()
    {
        return $this->update(new Invoice());
    }

    /**
     * @Route("/update/{id}", name="webinar_account_invoice_update", requirements={"id"="\d+"}, defaults={"id"=0})
     * @Template
     * @Acl(
     *      id="webinar_account_invoice_update",
     *      type="entity",
     *      permission="EDIT",
     *      class="WebinarAccountBundle:Invoice"
     * )
     */
    public function updateAction(Invoice $entity)
    {
        return $this->update($entity);
    }

    /**
     * @param  Invoice $entity
     *
     * @return array
     */
    protected function update(Invoice $entity)
    {
        return $this->get('oro_form.model.update_handler')->handleUpdate(
            $entity,
            $this->get('webinar_account.invoice.form'),
            function (Invoice $entity) {
                return array(
                    'route' => 'webinar_account_invoice_update',
                    'parameters' => array('id' => $entity->getId())
                );
            },
            function (Invoice $entity) {
                return array(
                    'route' => 'webinar_account_invoice_view',
                    'parameters' => array('id' => $entity->getId())
                );
            },
            $this->get('translator')->trans('webinar.invoice.saved.message'),
            $this->get('webinar_account.invoice.form.handler')
        );
    }
}
