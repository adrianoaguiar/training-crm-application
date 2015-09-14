<?php

namespace OroCRM\Bundle\PartnerBundle\Provider;

use Oro\Bundle\IntegrationBundle\Provider\ChannelInterface;
use Oro\Bundle\IntegrationBundle\Provider\IconAwareIntegrationInterface;

class GitHubChannelType implements ChannelInterface, IconAwareIntegrationInterface
{
    const TYPE = 'github';

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'orocrm.partner.channel_type.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getIcon()
    {
        return 'bundles/orocrmpartner/img/github-logo.png';
    }
}
