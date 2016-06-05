<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\DefaultRoute;
use Dspbee\Core\Request;

class DefaultRouteTest extends \PHPUnit_Framework_TestCase
{
    public function testRoute()
    {
        $route = new DefaultRoute();
        $this->assertNull($route->getResponse('', new Request()));
    }
}