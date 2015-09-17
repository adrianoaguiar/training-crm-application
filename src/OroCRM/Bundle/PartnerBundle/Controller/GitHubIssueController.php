<?php

namespace OroCRM\Bundle\PartnerBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

use OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue;

/**
 * @Route("/github-issue")
 */
class GitHubIssueController extends Controller
{
    /**
     * @Route("/", name="orocrm_github_issue_index")
     * @AclAncestor("orocrm_partner_view")
     * @Template
     */
    public function indexAction()
    {
        return [];
    }

    /**
     * @Route("/widget/info/{id}", name="orocrm_partner_github_issue_widget_info", requirements={"id"="\d+"})
     * @Template
     * @AclAncestor("orocrm_partner_github_issue_view")
     */
    public function infoAction(GitHubIssue $entity)
    {
        return ['entity' => $entity];
    }

    /**
     * This action is used to render the list of github issues associated with the given entity
     * on the view page of this entity
     *
     * @Route("/activity/view/{entityClass}/{entityId}", name="orocrm_partner_github_issue_activity_view")
     * @AclAncestor("orocrm_partner_github_issue_view")
     * @Template
     */
    public function activityAction($entityClass, $entityId)
    {
        return [
            'entity' => $this->get('oro_entity.routing_helper')->getEntity($entityClass, $entityId)
        ];
    }

    /**
     * @Route("/create", name="orocrm_partner_github_issue_create")
     * @Template("OroCRMPartnerBundle:GitHubIssue:update.html.twig")
     * @Acl(
     *      id="orocrm_partner_github_issue_create",
     *      type="entity",
     *      class="OroCRMPartnerBundle:GitHubIssue",
     *      permission="CREATE",
     *      group_name=""
     * )
     */
    public function createAction(Request $request)
    {
        $entity = new GitHubIssue();

        $formAction = $this->get('oro_entity.routing_helper')
            ->generateUrlByRequest('orocrm_partner_github_issue_create', $request);

        return $this->update($entity, $formAction, $request);
    }

    /**
     * @Route("/update/{id}", name="orocrm_partner_github_issue_update", requirements={"id"="\d+"})
     * @Template
     * @Acl(
     *      id="orocrm_partner_github_issue_update",
     *      type="entity",
     *      class="OroCRMPartnerBundle:GitHubIssue",
     *      permission="EDIT",
     *      group_name=""
     * )
     */
    public function updateAction(GitHubIssue $entity, Request $request)
    {
        $formAction = $this->get('router')->generate('orocrm_partner_github_issue_update', ['id' => $entity->getId()]);

        return $this->update($entity, $formAction, $request);
    }

    /**
     * @param GitHubIssue $entity
     * @param string      $formAction
     * @param Request     $request
     *
     * @return array
     */
    protected function update(GitHubIssue $entity, $formAction, Request $request)
    {
        $saved = false;

        if ($this->get('orocrm_partner.form.handler.github_issue')->process($entity)) {
            if (!$request->get('_widgetContainer')) {
                $this->get('session')->getFlashBag()->add(
                    'success',
                    $this->get('translator')->trans('orocrm.partner.controller.github_issue_saved_message')
                );

                return $this->get('oro_ui.router')->redirectAfterSave(
                    ['route' => 'orocrm_partner_github_issue_update', 'parameters' => ['id' => $entity->getId()]],
                    ['route' => 'orocrm_partner_github_issue_view', 'parameters' => ['id' => $entity->getId()]]
                );
            }
            $saved = true;
        }

        return [
            'entity'     => $entity,
            'saved'      => $saved,
            'form'       => $this->get('orocrm_partner.form.handler.github_issue')->getForm()->createView(),
            'formAction' => $formAction
        ];
    }
}
