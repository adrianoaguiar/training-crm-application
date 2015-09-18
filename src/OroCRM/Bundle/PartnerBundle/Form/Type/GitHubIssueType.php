<?php

namespace OroCRM\Bundle\PartnerBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use OroCRM\Bundle\PartnerBundle\Provider\GitHubChannelType;
use OroCRM\Bundle\PartnerBundle\Form\EventListener\GitHubIssueSubscriber;

class GitHubIssueType extends AbstractType
{
    /** @var GitHubIssueSubscriber */
    protected $gitHubIssueSubscriber;

    /**
     * @param GitHubIssueSubscriber $gitHubIssueSubscriber
     */
    public function __construct(GitHubIssueSubscriber $gitHubIssueSubscriber)
    {
        $this->gitHubIssueSubscriber = $gitHubIssueSubscriber;
    }


    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber($this->gitHubIssueSubscriber);
        $builder
            ->add(
                'title',
                'text',
                [
                    'required' => true,
                    'label'    => 'orocrm.partner.githubissue.title.label'
                ]
            )
            ->add(
                'description',
                'textarea',
                [
                    'required' => true,
                    'label'    => 'orocrm.partner.githubissue.description.label'
                ]
            )->add(
                'channel',
                'oro_integration_select',
                [
                    'allowed_types' => [GitHubChannelType::TYPE]
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => 'OroCRM\Bundle\PartnerBundle\Entity\GitHubIssue',
            )
        );
    }
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'orocrm_partner_github_issue';
    }
}
