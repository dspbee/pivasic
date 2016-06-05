<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\Application;

class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    public function testApp()
    {
        $app = new Application('');
        $this->assertInstanceOf('Dspbee\Core\Response', $app->run([], [], []));
        $this->assertEquals('404 Not Found', $app->run([], [], [])->getContent());
    }
}