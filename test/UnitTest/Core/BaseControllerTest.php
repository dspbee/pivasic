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
        $stub->setContent('test');
        $this->assertInstanceOf('Dspbee\Core\Response', $stub->getResponse());
        $this->assertEquals('test', $stub->getResponse()->getContent());

        $stub->setTemplate('test');
        $this->assertInstanceOf('Dspbee\Core\Response', $stub->getResponse());
        $this->assertNull($stub->getResponse()->getContent());
    }
}