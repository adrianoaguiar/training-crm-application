<?php

namespace OroCRM\Bundle\PartnerBundle\Controller\Api\Rest;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

use FOS\RestBundle\Controller\Annotations\NamePrefix;
use FOS\RestBundle\Controller\Annotations\RouteResource;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Routing\ClassResourceInterface;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;
use Oro\Bundle\SoapBundle\Controller\Api\Rest\RestController;
use OroCRM\Bundle\PartnerBundle\Entity\Partner;
use OroCRM\Bundle\PartnerBundle\Entity\PartnerStatus;

/**
 * @RouteResource("partner")
 * @NamePrefix("orocrm_partner_api_")
 */
class PartnerController extends RestController implements ClassResourceInterface
{
    /**
     * REST GET LIST PARTNER
     *
     * @QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Page number, starting from 1. Defaults to 1."
     * )
     * @QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      nullable=true,
     *      description="Number of items per page. defaults to 10."
     * )
     * @ApiDoc(
     *      description="Get all partners",
     *      resource=true
     * )
     * @AclAncestor("orocrm_partner_view")
     *
     * @return Response
     */
    public function cgetAction(Request $request)
    {
        $page = (int)$request->get('page', 1);
        $limit = (int)$request->get('limit', self::ITEMS_PER_PAGE);

        return $this->handleGetListRequest($page, $limit);
    }

    /**
     * REST GET PARTNER
     *
     * @param integer $id
     *
     * @ApiDoc(
     *      description="Get partner",
     *      resource=true
     * )
     * @AclAncestor("orocrm_partner_view")
     *
     * @return Response
     */
    public function getAction($id)
    {
        return $this->handleGetRequest($id);
    }

    /**
     * REST PUT PARTNER
     *
     * @param integer $id
     *
     * @ApiDoc(
     *      description="Update partner",
     *      resource=true
     * )
     * @AclAncestor("orocrm_partner_update")
     *
     * @return Response
     */
    public function putAction($id)
    {
        return $this->handleUpdateRequest($id);
    }

    /**
     * REST POST PARTNER
     *
     * @ApiDoc(
     *      description="Create new partner",
     *      resource=true
     * )
     * @AclAncestor("orocrm_partner_create")
     */
    public function postAction()
    {
        return $this->handleCreateRequest();
    }

    /**
     * REST DELETE PARTNER
     *
     * @param int $id
     *
     * @ApiDoc(
     *      description="Delete Partner",
     *      resource=true
     * )
     * @Acl(
     *      id="orocrm_partner_delete",
     *      type="entity",
     *      permission="DELETE",
     *      class="OroCRMPartnerBundle:Partner"
     * )
     *
     * @return Response
     */
    public function deleteAction($id)
    {
        return $this->handleDeleteRequest($id);
    }

    /**
     * {@inheritdoc}
     */
    public function getManager()
    {
        return $this->get('orocrm_partner.partner.manager.api');
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->get('orocrm_partner.form.type.partner.api');
    }

    /**
     * {@inheritdoc}
     */
    public function getFormHandler()
    {
        return $this->get('orocrm_partner.form.handler.partner.api');
    }

    /**
     * {@inheritdoc}
     */
    protected function getPreparedItem($entity, $resultFields = [])
    {
        $result = parent::getPreparedItem($entity, $resultFields);
        unset($result['contract']);
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    protected function transformEntityField($field, &$value)
    {
        switch ($field) {
            case 'status':
                if ($value) {
                    /** @var PartnerStatus $value */
                    $value = $value->getName();
                }
                break;
            case 'owner':
            case 'account':
                if ($value) {
                    $value = $value->getId();
                }
                break;
            default:
                parent::transformEntityField($field, $value);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function fixFormData(array &$data, $entity)
    {
        /** @var Partner $entity */
        parent::fixFormData($data, $entity);

        unset($data['id']);
        unset($data['createdAt']);
        unset($data['updatedAt']);

        return true;
    }
}
