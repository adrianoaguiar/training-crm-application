<?php

namespace Webinar\Bundle\DemoBundle\Migrations\Schema;

use Doctrine\DBAL\Schema\Schema;

use Oro\Bundle\MigrationBundle\Migration\Installation;
use Oro\Bundle\MigrationBundle\Migration\QueryBag;

use Webinar\Bundle\DemoBundle\Migrations\Schema\v1_0\WebinarDemoBundle;

class WebinarDemoBundleInstaller implements Installation
{
    /**
     * {@inheritdoc}
     */
    public function getMigrationVersion()
    {
        return 'v1_0';
    }

    /**
     * {@inheritdoc}
     */
    public function up(Schema $schema, QueryBag $queries)
    {
        $migration = new WebinarDemoBundle();
        $migration->up($schema, $queries);
    }
}