<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Test\Bundle\Common\Bag;

use Dspbee\Bundle\Common\Bag\ValueBag;

class ValueBagTest extends \PHPUnit_Framework_TestCase
{
    public function testBag()
    {
        $bag = new ValueBag(['key' => null]);
        $this->assertEquals('default', $bag->fetch('key', 'default'));

        $bag = new ValueBag(['key' => 0]);
        $this->assertEquals(0, $bag->fetch('key', 'default'));

        $bag = new ValueBag(['key' => 1]);
        $this->assertEquals(1, $bag->fetch('key'));

        $bag = new ValueBag(['key' => 'bar']);
        $this->assertEquals(0, $bag->fetchInt('key'));

        $bag = new ValueBag(['key' => '12.8']);
        $this->assertEquals(12, $bag->fetchInt('key'));

        $bag = new ValueBag(['key' => '12.8']);
        $this->assertEquals(12.8, $bag->fetchFloat('key'));

        $bag = new ValueBag(['key' => '12.8']);
        $this->assertEquals(false, $bag->fetchFilter('key', null, FILTER_VALIDATE_EMAIL));

        $bag = new ValueBag(['key' => 'dspbee@gmail.com']);
        $this->assertEquals('dspbee@gmail.com', $bag->fetchFilter('key', null, FILTER_VALIDATE_EMAIL));

        $call = function ($value) {
            return filter_var($value, FILTER_SANITIZE_STRING);
        };
        $bag = new ValueBag(['key' => '<script>alert(2);</script>']);
        $this->assertEquals('alert(2);', $bag->fetchCustom('key', $call));

        $bag = new ValueBag(['key' => 1]);
        $this->assertEquals(null, $bag->fetch('foo'));

        $bag = new ValueBag(['key' => 1]);
        $this->assertEquals('default', $bag->fetch('foo', 'default'));

        $bag = new ValueBag(['key' => 1]);
        $this->assertEquals(true, $bag->fetchBool('key'));

        $bag = new ValueBag(['key' => 'on']);
        $this->assertEquals(true, $bag->fetchBool('key'));

        $bag = new ValueBag(['key' => true]);
        $this->assertEquals(true, $bag->fetchBool('key'));

        $bag = new ValueBag(['key' => 'true']);
        $this->assertEquals(true, $bag->fetchBool('key'));

        $bag = new ValueBag(['key' => 'false']);
        $this->assertEquals(false, $bag->fetchBool('key'));

        $bag = new ValueBag(['key' => 'off']);
        $this->assertEquals(false, $bag->fetchBool('key'));

        $bag = new ValueBag(['key' => 'foo']);
        $this->assertEquals(false, $bag->fetchBool('key'));

        $bag = new ValueBag(['key' => 'foo']);
        $this->assertEquals(null, $bag->fetchBool('bar'));

        $bag = new ValueBag(['key' => 'foo']);
        $this->assertEquals(true, $bag->has('key'));

        $bag = new ValueBag(['key' => null]);
        $this->assertEquals(true, $bag->has('key'));

        $bag = new ValueBag(['key' => 'foo']);
        $this->assertEquals(false, $bag->has('bar'));
    }
}