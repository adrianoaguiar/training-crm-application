<?php

namespace OroCRM\Bundle\PartnerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use OroCRM\Bundle\PartnerBundle\Provider\Transport\GitHubTransport;

class GitHubTransportType extends AbstractType
{
    const NAME = 'orocrm_partner_github_transport_form_type';

    /** @var GitHubTransport */
    protected $transport;

    /**
     * @param GitHubTransport $transport
     */
    public function __construct(GitHubTransport $transport)
    {
        $this->transport = $transport;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'token',
            'password',
            ['label' => 'orocrm.partner.github_transport.token.label', 'required' => true]
        );
        $builder->add(
            'organization',
            'text',
            ['label' => 'orocrm.partner.github_transport.organization.label', 'required' => true]
        );
        $builder->add(
            'repo',
            'text',
            ['label' => 'orocrm.partner.github_transport.repository.label', 'required' => true]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['data_class' => $this->transport->getSettingsEntityFQCN()]);
    }
}
