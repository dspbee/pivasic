<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\Response;

class ResponseTest extends \PHPUnit_Framework_TestCase
{
    public function testResponse()
    {
        $response = new Response();

        $this->assertEmpty($response->getContent());

        $response->setContent('test');
        $this->assertEquals('test', $response->getContent());
    }
}