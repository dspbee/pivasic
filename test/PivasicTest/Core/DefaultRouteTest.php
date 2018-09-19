<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\DefaultRoute;
use Dspbee\Core\Request;
use PHPUnit\Framework\TestCase;

class DefaultRouteTest extends TestCase
{
    public function testRoute()
    {
        $route = new DefaultRoute();
        $this->assertNull($route->getResponse('', new Request()));
    }
}