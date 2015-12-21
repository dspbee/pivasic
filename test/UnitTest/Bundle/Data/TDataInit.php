<?php
namespace Dspbee\Test\Bundle\Data;

class TDataInitTest extends \PHPUnit_Framework_TestCase
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