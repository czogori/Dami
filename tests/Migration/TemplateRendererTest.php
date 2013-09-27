<?php

namespace Dami\Tests\Migration;

use Dami\Migration\TemplateRenderer;

/**
 * @author Arek Jaskólski <arek.jaskolski@gmail.com>
 */
class TemplateRendererTest extends \PHPUnit_Framework_TestCase
{
    public function testGetTemplatePath()
    {
        $migrationNameParser = $this->getMock('Dami\Migration\MigrationNameParser');
        $templateInitialization = $this->getMock('Dami\Migration\TemplateInitialization', null, array($migrationNameParser));
        $templateInitialization
            ->expects($this->any())
            ->method('getInitUp')
            ->will($this->returnValue('fff'));
        $templateRenderer = new TemplateRenderer($templateInitialization);
        echo $templateRenderer->render('Foo');
    }
}
