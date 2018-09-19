<?php
namespace Dspbee\Test\Bundle\Common\Bag;

use Dspbee\Bundle\Common\Bag\ServerBag;
use PHPUnit\Framework\TestCase;

class ServerBagTest extends TestCase
{
    /**
     * @var ServerBag
     */
    protected $bag;

    protected function setUp()
    {
        parent::setUp();
        $this->bag = new ServerBag();
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
    public function testServerAdd($key, $value)
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
    public function testServerGet($key, $value)
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
    public function testServerHas($key, $value)
    {
        $this->bag->add([$key => $value]);
        $this->assertTrue($this->bag->has($key));
    }


    public function setProvider()
    {
        return [
            ['foo', 'bar'],
            ['foo.bar', 'pivasic'],
            ['world', 'hello bear'],
        ];
    }
}