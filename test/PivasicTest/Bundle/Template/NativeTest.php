<?php
namespace Pivasic\Test\Bundle\Template;

use PHPUnit\Framework\TestCase;
use Pivasic\Bundle\Template\Native;

class NativeTest extends TestCase
{
    /**
     * @expectedException \Pivasic\Bundle\Template\Exception\FileNotFoundException
     */
    public function testFileNotFoundException()
    {
        $template = new Native('');
        $template->getContent('');
    }

    public function testGetContent()
    {

        $root = dirname(__FILE__);
        $path = $root . '/view/test.html.php';

        mkdir($root . '/view');
        mkdir($root . '/view/_cache');

        if (file_exists($root . '/view')) {
            $fh = fopen($path, 'wb');
            fwrite($fh, '<h1><?= $title ?></h1>');
            fclose($fh);

            $data = [
                'title' => 'Hello world'
            ];

            $template = new Native($root);
            $this->assertEquals('<h1>Hello world</h1>', $template->getContent('test.html.php', $data));

            unlink($root . '/view/_cache/test.html.php');
            unlink($root . '/view/test.html.php');
            rmdir($root . '/view/_cache');
            rmdir($root . '/view');
        }
    }
}