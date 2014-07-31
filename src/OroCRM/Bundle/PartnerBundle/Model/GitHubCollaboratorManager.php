<?php

namespace OroCRM\Bundle\PartnerBundle\Model;

use Github\Api\Repository\Collaborators;
use Github\Client;
use Github\Exception\ExceptionInterface;

use OroCRM\Bundle\PartnerBundle\Exception\InvalidConfigurationException;
use OroCRM\Bundle\PartnerBundle\Exception\InvalidResponseException;
use OroCRM\Bundle\PartnerBundle\Provider\ConfigurationProvider;

class GitHubCollaboratorManager
{
    /**
     * @var ConfigurationProvider
     */
    protected $configProvider;

    /**
     * @var GitHubClientFactory
     */
    protected $gitHubClientFactory;

    /**
     * @var Client
     */
    protected $client;

    /**
     * @param ConfigurationProvider $configurationProvider
     * @param GitHubClientFactory   $gitHubClientFactory
     */
    public function __construct(ConfigurationProvider $configurationProvider, GitHubClientFactory $gitHubClientFactory)
    {
        $this->configProvider = $configurationProvider;
        $this->gitHubClientFactory = $gitHubClientFactory;
    }

    /**
     * @param string $username
     * @throws InvalidResponseException
     */
    public function addCollaborator($username)
    {
        foreach ($this->configProvider->getRepositories() as $repository) {
            if (empty($repository['owner']) || empty($repository['name'])) {
                continue;
            }
            try {
                $this->getCollaborators()->add($repository['owner'], $repository['name'], $username);
            } catch (ExceptionInterface $e) {
                $message = "Can't add collaborator \"{$username}\" to GitHub repository " .
                    "\"{$repository['owner']}/{$repository['name']}\".";
                throw InvalidResponseException::create($message, $e);
            }
        }
    }

    /**
     * @param string $username
     * @throws InvalidResponseException
     */
    public function removeCollaborator($username)
    {
        foreach ($this->configProvider->getRepositories() as $repository) {
            if (empty($repository['owner']) || empty($repository['name'])) {
                continue;
            }

            try {
                $this->getCollaborators()->remove($repository['owner'], $repository['name'], $username);
            } catch (ExceptionInterface $e) {
                $message = "Can't remove collaborator \"{$username}\" from GitHub repository " .
                    "\"{$repository['owner']}/{$repository['name']}\".";
                throw InvalidResponseException::create($message, $e);
            }
        }
    }

    /**
     * @return Collaborators
     */
    protected function getCollaborators()
    {
        return $this->getClient()->api('repo')->collaborators();
    }

    /**
     * @return Client
     * @throws InvalidConfigurationException
     */
    protected function getClient()
    {
        if (!$this->client) {
            $this->client = $this->gitHubClientFactory->createClient();

            $token = $this->configProvider->getApiToken();
            if (empty($token)) {
                throw new InvalidConfigurationException('GitHub API token isn\'t set.');
            }
            $this->client->authenticate($token, null, Client::AUTH_URL_TOKEN);
        }

        return $this->client;
    }
}
