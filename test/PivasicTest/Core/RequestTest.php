<?php
namespace Dspbee\Test\Core;

use Dspbee\Core\Request;
use PHPUnit\Framework\TestCase;

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
        $this->assertEmpty($request->languageCode());

        $request = new Request(['en'], ['Custom'], 'en/custom/blog?page=2');
        $this->assertEquals('en', $request->languageCode());

        $request = new Request(['ru', 'en'], ['Custom'], 'en/custom/blog?page=2');
        $this->assertEquals('en', $request->languageCode());
        $this->assertEquals('ru', $request->languageDefault());
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
        $this->assertEquals('index', $request->route());

        $request = new Request([], [], 'app.dev.php/');
        $this->assertEquals('index', $request->route());

        $request = new Request([], [], '/app.dev.php///');
        $this->assertEquals('index', $request->route());

        $request = new Request([], [], 'app.dev.php/');
        $this->assertEquals('index', $request->route());

        $request = new Request([], [], 'app.dev.php ');
        $this->assertEquals('index', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php');
        $this->assertEquals('index', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/blog');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en');
        $this->assertEquals('index', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog/');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog////');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/blog/?page=2');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/custom/blog?page=2');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'app.dev.php/ru/custom/blog?page=2');
        $this->assertEquals('blog', $request->route());

        $request = new Request([], ['Custom'], 'app.dev.php/Custom/blog');
        $this->assertEquals('blog', $request->route());

        $request = new Request([], ['Custom'], 'app.dev.php/custom/blog');
        $this->assertEquals('blog', $request->route());

        $request = new Request([], ['Manage'], 'app.dev.php/manage/blog');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['en'], ['Custom'], 'app.dev.php/en/custom/blog?page=2');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'app.dev.php/en/custom/blog?page=2');
        $this->assertEquals('blog', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'app.dev.php/en/custom/blog/foo/bar');
        $this->assertEquals('blog/foo/bar', $request->route());

        $request = new Request(['ru', 'en'], ['Custom'], 'app.dev.php//blog/foo/bar//');
        $this->assertEquals('blog/foo/bar', $request->route());
    }

    public function testRequestMakeUrl()
    {
        $_SERVER['HTTP_HOST'] = 'domain.com';

        $request = new Request();
        $this->assertEquals('/', $request->makeUrl());
        $this->assertEquals('/foo/bar', $request->makeUrl('foo/bar////'));
        $this->assertEquals('/foo/bar', $request->makeUrl('//foo/bar////'));
        $this->assertEquals('http://domain.com/', $request->makeUrl('', true));
        $this->assertEquals('http://domain.com/blog', $request->makeUrl('blog', true));
        $this->assertEquals('http://domain.com/foo/bar', $request->makeUrl('foo/bar////', true));
        $this->assertEquals('http://domain.com/foo/bar', $request->makeUrl('//foo/bar////', true));

        $request = new Request(['en', 'ru'], [], 'en');
        $this->assertEquals('/', $request->makeUrl('/'));
        $this->assertEquals('/foo/bar', $request->makeUrl('/foo/bar'));
        $this->assertEquals('http://domain.com/', $request->makeUrl('/', true));
        $this->assertEquals('http://domain.com/foo/bar', $request->makeUrl('/foo/bar', true));

        $request = new Request(['en', 'ru'], [], 'ru');
        $this->assertEquals('/ru', $request->makeUrl('/'));
        $this->assertEquals('/ru/foo/bar', $request->makeUrl('/foo/bar'));
        $this->assertEquals('http://domain.com/ru', $request->makeUrl('/', true));
        $this->assertEquals('http://domain.com/ru/foo/bar', $request->makeUrl('/foo/bar', true));

        $request = new Request(['en', 'ru'], ['Manage'], '/en/manage');
        $this->assertEquals('/manage', $request->makeUrl('/'));
        $this->assertEquals('/manage/foo/bar', $request->makeUrl('/foo/bar'));
        $this->assertEquals('http://domain.com/manage', $request->makeUrl('/', true));
        $this->assertEquals('http://domain.com/manage/foo/bar', $request->makeUrl('/foo/bar', true));

        $request = new Request(['en', 'ru'], ['Manage'], 'ru/manage//');
        $this->assertEquals('/ru/manage', $request->makeUrl('/'));
        $this->assertEquals('/ru/manage/foo/bar', $request->makeUrl('/foo/bar'));
        $this->assertEquals('http://domain.com/ru/manage', $request->makeUrl('/', true));
        $this->assertEquals('http://domain.com/ru/manage/foo/bar', $request->makeUrl('/foo/bar', true));

        $request = new Request(['en', 'ru'], ['Manage'], 'app.dev.php/en/manage');
        $this->assertEquals('/app.dev.php/manage', $request->makeUrl('/'));
        $this->assertEquals('/app.dev.php/manage/foo/bar', $request->makeUrl('/foo/bar'));
        $this->assertEquals('http://domain.com/app.dev.php/manage', $request->makeUrl('/', true));
        $this->assertEquals('http://domain.com/app.dev.php/manage/foo/bar', $request->makeUrl('/foo/bar', true));
    }
}
