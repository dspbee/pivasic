<?php
namespace Pivasic\Test\Core;

use PHPUnit\Framework\TestCase;
use Pivasic\Core\DefaultRoute;
use Pivasic\Core\Request;

class DefaultRouteTest extends TestCase
{
    public function testRoute()
    {
        $route = new DefaultRoute();
        $this->assertNull($route->getResponse('', new Request()));
    }
}