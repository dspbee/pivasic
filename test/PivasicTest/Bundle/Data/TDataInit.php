<?php
namespace Dspbee\Test\Bundle\Data;

use PHPUnit\Framework\TestCase;

class TDataInitTest extends TestCase
{
    public function testInitTrait()
    {
        $data = [
            'foo' => 'hello',
            'bar' => 3247
        ];

        $d = new TestClass();
        $d->initFromArray($data);

        $this->assertEquals('hello', $d->foo());
        $this->assertEquals(3249, $d->bar());
    }
}