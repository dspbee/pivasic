<?php
namespace Dspbee\Test\Bundle\Template;

use Dspbee\Bundle\Template\Native;
use PHPUnit\Framework\TestCase;

class NativeTest extends TestCase
{
    /**
     * @expectedException Dspbee\Bundle\Template\Exception\FileNotFoundException
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

    public function testClearCache()
    {
        $root = dirname(__FILE__);
        $path = $root . '/view/test.html.php';

        mkdir($root . '/view');
        mkdir($root . '/view/_cache');

        if (file_exists($root . '/view')) {
            $fh = fopen($path, 'wb');
            fwrite($fh, '<h1>Hello world</h1>');
            fclose($fh);

            $this->assertEquals(2, count(scandir($root . '/view/_cache')));

            $template = new Native($root);
            $template->getContent('test.html.php');

            $this->assertEquals(3, count(scandir($root . '/view/_cache')));

            $template->clearCache();

            $this->assertEquals(2, count(scandir($root . '/view/_cache')));

            if (file_exists($root . '/view/_cache/test.html.php')) {
                unlink($root . '/view/_cache/test.html.php');
            }
            unlink($root . '/view/test.html.php');
            rmdir($root . '/view/_cache');
            rmdir($root . '/view');
        }
    }
}