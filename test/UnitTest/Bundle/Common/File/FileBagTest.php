<?php
namespace Dspbee\Test\Bundle\Common\File;

use Dspbee\Bundle\Common\File\FileBag;

class FileBagTest extends \PHPUnit_Framework_TestCase
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