<?php

namespace Dami\Tests\Migration;

use Dami\Migration\FileNameBuilder;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class FileNameBuilderTest extends \PHPUnit_Framework_TestCase
{
    
	/**
     * @expectedException InvalidArgumentException
     */
    public function testCreateInstanceWithoutMigrationName()
    {                
    	$fileNameBuilder = new FileNameBuilder();
    }

    public function testBuild()
    {        
        $fileNameBuilder = new FileNameBuilder('AddTableFoo');        
        $dateTime = new \DateTime("2013-01-01 00:00:00");        
        $this->assertEquals('20130101000000_add_table_foo.php', $fileNameBuilder->build($dateTime->format('YmdHis')));
    }
}