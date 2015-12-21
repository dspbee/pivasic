<?php
namespace Dspbee\Test\Bundle\Database;

use Dspbee\Bundle\Database\MySQL;

class MySQLTest extends \PHPUnit_Framework_TestCase
{
    public function testConnect()
    {
        $mysql = new MySQL('', '', '', '');
        $this->assertNull($mysql->connect());
    }
}