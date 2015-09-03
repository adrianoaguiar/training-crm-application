<?php

namespace OroCRM\Bundle\PartnerBundle\ImportExport\Serializer;

use Symfony\Bridge\Doctrine\RegistryInterface;

use Oro\Bundle\ImportExportBundle\Field\FieldHelper;
use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\ConfigurableEntityNormalizer;

use OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue;

class IssueNormalizer extends ConfigurableEntityNormalizer
{
    /** @var RegistryInterface */
    protected $registry;

    /**
     * @param FieldHelper       $fieldHelper
     * @param RegistryInterface $registry
     */
    public function __construct(FieldHelper $fieldHelper, RegistryInterface $registry)
    {
        parent::__construct($fieldHelper);
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = array())
    {
        return $data instanceof GitHubIssue;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = array())
    {
        return $type == 'OroCRM\\Bundle\\PartnerBundle\\Entity\\GitHubIssue';
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        /** @var GitHubIssue $gitHubIssue */
        $gitHubIssue = parent::denormalize($data, $class, $format, $context);

        $integration = $this->getIntegrationFromContext($context);
        $gitHubIssue->setChannel($integration);

        return $gitHubIssue;
    }

    /**
     * @param array $context
     *
     * @return Integration
     * @throws \LogicException
     */
    public function getIntegrationFromContext(array $context)
    {
        if (!isset($context['channel'])) {
            throw new \LogicException('Context should contain reference to channel');
        }

        return $this->registry
            ->getRepository('OroIntegrationBundle:Channel')
            ->getOrLoadById($context['channel']);
    }
}
