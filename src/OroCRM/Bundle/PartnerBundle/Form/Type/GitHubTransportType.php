<?php

namespace OroCRM\Bundle\PartnerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
            ['label' => 'API Token', 'required' => true]
        );
        $builder->add(
            'organization',
            'text',
            ['label' => 'Organization', 'required' => true]
        );
        $builder->add(
            'repo',
            'text',
            ['label' => 'Repo', 'required' => true]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => $this->transport->getSettingsEntityFQCN()]);
    }
}
