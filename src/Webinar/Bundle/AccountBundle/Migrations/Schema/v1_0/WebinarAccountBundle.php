<?php

namespace Webinar\Bundle\AccountBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\EntityExtendBundle\EntityConfig\ExtendScope;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtension;
use Oro\Bundle\EntityExtendBundle\Migration\Extension\ExtendExtensionAwareInterface;
use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class WebinarAccountBundle implements Migration, OrderedMigrationInterface, ExtendExtensionAwareInterface
{
    /** @var ExtendExtension */
    protected $extendExtension;

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->extendAccountTable($schema);
    }

    /**
     * @param Schema $schema
     */
    protected function extendAccountTable(Schema $schema)
    {
        $table = $schema->getTable('orocrm_account');

        $table->addColumn(
            'employee_count',
            'integer',
            [
                'length' => 32,
                'oro_options' => [
                    'extend'   => ['owner' => ExtendScope::OWNER_CUSTOM],
                    'datagrid' => ['is_visible' => true],
                    'form'     => ['is_enabled' => true],
                    'view'     => ['is_displayable' => true],
                    'merge'    => ['display' => true],
                ]
            ]
        );
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
    public function getOrder()
    {
        return 2;
    }
}
