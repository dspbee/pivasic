<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

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
     * @param null|string $url
     */
    public function __construct(array $languageList = [], array $packageList = [], $url = null)
    {
        if (null === $url) {
            $url = $_SERVER['REQUEST_URI'] ?? '';
        }
        $this->method = 'GET';
        $this->languageDefault = $languageList[0] ?? '';
        $this->languageCode = '';
        $this->package = 'Original';
        $this->route = 'index';

        $url = explode('?', $url);
        $url = $url[0];
        $url = trim(trim($url), '/');
        if ('' !== $url) {
            $partList = explode('/', $url);

            /**
             * Delete front controller.
             */
            if (false !== strpos($partList[0], '.php')) {
                unset($partList[0]);
            }

            /**
             * Check language.
             */
            if (isset($partList[0]) && false !== ($key = array_search($partList[0], $languageList))) {
                unset($partList[0]);
                $this->languageCode = $languageList[$key];
            }

            /**
             * Check package.
             */
            if (isset($partList[0]) && false !== ($key = array_search(ucfirst($partList[0]), $packageList))) {
                unset($partList[0]);
                $this->package = $packageList[$key];
            }

            /**
             * Get route.
             */
            if (isset($partList[0])) {
                $this->route = implode('/', $partList);
                $this->route = trim(str_replace('.', '_', $this->route), '/');
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
     * Name of an app package.
     *
     * @return string
     */
    public function package()
    {
        return $this->package;
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
                $url = 'https://';
            } else {
                $url = 'http://';
            }
            if (empty($controller)) {
                $url .= $host . '/' . $url;
            } else {
                $url .= $host . '/' . $controller . '/' . $url;
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

    private $url;
    private $method;
    private $languageDefault;
    private $languageCode;
    private $package;
    private $route;
}