<?php

namespace Dami\Tests\Migration;

use Dami\Migration\TemplateInitialization;
use Dami\Migration\MigrationNameParser;
/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class TemplateInitializationTest extends \PHPUnit_Framework_TestCase
{
    public function testGetInitUp()
    {
        $migrationNameParser = $this->getMock('Dami\Migration\MigrationNameParser');
        $migrationNameParser
            ->expects($this->any())
            ->method('getActionObject')
            ->will($this->returnValue('Table'));
        $migrationNameParser
            ->expects($this->any())
            ->method('getModel')
            ->will($this->returnValue('foo'));
        $templateInitialization = new TemplateInitialization($migrationNameParser);
        $this->assertEquals("\$this->createTable('foo');", $templateInitialization->getInitUp());
    }

    public function testGetInitDown()
    {
        $migrationNameParser = $this->getMock('Dami\Migration\MigrationNameParser');
        $migrationNameParser
            ->expects($this->any())
            ->method('getActionObject')
            ->will($this->returnValue('Table'));
        $migrationNameParser
            ->expects($this->any())
            ->method('getModel')
            ->will($this->returnValue('foo'));
        $templateInitialization = new TemplateInitialization($migrationNameParser);
        $this->assertEquals("\$this->dropTable('foo');", $templateInitialization->getInitDown());
    }
}
