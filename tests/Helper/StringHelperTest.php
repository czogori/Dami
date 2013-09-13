<?php

namespace Dami\Tests\Helper;

use Dami\Helper\StringHelper;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class StringHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testCamielize()
    {                
        $this->assertEquals('CreateTable', StringHelper::camelize('create_table'));
    }

    public function testUnderscore()
    {        
        $this->assertEquals('create_table', StringHelper::underscore('CreateTable'));
        $this->assertEquals('create_table', StringHelper::underscore('createTable'));
    }
}