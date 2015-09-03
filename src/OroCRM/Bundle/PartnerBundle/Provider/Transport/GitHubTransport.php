<?php

namespace OroCRM\Bundle\PartnerBundle\Provider\Transport;

use Github\Api\Issue;
use Github\Client;
use Github\Exception\ExceptionInterface;

use Oro\Bundle\IntegrationBundle\Entity\Transport;
use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;

use OroCRM\Bundle\PartnerBundle\Model\GitHubClientFactory;
use OroCRM\Bundle\PartnerBundle\Form\Type\GitHubTransportType;
use OroCRM\Bundle\PartnerBundle\Provider\Transport\RestIterator;
use OroCRM\Bundle\PartnerBundle\Exception\InvalidConfigurationException;

class GitHubTransport implements TransportInterface
{
    /**
     * @var GitHubClientFactory
     */
    protected $gitHubClientFactory;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @var string
     */
    protected $gitHubOrganization;

    /**
     * @var string
     */
    protected $gitHubRepo;

    /**
     * @param GitHubClientFactory $gitHubClientFactory
     */
    public function __construct(GitHubClientFactory $gitHubClientFactory)
    {
        $this->gitHubClientFactory = $gitHubClientFactory;
    }

    /**
     * @param GitHubClientFactory $clientFactory
     */
    public function setRestClientFactory(GitHubClientFactory $clientFactory)
    {
        $this->gitHubClientFactory = $clientFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function init(Transport $transportEntity)
    {
        $token = $transportEntity->getSettingsBag()->get('token');
        if (empty($token)) {
            throw new InvalidConfigurationException('GitHub API token isn\'t set.');
        }
        $this->gitHubOrganization = $transportEntity->getSettingsBag()->get('organization');
        if (empty($this->gitHubOrganization)) {
            throw new InvalidConfigurationException('GitHub organization isn\'t set.');
        }
        $this->gitHubRepo = $transportEntity->getSettingsBag()->get('repo');
        if (empty($this->gitHubRepo)) {
            throw new InvalidConfigurationException('GitHub repo isn\'t set.');
        }

        $this->client = $this->gitHubClientFactory->createClient();
        $this->client->authenticate($token, null, Client::AUTH_URL_TOKEN);

        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'orocrm.partner.transport.rest.label';
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsFormType()
    {
        return GitHubTransportType::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsEntityFQCN()
    {
        return 'OroCRM\Bundle\PartnerBundle\Entity\GitHubTransport';
    }

    /**
     * Get GutHub Issues
     *
     * @param \DateTime $since
     * @return \Iterator
     * @throws ExceptionInterface
     */
    public function getIssues($since = null)
    {
        $params = ['state' => 'all'];
        if ($since) {
            $params['since'] = $since->format(\DateTime::ISO8601);
        }
        $issues = new GitHubIssueIterator($this->client, $this->gitHubOrganization, $this->gitHubRepo, $params);

        return $issues;
    }

    /**
     * Create GutHub Issue
     *
     * @param array $data
     * @return array
     */
    public function createIssue($data)
    {
        $issueData = $this->client->issues()->create($this->gitHubOrganization, $this->gitHubRepo, $data);

        return $issueData;
    }
}
