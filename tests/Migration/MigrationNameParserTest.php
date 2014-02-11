<?php

namespace Dami\Tests\Migration;

use Dami\Migration\MigrationNameParser;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class MigrationNameParserTest extends \PHPUnit_Framework_TestCase
{
    public function testGetActionObject()
    {
        $migrationNameParser = new MigrationNameParser();
        $migrationNameParser->setMigrationName('AddTableFoo');
        $this->assertEquals('table', $migrationNameParser->getActionObject());
    }

    public function testGetModel()
    {
        $migrationNameParser = new MigrationNameParser();

        $migrationsNames = array(
            'AddTableFoo'    => 'foo',
            'AddTableFooBar' => 'foo_bar',
        );
        foreach ($migrationsNames as $migrationsName => $expect) {
            $migrationNameParser->setMigrationName($migrationsName);
            $this->assertEquals($expect, $migrationNameParser->getModel());
        }
    }

    public function testGettingValidAction()
    {
        $migrationNameParser = new MigrationNameParser();

        $migrationsNames = array(
            'CreateTableFoo'              => 'create',
            'AddColumnFooToTableBar'      => 'add',
            'DropTableFoo'                => 'drop',
            'RemoveColumnFooFromTableBar' => 'remove',
        );
        foreach ($migrationsNames as $migrationsName => $expect) {
            $migrationNameParser->setMigrationName($migrationsName);
            $this->assertEquals($expect, $migrationNameParser->getAction());
        }
    }

    public function testGettingNotValidAction()
    {
        $migrationNameParser = new MigrationNameParser();

        $migrationsNames = array(
            'FixTableFoo',
            'DeleteTableFoo',
        );
        foreach ($migrationsNames as $migrationsName) {
            $migrationNameParser->setMigrationName($migrationsName);
            $this->assertNull($migrationNameParser->getAction());
        }
    }

    public function testGettingValidActionObject()
    {
        $migrationNameParser = new MigrationNameParser();

        $migrationsNames = array(
            'CreateTableFoo'              => 'table',
            'AddColumnFooToTableBar'      => 'column',
            'RemoveIndexFooFromTableBar'  => 'index',
        );
        foreach ($migrationsNames as $migrationsName => $expect) {
            $migrationNameParser->setMigrationName($migrationsName);
            $this->assertEquals($expect, $migrationNameParser->getActionObject());
        }
    }

    public function testGettingNotValidActionObject()
    {
        $migrationNameParser = new MigrationNameParser();

        $migrationsNames = array(
            'CreateTypeFoo',
            'AddConstraintFooToTableBar',
        );
        foreach ($migrationsNames as $migrationsName) {
            $migrationNameParser->setMigrationName($migrationsName);
            $this->assertNull($migrationNameParser->getActionObject());
        }
    }
}
