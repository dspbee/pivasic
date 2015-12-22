<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\BaseController;
use Dspbee\Core\Request;

class BaseProcessTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $stub = $this->getMockForAbstractClass('Dspbee\Core\BaseController', ['', new Request()]);

        $stub->expects($this->any())->method('process');

        /**
         * @var BaseController $stub
         */
        $this->assertInstanceOf('Dspbee\Core\Response', $stub->renderContent('test'));
        $this->assertEquals('test', $stub->renderContent('test')->getContent());

        $this->assertInstanceOf('Dspbee\Core\Response', $stub->renderNative('test'));
        $this->assertNull($stub->renderNative('test')->getContent());
    }
}