<?php

namespace OroCRM\Bundle\PartnerBundle\EventListener;

use Oro\Bundle\ActivityBundle\Manager\ActivityManager;
use Oro\Bundle\ImportExportBundle\Event\StrategyEvent;
use Oro\Bundle\IntegrationBundle\ImportExport\Helper\DefaultOwnerHelper;

use OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue;
use OroCRM\Bundle\PartnerBundle\Entity\GitHubAccount;

class ImportStrategyListener
{
    /** @var ActivityManager */
    protected $activityManager;

    /** @var DefaultOwnerHelper */
    protected $defaultOwnerHelper;

    /**
     * @param ActivityManager    $activityManager
     * @param DefaultOwnerHelper $defaultOwnerHelper
     */
    public function __construct(ActivityManager $activityManager, DefaultOwnerHelper $defaultOwnerHelper)
    {
        $this->activityManager = $activityManager;
        $this->defaultOwnerHelper = $defaultOwnerHelper;
    }

    /**
     * @param StrategyEvent $event
     */
    public function onProcessAfter(StrategyEvent $event)
    {
        $entity = $event->getEntity();

        if ($entity instanceof GitHubIssue && $entity->getAssignedTo()) {
            // Add activity to the related Partner entity
            /** @var GitHubAccount $githubAccount */
            $githubAccount = $entity->getAssignedTo();
            $this->activityManager->addActivityTarget($entity, $githubAccount->getPartner());

            // Populate entity owner from the Integration settings
            $this->defaultOwnerHelper->populateChannelOwner($entity, $entity->getChannel());
        }
    }
}
