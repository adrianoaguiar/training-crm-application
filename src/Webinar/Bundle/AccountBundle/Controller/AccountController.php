<?php

namespace Webinar\Bundle\AccountBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\SecurityBundle\Annotation\AclAncestor;

use OroCRM\Bundle\AccountBundle\Entity\Account;
use OroCRM\Bundle\AccountBundle\Controller\AccountController as OriginalController;

class AccountController extends OriginalController
{
    /**
     * @Route("/widget/info/{id}", name="orocrm_account_widget_info", requirements={"id"="\d+"})
     * @AclAncestor("orocrm_account_view")
     * @Template()
     */
    public function infoAction(Account $account)
    {
        $b2bcustomer = $this->get('doctrine')->getManager()
            ->getRepository('OroCRMSalesBundle:B2bCustomer')
            ->findOneBy(['account' => $account]);

        return [
            'account'     => $account,
            'b2bcustomer' => $b2bcustomer,
        ];
    }
}
