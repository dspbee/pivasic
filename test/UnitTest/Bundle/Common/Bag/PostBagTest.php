<?php
namespace Dspbee\Test\Bundle\Common\Bag;

use Dspbee\Bundle\Common\Bag\PostBag;

class PostBagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var PostBag
     */
    protected $bag;

    protected function setUp()
    {
        parent::setUp();
        $this->bag = new PostBag();
    }

    protected function tearDown()
    {
        $this->bag = null;
    }

    /**
     * @param $key
     * @param $value
     *
     * @dataProvider setProvider
     */
    public function testPostAdd($key, $value)
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
    public function testPostGet($key, $value)
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
    public function testPostHas($key, $value)
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
    public function testPostKeys($list, $keys)
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
    public function testPostCount($list, $count)
    {
        $this->bag->add($list);
        $this->assertEquals($count, $this->bag->count());
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