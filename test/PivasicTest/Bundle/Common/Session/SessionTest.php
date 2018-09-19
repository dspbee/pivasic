<?php
namespace Dspbee\Test\Bundle\Common\Session;

use Dspbee\Bundle\Common\Session\Session;
use PHPUnit\Framework\TestCase;

session_destroy();

class SessionTest extends TestCase
{
    public function testBag()
    {
        $session = new Session();

        $this->assertNull($session->bag());
    }
}