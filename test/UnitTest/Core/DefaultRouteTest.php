<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\DefaultRoute;
use Dspbee\Core\Request;

class DefaultRouteTest extends \PHPUnit_Framework_TestCase
{
    public function testRoute()
    {
        $route = new DefaultRoute('', new Request());

        $this->assertNull($route->getResponse());
    }
}