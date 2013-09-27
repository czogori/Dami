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
        $migrationNameParser->setMigrationName('AddTableFoo');
        $this->assertEquals('foo', $migrationNameParser->getModel());
    }
}
