<?php

namespace OroCRM\Bundle\PartnerBundle\ImportExport\Serializer;

use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\DateTimeNormalizer as BaseNormalizer;
use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\DenormalizerInterface;
use Oro\Bundle\ImportExportBundle\Serializer\Normalizer\NormalizerInterface;
use Oro\Bundle\ImportExportBundle\Serializer\Serializer;

class DateTimeNormalizer implements NormalizerInterface, DenormalizerInterface
{
    public function __construct()
    {
        $this->gitHubNormalizer = new BaseNormalizer(\DateTime::ATOM);
    }

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = array())
    {
        return $this->gitHubNormalizer->denormalize($data, $class, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = array())
    {
        return $this->gitHubNormalizer->normalize($object, $format, $context);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null, array $context = array())
    {
        return $this->gitHubNormalizer->supportsDenormalization($data, $type, $format, $context)
            && !empty($context[Serializer::PROCESSOR_ALIAS_KEY])
            && strpos($context[Serializer::PROCESSOR_ALIAS_KEY], 'github') !== false;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null, array $context = array())
    {
        return $this->gitHubNormalizer->supportsNormalization($data, $format, $context)
            && !empty($context[Serializer::PROCESSOR_ALIAS_KEY])
            && strpos($context[Serializer::PROCESSOR_ALIAS_KEY], 'github') !== false;
    }
}
