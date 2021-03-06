<?php

namespace Dami\Tests;

use Dami\Cli\Dami;
use Dami\Migration;
use Dami\Migration\MigrationFiles;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class MigrationTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $dami = new Dami();
        $container = $dami->getContainer();

        // Change an environment to test
        $connectionConfig = $container->get('connection_config');
        $connectionConfig->changeEnvironment('test');

        $schemaManipulation = $container->get('rentgen.schema.manipulation');
        $schemaManipulation->clearDatabase();
        $schemaInfo = $container->get('rentgen.schema.info');
        $schemaTable = $container->get('dami.schema_table');

        $migrationFiles = new MigrationFiles(__DIR__ . '/Fixtures/Migrations', $schemaTable);

        $this->migration = new Migration($schemaTable, $migrationFiles,
            $schemaManipulation, $schemaInfo);
    }

    public function testMigrate()
    {
        $migrationsAffected = $this->migration->migrate();
        $this->assertEquals(1, $migrationsAffected);
    }
}
