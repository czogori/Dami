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
}
