<?php
namespace Dspbee\Test\Bundle\Data;

use Dspbee\Bundle\Data\TDataInit;

class TestClass
{
    use TDataInit;

    public function foo()
    {
        return $this->foo;
    }

    public function bar()
    {
        return $this->bar;
    }

    protected function setBar($value)
    {
        $this->bar = intval($value) + 2;
    }

    private $foo;
    private $bar;
}