<?php

namespace Dami\Tests\Migration;

use Dami\Migration\FileNameParser;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class FileNameParserTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $fileName = '20130624094755_add_table_foo.php';
        $this->fileNameParser = new FileNameParser($fileName);                
    }

    /**
     * @expectedException Exception
     */
    public function test_create_instance_without_argument()
    {
        $fileNameParser = new FileNameParser();                   
    }

    public function test_getVersion()
    {
        $this->assertEquals('20130624094755', $this->fileNameParser->getVersion());
    }

    public function test_getMigratrionName()
    {
        $this->assertEquals('add_table_foo', $this->fileNameParser->getMigrationName());
    }

    public function test_getMigrationClassName()
    {            
        $this->assertEquals('AddTableFooMigration', $this->fileNameParser->getMigrationClassName());
    }
}
