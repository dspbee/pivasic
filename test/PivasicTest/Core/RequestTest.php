<?php
namespace Pivasic\Test\Core;

use PHPUnit\Framework\TestCase;
use Pivasic\Core\Request;

class RequestTest extends TestCase
{
    public function testRequestUrl()
    {
        $request = new Request();
        $this->assertEquals('/', $request->url());

        $request = new Request([], [], '/');
        $this->assertEquals('/', $request->url());

        $request = new Request([], [], '////');
        $this->assertEquals('/', $request->url());

        $request = new Request([], [], '');
        $this->assertEquals('/', $request->url());

        $request = new Request([], [], ' ');
        $this->assertEquals('/', $request->url());

        $request = new Request(['en'], ['Custom'], '');
        $this->assertEquals('/', $request->url());

        $request = new Request(['en'], ['Custom'], 'blog');
        $this->assertEquals('/blog', $request->url());

        $request = new Request(['en'], ['Custom'], 'en');
        $this->assertEquals('/en', $request->url());

        $request = new Request(['en'], ['Custom'], 'en/blog');
        $this->assertEquals('/en/blog', $request->url());

        $request = new Request(['en'], ['Custom'], 'en/blog/');
        $this->assertEquals('/en/blog', $request->url());

        $request = new Request(['en'], ['Custom'], 'en/blog////');
        $this->assertEquals('/en/blog', $request->url());

        $request = new Request(['en'], ['Custom'], 'en/blog/?page=2');
        $this->assertEquals('/en/blog', $request->url());

        $request = new Request(['en'], ['Custom'], 'en/custom/blog?page=2');
        $this->assertEquals('/en/custom/blog', $request->url());

        $request = new Request(['ru', 'en'], ['Custom'], 'ru/custom/blog?page=2');
        $this->assertEquals('/ru/custom/blog', $request->url());

        $request = new Request([], [], 'app.dev.php/');
        $this->assertEquals('/app.dev.php', $request->url());

        $request = new Request([], [], '/app.dev.php///');
        $this->assertEquals('/app.dev.php', $request->url());

        $request = new Request([], [], 'app.dev.php');
        $this->assertEquals('/app.dev.php', $request->url());

        $request = new Request([], [], 'app.dev.php ');
        $this->assertEquals('/app.dev.php', $request->url());

        $request = new Request(['en'], ['Custom'], 'app.dev.php');
        $this->assertEquals('/app.dev.php', $request->url());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/blog');
        $this->assertEquals('/app.dev.php/blog', $request->url());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en');
        $this->assertEquals('/app.dev.php/en', $request->url());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog');
        $this->assertEquals('/app.dev.php/en/blog', $request->url());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog/');
        $this->assertEquals('/app.dev.php/en/blog', $request->url());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog////');
        $this->assertEquals('/app.dev.php/en/blog', $request->url());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog/?page=2');
        $this->assertEquals('/app.dev.php/en/blog', $request->url());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/custom/blog?page=2');
        $this->assertEquals('/app.dev.php/en/custom/blog', $request->url());

        $request = new Request(['ru', 'en'], ['Custom'], 'app.dev.php/ru/custom/blog?page=2');
        $this->assertEquals('/app.dev.php/ru/custom/blog', $request->url());
    }

    public function testRequestLanguage()
    {
        $request = new Request();
        $this->assertEmpty($request->language());

        $request = new Request(['en'], ['Custom'], 'en/custom/blog?page=2');
        $this->assertEquals('en', $request->language());

        $request = new Request(['ru', 'en'], ['Custom'], 'en/custom/blog?page=2');
        $this->assertEquals('en', $request->language());
        $this->assertEquals('ru', $request->defaultLanguage());
    }

    public function testRequestPackage()
    {
        $request = new Request([], ['Custom'], 'blog');
        $this->assertEquals('Original', $request->package());

        $request = new Request([], ['Custom'], 'Custom/blog');
        $this->assertEquals('Custom', $request->package());

        $request = new Request([], ['Manage'], 'manage/blog');
        $this->assertEquals('Manage', $request->package());
    }

    public function testRequestRoute()
    {
        $request = new Request();
        $this->assertEquals('index', $request->route());

        $request = new Request([], [], '/');
        $this->assertEquals('index', $request->route());

        $request = new Request([], [], '////');
        $this->assertEquals('index', $request->route());

        $request = new Request([], [], '');
        $this->assertEquals('index', $request->route());

        $request = new Request([], [], ' ');
        $this->assertEquals('index', $request->route());

        $request = new Request(['en'], ['Custom'], '');
        $this->assertEquals('index', $request->route());

        $request = new Request(['en'], ['Custom'], 'blog');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'en');
        $this->assertEquals('index', $request->route());

        $request = new Request(['en'], ['Custom'], 'en/blog');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'en/blog/');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'en/blog////');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'en/blog/?page=2');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'en/custom/blog?page=2');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'ru/custom/blog?page=2');
        $this->assertEquals('blog', $request->route());

        $request = new Request([], ['Custom'], 'Custom/blog');
        $this->assertEquals('blog', $request->route());

        $request = new Request([], ['Custom'], 'custom/blog');
        $this->assertEquals('blog', $request->route());

        $request = new Request([], ['Manage'], 'manage/blog');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'en/custom/blog?page=2');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'en/custom/blog?page=2');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'en/custom/blog/foo/bar');
        $this->assertEquals('blog/foo/bar', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], '/blog/foo/bar//');
        $this->assertEquals('blog/foo/bar', $request->route());



        $request = new Request([], [], 'app.dev.php');
        $this->assertEquals('app_dev_php', $request->route());

        $request = new Request([], [], 'app.dev.php/');
        $this->assertEquals('app_dev_php', $request->route());

        $request = new Request([], [], '/app.dev.php///');
        $this->assertEquals('app_dev_php', $request->route());

        $request = new Request([], [], 'app.dev.php/');
        $this->assertEquals('app_dev_php', $request->route());

        $request = new Request([], [], 'app.dev.php ');
        $this->assertEquals('app_dev_php', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php');
        $this->assertEquals('app_dev_php', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/blog');
        $this->assertEquals('app_dev_php/blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en');
        $this->assertEquals('app_dev_php/en', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog');
        $this->assertEquals('app_dev_php/en/blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog/');
        $this->assertEquals('app_dev_php/en/blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog////');
        $this->assertEquals('app_dev_php/en/blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog/?page=2');
        $this->assertEquals('app_dev_php/en/blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/custom/blog?page=2');
        $this->assertEquals('app_dev_php/en/custom/blog', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'app.dev.php/ru/custom/blog?page=2');
        $this->assertEquals('app_dev_php/ru/custom/blog', $request->route());

        $request = new Request([], ['Custom'], 'app.dev.php/Custom/blog');
        $this->assertEquals('app_dev_php/Custom/blog', $request->route());

        $request = new Request([], ['Custom'], 'app.dev.php/custom/blog');
        $this->assertEquals('app_dev_php/custom/blog', $request->route());

        $request = new Request([], ['Manage'], 'app.dev.php/manage/blog');
        $this->assertEquals('app_dev_php/manage/blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/custom/blog?page=2');
        $this->assertEquals('app_dev_php/en/custom/blog', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'app.dev.php/en/custom/blog?page=2');
        $this->assertEquals('app_dev_php/en/custom/blog', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'app.dev.php/en/custom/blog/foo/bar');
        $this->assertEquals('app_dev_php/en/custom/blog/foo/bar', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'app.dev.php//blog/foo/bar//');
        $this->assertEquals('app_dev_php//blog/foo/bar', $request->route());
    }
}
