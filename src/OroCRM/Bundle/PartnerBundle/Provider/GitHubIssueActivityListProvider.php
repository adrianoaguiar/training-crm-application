<?php

namespace OroCRM\Bundle\PartnerBundle\Provider;

use Oro\Bundle\ActivityListBundle\Entity\ActivityList;
use Oro\Bundle\ActivityListBundle\Entity\ActivityOwner;
use Oro\Bundle\ActivityListBundle\Model\ActivityListProviderInterface;
use Oro\Bundle\CommentBundle\Model\CommentProviderInterface;
use Oro\Bundle\EntityBundle\ORM\DoctrineHelper;
use Oro\Bundle\EntityConfigBundle\Config\ConfigManager;
use Oro\Bundle\EntityConfigBundle\Config\Id\ConfigIdInterface;

use OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue;

class GitHubIssueActivityListProvider implements ActivityListProviderInterface, CommentProviderInterface
{
    const ACTIVITY_CLASS = 'OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue';
    const ACL_CLASS      = 'OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue';

    /** @var DoctrineHelper */
    protected $doctrineHelper;

    /**
     * @param DoctrineHelper  $doctrineHelper
     */
    public function __construct(DoctrineHelper $doctrineHelper)
    {
        $this->doctrineHelper  = $doctrineHelper;
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicableTarget(ConfigIdInterface $configId, ConfigManager $configManager)
    {
        $provider = $configManager->getProvider('activity');
        return $provider->hasConfigById($configId)
            && $provider->getConfigById($configId)->has('activities')
            && in_array(self::ACTIVITY_CLASS, $provider->getConfigById($configId)->get('activities'));
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject($entity)
    {
        /** @var $entity GitHubIssue */
        return $entity->getTitle();
    }

    /**
     * {@inheritdoc}
     */
    public function getDescription($entity)
    {
        /** @var $entity GitHubIssue */
        return $entity->getDescription();
    }

    /**
     * {@inheritdoc}
     */
    public function getData(ActivityList $activityListEntity)
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getOrganization($activityEntity)
    {
        /** @var $activityEntity GitHubIssue */
        $owner = $activityEntity->getOwner();
        if ($owner && $owner->getOrganization()) {
            return $owner->getOrganization();
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'OroCRMPartnerBundle:GitHubIssue:js/activityItemTemplate.js.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getRoutes()
    {
        return [
            'itemView' => 'orocrm_partner_github_issue_widget_info',
            'itemEdit' => 'orocrm_partner_github_issue_update',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getActivityClass()
    {
        return self::ACTIVITY_CLASS;
    }

    /**
     * {@inheritdoc}
     */
    public function getActivityId($entity)
    {
        return $this->doctrineHelper->getSingleEntityIdentifier($entity);
    }

    /**
     * {@inheritdoc}
     */
    public function isApplicable($entity)
    {
        if (is_object($entity)) {
            $entity = $this->doctrineHelper->getEntityClass($entity);
        }

        return $entity == self::ACTIVITY_CLASS;
    }

    /**
     * {@inheritdoc}
     */
    public function getTargetEntities($entity)
    {
        return $entity->getActivityTargetEntities();
    }

    /**
     * {@inheritdoc}
     */
    public function hasComments(ConfigManager $configManager, $entity)
    {
        $config = $configManager->getProvider('comment')->getConfig($entity);

        return $config->is('enabled');
    }

    /**
     * {@inheritdoc}
     */
    public function getAclClass()
    {
        return self::ACL_CLASS;
    }

    /**
     * {@inheritdoc}
     */
    public function getActivityOwners($entity, ActivityList $activityList)
    {

        /** @var $entity GitHubIssue */
        $owner = $entity->getOwner();
        if (!$owner) {
            return [];
        }

        $activityOwner = new ActivityOwner();
        $activityOwner->setActivity($activityList);
        $activityOwner->setUser($owner);

        return [$activityOwner];
    }
}
