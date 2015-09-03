<?php

namespace OroCRM\Bundle\PartnerBundle\Migrations\Schema\v1_1;

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
        self::createGitHubAccountTable($schema);
        self::createGitHubIssueTable($schema);
    }

    public static function createGitHubAccountTable(Schema $schema)
    {
        /** Generate table orocrm_partner_github_account **/
        $table = $schema->createTable('orocrm_partner_github_account');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('partner_id', 'integer', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', ['notnull' => false]);
        $table->addColumn('username', 'string', ['length' => 100]);
        $table->addColumn('name', 'string', ['notnull' => false, 'length' => 100]);
        $table->addColumn('email', 'string', ['notnull' => false, 'length' => 100]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['partner_id'], 'IDX_4ECD86E59393F8FE', []);
        $table->addIndex(['created_at'], 'github_account_create_idx', []);
        $table->addUniqueIndex(['username'], 'github_account_username_unq');
        /** End of generate table orocrm_partner_github_account **/

        /** Generate foreign keys for table orocrm_partner_github_account **/
        $table->addForeignKeyConstraint(
            $schema->getTable('orocrm_partner'),
            ['partner_id'],
            ['id'],
            ['onDelete' => 'SET NULL', 'onUpdate' => null]
        );
        /** End of generate foreign keys for table orocrm_partner_github_account **/
    }

    public static function createGitHubIssueTable(Schema $schema)
    {
        /** Generate table orocrm_partner_github_issue **/
        $table = $schema->createTable('orocrm_partner_github_issue');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('closed_at', 'datetime', ['notnull' => false]);
        $table->addColumn('created_at', 'datetime', ['notnull' => false]);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false]);
        $table->addColumn('title', 'string', ['notnull' => true, 'length' => 255]);
        $table->addColumn('description', 'text', ['notnull' => true]);
        $table->addColumn('number', 'text', ['notnull' => false, 'unsigned' => true]);
        $table->addColumn('url', 'text', ['notnull' => false]);
        $table->addColumn('owner_id', 'integer', ['notnull' => false, 'unsigned' => true]);
        $table->addColumn('assigned_to_id', 'integer', ['notnull' => false, 'unsigned' => true]);
        $table->addColumn('remote_id', 'integer', ['notnull' => false, 'unsigned' => true]);
        $table->addColumn('channel_id', 'integer', ['notnull' => false]);
        $table->setPrimaryKey(['id']);
        $table->addIndex(['created_at'], 'github_issue_create_idx', []);
        $table->addIndex(['owner_id'], 'github_issue_owner_idx', []);
        $table->addIndex(['assigned_to_id'], 'github_issue_assignee_idx', []);
        $table->addUniqueIndex(['remote_id', 'channel_id'], 'github_issue_rid_cid_unq');
        /** End of generate table orocrm_partner_github_issue **/

    }
}
