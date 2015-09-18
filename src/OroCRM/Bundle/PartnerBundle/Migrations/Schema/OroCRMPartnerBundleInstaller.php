<?php

namespace OroCRM\Bundle\PartnerBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtension;
use Oro\Bundle\ActivityBundle\Migration\Extension\ActivityExtensionAwareInterface;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtension;
use Oro\Bundle\AttachmentBundle\Migration\Extension\AttachmentExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtension;
use Oro\Bundle\NoteBundle\Migration\Extension\NoteExtensionAwareInterface;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;

use OroCRM\Bundle\PartnerBundle\Migrations\Schema\v1_0\OroCRMPartnerBundle;
use OroCRM\Bundle\PartnerBundle\Migrations\Schema\v1_1\OroCRMPartnerBundle as OroCRMPartnerGitHub;
use OroCRM\Bundle\PartnerBundle\Migrations\Schema\v1_2\OroCRMPartnerExtensions;
use OroCRM\Bundle\PartnerBundle\Migrations\Schema\v1_3\OroCRMPartnerBundle as OroCRMPartnerIntegrationTransport;

class OroPartnerBundleInstaller implements
    Installation,
    AttachmentExtensionAwareInterface,
    NoteExtensionAwareInterface,
    ActivityExtensionAwareInterface,
    ExtendExtensionAwareInterface
{
    /** @var AttachmentExtension */
    protected $attachmentExtension;

    /** @var NoteExtension */
    protected $noteExtension;

    /** @var ActivityExtension */
    protected $activityExtension;

    /** @var  ExtendExtension */
    protected $extendExtension;

    /**
     * {@inheritdoc}
     */
    public function setAttachmentExtension(AttachmentExtension $attachmentExtension)
    {
        $this->attachmentExtension = $attachmentExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function setNoteExtension(NoteExtension $noteExtension)
    {
        $this->noteExtension = $noteExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function setActivityExtension(ActivityExtension $activityExtension)
    {
        $this->activityExtension = $activityExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function setExtendExtension(ExtendExtension $extendExtension)
    {
        $this->extendExtension = $extendExtension;
    }

    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_4';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        OroCRMPartnerBundle::createPartnerTables($schema);
        OroCRMPartnerGitHub::createGitHubAccountTable($schema);
        OroCRMPartnerGitHub::createGitHubIssueTable($schema);
        OroCRMPartnerIntegrationTransport::updateTransportTable($schema);

        $extension = new OroCRMPartnerExtensions();
        $extension->setAttachmentExtension($this->attachmentExtension);
        $extension->setActivityExtension($this->activityExtension);
        $extension->setNoteExtension($this->noteExtension);
        $extension->setExtendExtension($this->extendExtension);
        $extension->up($schema, $queries);
    }
}
