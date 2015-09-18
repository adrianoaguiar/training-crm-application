<?php

namespace OroCRM\Bundle\PartnerBundle\Migrations\Schema\v1_4;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Migration;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

class OroCRMPartnerBundle implements Migration
{
    /**
     * @inheritdoc
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        self::updateTransportTable($schema);
    }

    /**
     * Update table oro_integration_transport
     *
     * @param Schema $schema
     */
    public static function updateTransportTable(Schema $schema)
    {
        $table = $schema->getTable('oro_integration_transport');
        $table->changeColumn('github_api_token', ['length' => 64]);
    }
}
