<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\BaseProcess;
use Dspbee\Core\Request;

class BaseProcessTest extends \PHPUnit_Framework_TestCase
{
    public function testProcess()
    {
        $stub = $this->getMockForAbstractClass('Dspbee\Core\BaseProcess', ['', new Request()]);

        $stub->expects($this->any())->method('process');

        /**
         * @var BaseProcess $stub
         */
        $this->assertInstanceOf('Dspbee\Core\Response', $stub->renderContent('test'));
        $this->assertEquals('test', $stub->renderContent('test')->getContent());

        $this->assertInstanceOf('Dspbee\Core\Response', $stub->renderNative('test'));
        $this->assertNull($stub->renderNative('test')->getContent());
    }
}