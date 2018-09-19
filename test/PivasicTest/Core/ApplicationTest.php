<?php
namespace Pivasic\Test\Core;

use PHPUnit\Framework\TestCase;
use Pivasic\Core\Application;

class ApplicationTest extends TestCase
{
    public function testApp()
    {
        $app = new Application('');
        $this->assertInstanceOf('Pivasic\Core\Response', $app->getResponse([], [], []));
    }
}