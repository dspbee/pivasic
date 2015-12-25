<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\BaseController;
use Dspbee\Core\Request;
use Dspbee\Core\Response;

class BaseProcessTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException Dspbee\Bundle\Template\Exception\FileNotFoundException
     */
    public function testSetTemplate()
    {
        $controller =  new BaseController('', new Request());
        $controller->setTemplate('test');
    }

    public function testSetContent()
    {
        $controller =  new BaseController('', new Request());
        $controller->setContent('test');
        $this->assertInstanceOf('Dspbee\Core\Response', $controller->getResponse());
        $this->assertEquals('test', $controller->getResponse()->getContent());
    }

    public function testGetResponse()
    {
        $controller = new BaseController('', new Request());
        $this->assertNull($controller->getResponse());
        $controller->setContent('');
        $this->assertInstanceOf('Dspbee\Core\Response', $controller->getResponse());
    }

    public function testSetResponse()
    {
        $controller = new BaseController('', new Request());
        $this->assertNull($controller->getResponse());

        $response = new Response();
        $response->setContent('test');

        $controller->setResponse($response);
        $this->assertInstanceOf('Dspbee\Core\Response', $controller->getResponse());
        $this->assertEquals('test', $controller->getResponse()->getContent());
    }
}