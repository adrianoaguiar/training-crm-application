<?php

namespace OroCRM\Bundle\PartnerBundle\ImportExport\Writer;

use Oro\Bundle\IntegrationBundle\Exception\TransportException;
use Oro\Bundle\IntegrationBundle\Provider\TwoWaySyncConnectorInterface;
use OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue;

class IssueExportWriter extends AbstractExportWriter
{
    const ID_KEY     = 'id';
    const NUMBER_KEY = 'number';

    /**
     * {@inheritdoc}
     */
    public function write(array $items)
    {
        $this->initFromContext();

        $entities = [];
        foreach ($items as $item) {
            /** @var GitHubIssue $entity */
            $entity = $this->getEntity();
            if (empty($item[self::ID_KEY])) {
                $issueData = $this->sendCreateRequest($item);
            } else {
                $issueData = $this->sendUpdateRequest($item);
            }
            if ($entity && isset($issueData[self::NUMBER_KEY])) {
                // We need to update our entities with remote IDs of created items
                $entity->setRemoteId($issueData[self::ID_KEY]);
                $entity->setNumber($issueData[self::NUMBER_KEY]);
                $entities[] = $entity;
            }
        }

        parent::write($entities);
    }

    /**
     * @param array $item
     * @return null|array
     */
    protected function sendCreateRequest(array $item)
    {
        $result = null;
        try {
            $result = $this->transport->createIssue($item);
        } catch (TransportException $e) {
            $this->logger->error($e->getMessage());
            $this->stepExecution->addFailureException($e);
        }

        return $result;
    }

    /**
     * @param array $item
     * @return null|array
     */
    protected function sendUpdateRequest(array $item)
    {
        $result = null;
        if ($this->getTwoWaySyncStrategy() == TwoWaySyncConnectorInterface::REMOTE_WINS) {
            $e = new \InvalidArgumentException(
                'Reverse sync with Remote Wins strategy is not supported for GitHub Issue connector'
            );
            $this->logger->error(new \RuntimeException($e));
            $this->stepExecution->addFailureException($e);
        } else {
            try {
                $result = $this->transport->updateIssue($item[self::NUMBER_KEY], $item);
            } catch (TransportException $e) {
                $this->logger->error($e->getMessage());
                $this->stepExecution->addFailureException($e);
            }
        }

        return $result;
    }
}
