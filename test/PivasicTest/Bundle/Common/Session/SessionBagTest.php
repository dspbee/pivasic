<?php
namespace Dspbee\Test\Bundle\Common\Session;

use Dspbee\Bundle\Common\Session\SessionBag;
use PHPUnit\Framework\TestCase;

session_start();

class SessionBagTest extends TestCase
{
    /**
     * @var SessionBag
     */
    protected $bag;

    protected function setUp()
    {
        parent::setUp();
        $this->bag = new SessionBag();
    }

    protected function tearDown()
    {
        $this->bag = null;
        $_SESSION = [];
    }

    /**
     * @param $key
     * @param $value
     *
     * @dataProvider setProvider
     */
    public function testSessionAdd($key, $value)
    {
        $this->bag->add([$key => $value]);
        $this->assertEquals($value, $this->bag->fetch($key));
    }

    /**
     * @param $key
     * @param $value
     *
     * @dataProvider setProvider
     */
    public function testSessionGet($key, $value)
    {
        $this->bag->add([$key => $value]);
        $this->assertEquals($value, $this->bag->fetch($key));
    }

    /**
     * @param $key
     * @param $value
     *
     * @dataProvider setProvider
     */
    public function testSessionHas($key, $value)
    {
        $this->bag->add([$key => $value]);
        $this->assertTrue($this->bag->has($key));
    }

    /**
     * @param $list
     * @param $keys
     *
     * @dataProvider setProviderKeys
     */
    public function testSessionKeys($list, $keys)
    {
        $this->bag->add($list);
        $this->assertEquals($keys, $this->bag->keys());
    }

    /**
     * @param $list
     * @param $count
     *
     * @dataProvider setProviderCount
     */
    public function testSessionCount($list, $count)
    {
        $this->bag->add($list);
        $this->assertEquals($count, $this->bag->count());
    }

    public function setSessionSet()
    {
        $this->bag->set('foo_', '_bar');
        $this->assertEquals('_bar', $this->bag->fetch('foo_'));
    }

    public function setProvider()
    {
        return [
            ['foo', 'bar'],
            ['foo.bar', 'pivasic'],
            ['world', 'hello bear'],
        ];
    }

    public function setProviderKeys()
    {
        return [
            [
                ['foo' => 1], ['foo']
            ],
            [
                ['foo' => 1, 'bar' => 1], ['foo', 'bar']
            ],
            [
                ['world' => 1, 'hello' => 1, 'bear' => 1], ['world', 'hello', 'bear']
            ],
        ];
    }

    public function setProviderCount()
    {
        return [
            [
                ['foo' => 1], 1
            ],
            [
                ['foo' => 1, 'bar' => 1], 2
            ],
            [
                ['world' => 1, 'hello' => 1, 'bear' => 1], 3
            ],
        ];
    }
}