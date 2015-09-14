<?php

namespace OroCRM\Bundle\PartnerBundle\ImportExport\Writer;

use Oro\Bundle\IntegrationBundle\Exception\TransportException;
use OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue;

class IssueExportWriter extends AbstractExportWriter
{
    const ID_KEY        = 'id';
    const ORIGIN_ID_KEY = 'originId';

    /**
     * {@inheritdoc}
     */
    public function write(array $items)
    {
        $this->initFromContext();

        $entities = [];
        foreach ($items as $item) {
            /** @var GitHubIssue $entity */
            $entity    = $this->getEntity($item[self::ORIGIN_ID_KEY]);
            $issueData = $this->sendCreateRequest($item);

            if ($entity && isset($issueData[self::ID_KEY])) {
                // We need to update our entities with remote IDs of created items
                $entity->setRemoteId($issueData[self::ID_KEY]);
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
}
