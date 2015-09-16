<?php

namespace OroTraining\Bundle\AccountBundle\Controller;

use Doctrine\ORM\EntityRepository;
use OroCRM\Bundle\PartnerBundle\Entity\Partner;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\Acl;
use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

use OroCRM\Bundle\AccountBundle\Entity\Account;
use OroCRM\Bundle\AccountBundle\Controller\AccountController as OriginalController;

class AccountController extends OriginalController
{
    /**
     * @Route("/view/{id}", name="orocrm_account_view", requirements={"id"="\d+"})
     * @Acl(
     *      id="orocrm_account_view",
     *      type="entity",
     *      permission="VIEW",
     *      class="OroCRMAccountBundle:Account"
     * )
     * @Template()
     */
    public function viewAction(Account $account)
    {
        $result = parent::viewAction($account);
        $result['partners'] = $this->getPartners($account);

        return $result;
    }

    /**
     * @Route(
     *      "/widget/partners/{id}",
     *      name="orotraining_account_widget_partners",
     *      requirements={"id"="\d+"},
     *      defaults={"id"=0}
     * )
     * @AclAncestor("orocrm_partner_view")
     * @Template()
     */
    public function gitHubAccountsAction(Account $account)
    {
        return [
            'gitHubAccounts' => $this->getGitHubAccounts($account)
        ];
    }

    /**
     * @param Account|null $account
     * @return Partner[]
     */
    protected function getPartners(Account $account)
    {
        return [
            'partners' => $this->get('doctrine')->getManager()
                ->getRepository('OroCRMPartnerBundle:Partner')
                ->findOneBy(['account' => $account])
        ];
    }

    /**
     * @param Account|null $account
     * @return Partner[]
     * @Template("OroCRMPartnerBundle:GitHubAccount:partnerGitHubAccounts.html.twig")
     */
    protected function getGitHubAccounts(Account $account)
    {
        /** @var EntityRepository $repository */
        $repository = $this->get('doctrine')->getManager()
                ->getRepository('OroCRMPartnerBundle:GitHubAccount');

        return $repository->createQueryBuilder('gitHubAccount')
                ->select('gitHubAccount')
                ->leftJoin('gitHubAccount.partner', 'partner', 'WITH', 'partner.account = :account')
                ->setParameter('account', $account)
                ->getQuery()
                ->execute();
    }
}
