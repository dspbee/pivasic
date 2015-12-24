<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

use Dspbee\Bundle\Common\Bag\CookieBag;
use Dspbee\Bundle\Common\Bag\EnvBag;
use Dspbee\Bundle\Common\Bag\GetBag;
use Dspbee\Bundle\Common\Bag\HeaderBag;
use Dspbee\Bundle\Common\Bag\PostBag;
use Dspbee\Bundle\Common\Bag\ServerBag;
use Dspbee\Bundle\Common\Bag\ValueBag;
use Dspbee\Bundle\Common\File\FileBag;
use Dspbee\Bundle\Common\Session\Session;

/**
 * Represents a HTTP request.
 *
 * Class Request
 * @package Dspbee\Core
 */
class Request
{
    /**
     * Request constructor.
     *
     * @param array $languageList
     * @param array $packageList
     * @param null $url
     */
    public function __construct(array $languageList = [], array $packageList = [], $url = null)
    {
        if (null === $url) {
            $url = $_SERVER['REQUEST_URI'] ?? '';
        }

        $this->method = 'GET';

        $this->languageDefault = key($languageList);
        $this->languageCode = '';
        $this->languageName = '';
        $this->package = 'Original';
        $this->packageRoute = $packageList['Original'] ?? false;
        $this->route = 'index';

        $this->get = null;
        $this->post = null;
        $this->cookie = null;
        $this->server = null;
        $this->env = null;
        $this->session = null;
        $this->file = null;
        $this->header = null;
        $this->data = null;

        $url = explode('?', $url);
        $url = $url[0];
        $url = trim(trim($url), '/');
        if ('' !== $url) {
            $partList = explode('/', $url);
            /**
             * Delete front controller.
             */
            if (false !== strpos($partList[0], '.php')) {
                array_shift($partList);
            }
            /**
             * Check language.
             */
            if (isset($partList[0]) && isset($languageList[$partList[0]])) {
                $this->languageCode = array_shift($partList);
                $this->languageName = $languageList[$this->languageCode];
            }
            /**
             * Check package.
             */
            if (isset($partList[0]) && isset($packageList[ucfirst($partList[0])])) {
                $this->package = ucfirst(array_shift($partList));
                $this->packageRoute = $packageList[$this->package];
            }
            /**
             * Get route.
             */
            if (count($partList)) {
                $this->route = implode('/', $partList);
                $this->route = trim($this->route, '/');
            }
        }

        /**
         * Request URL.
         */
        $this->url = '/' . $url;

        /**
         * Get method.
         */
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->method = 'AJAX';
        } else {
            if (isset($_SERVER['REQUEST_METHOD'])) {
                $this->method = $_SERVER['REQUEST_METHOD'];
            }
        }
    }

    /**
     * Path without an optional query.
     *
     * @return string
     */
    public function url()
    {
        return $this->url;
    }

    /**
     * Create absolute url path or full uri from route.
     *
     * @param string $route
     * @param bool $domain
     *
     * @return string
     */
    public function makeUrl($route = '', $domain = false)
    {
        $controller = '';
        if (false !== strpos($this->url, '.php')) {
            $controller = explode('.php', $this->url);
            $controller = ltrim($controller[0], '/') . '.php';
        }
        $route = trim($route, '/');
        if ('Original' == $this->package) {
            if ($this->languageCode != $this->languageDefault) {
                $url = trim('/' . $this->languageCode . '/' . $route, '/');
            } else {
                $url = trim('/' . $route, '/');
            }
        } else {
            if ($this->languageCode != $this->languageDefault) {
                $url = trim('/' . $this->languageCode . '/' . lcfirst($this->package) . '/' . $route, '/');
            } else {
                $url = trim('/' . lcfirst($this->package) . '/' . $route, '/');
            }
        }
        if ($domain) {
            $host = '';
            if (isset($_SERVER['HTTP_HOST'])) {
                $host = $_SERVER['HTTP_HOST'];
            } else if (isset($_SERVER['SERVER_NAME'])) {
                $host = $_SERVER['SERVER_NAME'];
            }
            if (
                (isset($_SERVER['HTTPS']) && 'off' !== $_SERVER['HTTPS']) ||
                (isset($_SERVER['SERVER_PORT']) && 443 == $_SERVER['SERVER_PORT'])
            ) {
                if (empty($controller)) {
                    $url = 'https://' . $host . '/' . $url;
                } else {
                    $url = 'https://' . $host . '/' . $controller . '/' . $url;
                }
            } else {
                if (empty($controller)) {
                    $url = 'http://' . $host . '/' . $url;
                } else {
                    $url = 'http://' . $host . '/' . $controller . '/' . $url;
                }
            }
        } else {
            if (empty($controller)) {
                $url = '/' . $url;
            } else {
                $url = '/' . $controller . '/' . $url;
            }
        }

        return $url;
    }


    /**
     * Request method.
     *
     * @return string
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * A default language code.
     *
     * @return string
     */
    public function languageDefault()
    {
        return $this->languageDefault;
    }

    /**
     * A language code.
     *
     * @return string
     */
    public function languageCode()
    {
        return $this->languageCode;
    }

    /**
     * A language name associated with code.
     *
     * @return string
     */
    public function languageName()
    {
        return $this->languageName;
    }

    /**
     * Name of an app package.
     *
     * @return string
     */
    public function package()
    {
        return $this->package;
    }

    /**
     * Custom routing class or false.
     *
     * @return string|bool
     */
    public function packageRoute()
    {
        return $this->packageRoute;
    }

    /**
     * URL path without front controller, language code, package name and an optional query.
     *
     * @return string
     */
    public function route()
    {
        return $this->route;
    }

    /**
     * The query (GET) parameters.
     *
     * @return GetBag|null
     */
    public function get(): GetBag
    {
        if (null === $this->get) {
            $this->get = new GetBag();
        }
        return $this->get;
    }

    /**
     * The query (POST) parameters.
     *
     * @return PostBag|null
     */
    public function post(): PostBag
    {
        if (null === $this->post) {
            $this->post = new PostBag();
        }
        return $this->post;
    }

    /**
     * The query (COOKIE) parameters.
     *
     * @return CookieBag|null
     */
    public function cookie(): CookieBag
    {
        if (null === $this->cookie) {
            $this->cookie = new CookieBag();
        }
        return $this->cookie;
    }

    /**
     * The (SERVER) parameters.
     *
     * @return ServerBag|null
     */
    public function server(): ServerBag
    {
        if (null === $this->server) {
            $this->server = new ServerBag();
        }
        return $this->server;
    }

    /**
     * The (ENV) parameters.
     *
     * @return EnvBag|null
     */
    public function env(): EnvBag
    {
        if (null === $this->env) {
            $this->env = new EnvBag();
        }
        return $this->env;
    }

    /**
     * Get session handler.
     *
     * @return Session|null
     */
    public function session(): Session
    {
        if (null == $this->session) {
            $this->session = new Session();
        }
        return $this->session;
    }

    /**
     * The query (FILES) uploaded files.
     *
     * @return FileBag|null
     */
    public function file(): FileBag
    {
        if (null === $this->file) {
            $this->file = new FileBag();
        }
        return $this->file;
    }

    /**
     * HTTP headers from the $_SERVER variable.
     *
     * @return HeaderBag|null
     */
    public function header(): HeaderBag
    {
        if (null === $this->header) {
            $this->header = new HeaderBag();
        }
        return $this->header;
    }

    /**
     * Returns the request body content.
     *
     * @return ValueBag|null
     *
     * @see http://php.net/manual/ru/wrappers.php.php#wrappers.php.input
     */
    public function data(): ValueBag
    {
        if (null === $this->data) {
            parse_str(file_get_contents('php://input'), $temp);
            $this->data = new ValueBag($temp);
        }

        return $this->data;
    }

    private $method;

    private $url;
    private $languageDefault;
    private $languageCode;
    private $languageName;
    private $package;
    private $packageRoute;
    private $route;

    private $get;
    private $post;
    private $cookie;
    private $server;
    private $env;
    private $session;
    private $file;
    private $header;
    private $data;
}