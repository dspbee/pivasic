<?php
namespace Pivasic\Test\Core;

use PHPUnit\Framework\TestCase;
use Pivasic\Core\BaseController;
use Pivasic\Core\Request;
use Pivasic\Core\Response;

class BaseProcessTest extends TestCase
{
    /**
     * @expectedException \Pivasic\Bundle\Template\Exception\FileNotFoundException
     */
    public function testSetTemplate()
    {
        $controller =  new BaseController('', new Request());
        $controller->setView('test');
    }

    public function testSetContent()
    {
        $controller =  new BaseController('', new Request());
        $controller->setContent('test');
        $this->assertInstanceOf('Pivasic\Core\Response', $controller->getResponse());
    }

    public function testGetResponse()
    {
        $controller = new BaseController('', new Request());
        $this->assertNull($controller->getResponse());
        $controller->setContent('');
        $this->assertInstanceOf('Pivasic\Core\Response', $controller->getResponse());
    }

    public function testSetResponse()
    {
        $controller = new BaseController('', new Request());
        $this->assertNull($controller->getResponse());

        $response = new Response();
        $response->setContent('test');

        $controller->setResponse($response);
        $this->assertInstanceOf('Pivasic\Core\Response', $controller->getResponse());
    }
}