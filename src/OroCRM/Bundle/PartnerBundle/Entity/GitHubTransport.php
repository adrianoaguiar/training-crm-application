<?php

namespace OroCRM\Bundle\PartnerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Validator\Constraints as Assert;

use Oro\Bundle\IntegrationBundle\Entity\Transport;

/**
 * @ORM\Entity
 */
class GitHubTransport extends Transport
{
    /**
     * @var string
     *
     * @ORM\Column(name="github_api_token", type="string", length=64, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=64)
     */
    protected $token;

    /**
     * @var string
     *
     * @ORM\Column(name="github_organization", type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=32)
     */
    protected $organization;

    /**
     * @var string
     *
     * @ORM\Column(name="github_repo", type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     * @Assert\Length(max=32)
     */
    protected $repo;

    /**
     * @param string $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param string $organization
     */
    public function setOrganization($organization)
    {
        $this->organization = $organization;
    }

    /**
     * @return string
     */
    public function getOrganization()
    {
        return $this->organization;
    }

    /**
     * @param string $repo
     */
    public function setRepo($repo)
    {
        $this->repo = $repo;
    }

    /**
     * @return string
     */
    public function getRepo()
    {
        return $this->repo;
    }

    /**
     * {@inheritdoc}
     */
    public function getSettingsBag()
    {
        return new ParameterBag(
            [
                'organization' => $this->organization,
                'repo'         => $this->repo,
                'token'        => $this->token
            ]
        );
    }
}
