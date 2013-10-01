<?php

namespace Dami\Tests\Migration;

use Symfony\Component\DependencyInjection\ContainerBuilder;

use Dami\Migration\Api\MigrationApi;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class MigrationApiTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $manipulation = $this->getMock('Rentgen\Schema\Manipulation', null, array(new ContainerBuilder()));
        $manipulation
            ->expects($this->any())
            ->method('createTable')
            ->will($this->returnValue('Dami\Migration\Api\Table'));
        $info = $this->getMock('Rentgen\Schema\Info', null, array(new ContainerBuilder()));
        $this->api = new FooApi($manipulation, $info);
    }
    public function testCreateTable()
    {
        $this->assertInstanceOf('Dami\Migration\Api\Table', $this->api->createTable('foo'));
        $this->assertCount(1, $this->api->getActions());
    }

    public function testDropTable()
    {
        $this->assertNull($this->api->dropTable('foo'));
        $this->assertCount(1, $this->api->getActions());
    }

    public function testGetActions()
    {
        $this->api->dropTable('foo');
        $this->assertCount(1, $this->api->getActions());

        $this->api->dropTable('bar');
        $this->assertCount(2, $this->api->getActions());
    }
}

class FooApi extends MigrationApi
{

}
