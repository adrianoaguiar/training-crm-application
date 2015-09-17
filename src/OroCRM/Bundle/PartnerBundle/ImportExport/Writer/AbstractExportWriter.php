<?php

namespace OroCRM\Bundle\PartnerBundle\ImportExport\Writer;

use Oro\Bundle\ImportExportBundle\Context\ContextInterface;
use Oro\Bundle\IntegrationBundle\Entity\Channel as Integration;
use Oro\Bundle\IntegrationBundle\ImportExport\Writer\PersistentBatchWriter;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorContextMediator;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;

abstract class AbstractExportWriter extends PersistentBatchWriter
{
    /** @var TransportInterface */
    protected $transport;

    /** @var ConnectorContextMediator */
    protected $contextMediator;

    /** @var Integration */
    protected $channel;

    /**
     * @param ConnectorContextMediator $contextMediator
     */
    public function setContextMediator(ConnectorContextMediator $contextMediator)
    {
        $this->contextMediator = $contextMediator;
    }

    /**
     * @ TransportInterface $transport
     */
    public function initFromContext()
    {
        $this->transport = $this->contextMediator->getTransport($this->getContext(), true);
        $this->channel = $this->contextMediator->getChannel($this->getContext());
        if (!$this->transport) {
            throw new \InvalidArgumentException('Transport was not provided');
        }
        $this->transport->init($this->channel->getTransport());
    }

    /**
     * @return ContextInterface
     */
    protected function getContext()
    {
        return $this->contextRegistry->getByStepExecution($this->stepExecution);
    }

    /**
     * @return object
     */
    protected function getEntity()
    {
        if (!$this->getContext()->hasOption('entity')) {
            throw new \InvalidArgumentException('Option "entity" was not configured');
        }

        $entity = $this->getContext()->getOption('entity');
        if (!$entity) {
            throw new \InvalidArgumentException('Missing entity in context');
        }

        return $entity;
    }

    /**
     * @return string
     */
    protected function getTwoWaySyncStrategy()
    {
        return (string)$this->getContext()->getOption('twoWaySyncStrategy');
    }
}
