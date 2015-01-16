<?php

namespace Webinar\Bundle\DemoBundle\Migrations\Schema\v1_0;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class WebinarDemoBundle implements Migration
{
    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        /** Tables generation **/
        $this->createTicketTable($schema);
    }

    /**
     * Create Ticket table
     *
     * @param Schema $schema
     */
    protected function createTicketTable(Schema $schema)
    {
        $table = $schema->createTable('webinar_ticket');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('seat_num', 'integer', []);
        $table->addColumn('description', 'string', ['length' => 255]);
        $table->addColumn('event_name', 'string', ['length' => 255]);
        $table->addColumn('event_date', 'datetime', []);
        $table->addColumn('created_at', 'datetime', []);
        $table->addColumn('updated_at', 'datetime', []);

        $table->setPrimaryKey(['id']);
    }
}
