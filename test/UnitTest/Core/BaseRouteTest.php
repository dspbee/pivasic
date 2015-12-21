<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\BaseRoute;

class BaseRouteTest extends \PHPUnit_Framework_TestCase
{
    public function testRoute()
    {
        $route = new BaseRoute();

        $this->assertNull($route->getProcess());
    }
}