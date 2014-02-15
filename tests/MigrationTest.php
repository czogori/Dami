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

        $connection = $container->get('connection');
        $schemaManipulation = $container->get('schema.manipulation');
        $schemaManipulation->clearDatabase();
        $schemaInfo = $container->get('schema.info');
        $schemaTable = $container->get('schema_table');

        $migrationFiles = new MigrationFiles(__DIR__ . '/Fixtures/Migrations', $schemaTable);

        $this->migration = new Migration($schemaTable, $migrationFiles,
            $schemaManipulation, $schemaInfo);
    }

    public function testMigrate()
    {
        $this->assertEquals(1, $this->migration->migrate());
    }
}
