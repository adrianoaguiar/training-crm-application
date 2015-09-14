<?php

namespace OroCRM\Bundle\PartnerBundle\Provider\Connector;

use Symfony\Bridge\Doctrine\RegistryInterface;

use Oro\Bundle\ImportExportBundle\Context\ContextRegistry;
use Oro\Bundle\IntegrationBundle\Entity\Status;
use Oro\Bundle\IntegrationBundle\Provider\AbstractConnector;
use Oro\Bundle\IntegrationBundle\Logger\LoggerStrategy;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
use Oro\Bundle\IntegrationBundle\Provider\TwoWaySyncConnectorInterface;

class IssueConnector extends AbstractConnector implements TwoWaySyncConnectorInterface
{
    /** @var RegistryInterface */
    protected $registry;

    /**
     * @param ContextRegistry          $contextRegistry
     * @param LoggerStrategy           $logger
     * @param ConnectorContextMediator $contextMediator
     * @param RegistryInterface        $registry
     */
    public function __construct(
        ContextRegistry $contextRegistry,
        LoggerStrategy $logger,
        ConnectorContextMediator $contextMediator,
        RegistryInterface $registry
    ) {
        parent::__construct($contextRegistry, $logger, $contextMediator);
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'orocrm.partner.connector.issue.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getImportEntityFQCN()
    {
        return 'OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue';
    }

    /**
     * {@inheritdoc}
     */
    public function getImportJobName()
    {
        return 'github_issue_import';
    }

    /**
     * {@inheritdoc}
     */
    public function getExportJobName()
    {
        return 'github_issue_export';
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'github_issue';
    }

    /**
     * {@inheritdoc}
     */
    protected function getConnectorSource()
    {
        return $this->transport->getIssues($this->getLastSyncDate());
    }

    /**
     * @return \DateTime|null
     */
    protected function getLastSyncDate()
    {
        $channel = $this->contextMediator->getChannel($this->getContext());
        $status  = $this->registry->getRepository('OroIntegrationBundle:Channel')
            ->getLastStatusForConnector($channel, $this->getType(), Status::STATUS_COMPLETED);
        return $status ? $status->getDate() : null;
    }
}
