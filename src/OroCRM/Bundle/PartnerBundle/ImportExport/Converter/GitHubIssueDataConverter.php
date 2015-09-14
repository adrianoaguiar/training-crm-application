<?php

namespace OroCRM\Bundle\PartnerBundle\ImportExport\Converter;

use Oro\Bundle\IntegrationBundle\ImportExport\DataConverter\AbstractTreeDataConverter;

class GitHubIssueDataConverter extends AbstractTreeDataConverter
{
    /**
     * {@inheritdoc}
     */
    protected function getHeaderConversionRules()
    {
        return [
            'originId'   => 'id',
            'id'         => 'remoteId',
            'number'     => 'number',
            'title'      => 'title',
            'body'       => 'description',
            'html_url'   => 'url',
            'state'      => 'status:id',
            'created_at' => 'createdAt',
            'updated_at' => 'updatedAt',
            'closed_at'  => 'closedAt',
            'assignee'   => 'assignedTo',
            'owner'      => 'owner:username'
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function getBackendHeader()
    {
        return array_values($this->getHeaderConversionRules());
    }
}
