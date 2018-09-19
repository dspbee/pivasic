<?php
namespace Dspbee\Test\Bundle\Database;

use Dspbee\Bundle\Database\MySQL;
use PHPUnit\Framework\TestCase;

class MySQLTest extends TestCase
{
    public function testConnect()
    {
        $mysql = new MySQL('', '', '', '');
        $this->assertNull($mysql->connect());
    }
}