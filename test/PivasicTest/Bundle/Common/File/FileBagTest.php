<?php
namespace Dspbee\Test\Bundle\Common\File;

use Dspbee\Bundle\Common\File\FileBag;
use PHPUnit\Framework\TestCase;

class FileBagTest extends TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentException()
    {
        $_FILES['foo'] = 'bar';
        new FileBag($_FILES);
    }

    public function testHas()
    {
        $file = new FileBag([]);
        $this->assertFalse($file->has('test'));
    }
}