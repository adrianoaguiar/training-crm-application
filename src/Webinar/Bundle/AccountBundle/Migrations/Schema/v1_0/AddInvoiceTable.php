<?php

namespace Webinar\Bundle\AccountBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\OrderedMigrationInterface;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class AddInvoiceTable implements Migration, OrderedMigrationInterface
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createInvoiceTable($schema);
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
        $table->addColumn('discount', 'percent', ['comment' => '(DC2Type:percent)', 'notnull' => false]);
        $table->addColumn('tax', 'percent', ['comment' => '(DC2Type:percent)', 'notnull' => false]);
        $table->addColumn('note', 'string', ['length' => 255]);
        $table->addColumn('datePaid', 'datetime', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);

        $table->setPrimaryKey(['id']);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 1;
    }
}
