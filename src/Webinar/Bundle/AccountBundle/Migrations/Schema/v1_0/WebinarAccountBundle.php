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
        $this->createInvoiceTable($schema);
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

        $this->extendExtension->addManyToOneRelation(
            $schema,
            $table,
            'invoice',
            'webinar_invoice',
            'note',
            ['extend' => ['owner' => ExtendScope::OWNER_CUSTOM, 'is_extend' => true]]
        );
    }

    /**
     * Create webinar_invoice table
     *
     * @param Schema $schema
     */
    protected function createInvoiceTable(Schema $schema)
    {
        $table = $schema->createTable('webinar_invoice');

        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('total', 'money', ['precision' => 19, 'scale' => 4, 'comment' => '(DC2Type:money)']);
        $table->addColumn('discount', 'percent', ['comment' => '(DC2Type:percent)']);
        $table->addColumn('tax', 'percent', ['comment' => '(DC2Type:percent)']);
        $table->addColumn('note', 'string', ['length' => 255]);
        $table->addColumn('datePaid', 'datetime', []);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);

        $table->setPrimaryKey(['id']);
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
