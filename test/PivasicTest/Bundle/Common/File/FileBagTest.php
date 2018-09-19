<?php
namespace Pivasic\Test\Bundle\Common\File;

use PHPUnit\Framework\TestCase;
use Pivasic\Bundle\Common\File\FileBag;

class FileBagTest extends TestCase
{

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testInvalidArgumentException()
    {
        $_FILES['foo'] = 'bar';
        new FileBag();
    }

    public function testHas()
    {
        $_FILES = [];
        $file = new FileBag();
        $this->assertFalse($file->has('test'));
    }
}