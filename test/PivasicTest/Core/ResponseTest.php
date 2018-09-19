<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\Response;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    public function testResponse()
    {
        $response = new Response();

        $this->assertEmpty($response->getContent());

        $response->setContent('test');
        $this->assertEquals('test', $response->getContent());
    }
}