<?php
namespace Dspbee\Test\Bundle\Common\Session;

use Dspbee\Bundle\Common\Session\Session;

session_destroy();

class SessionTest extends \PHPUnit_Framework_TestCase
{
    public function testBag()
    {
        $session = new Session();

        $this->assertNull($session->bag());
    }
}