<?php
/**
 * @license MIT
 */
namespace Pivasic\Core;

/**
 * Represents a HTTP request.
 *
 * Class Request
 * @package Pivasic\Core
 */
class Request
{
    /**
     * Request constructor.
     *
     * @param array $languageList
     * @param array $packageList
     * @param string $url
     */
    public function __construct(array $languageList = [], array $packageList = [], $url = '')
    {
        if ('' == $url) {
            $url = filter_input_array(INPUT_SERVER)['REQUEST_URI'] ?? '';
        }
        $this->method = 'GET';
        $this->defaultLanguage = $languageList[0] ?? '';
        $this->language = $this->defaultLanguage;
        $this->package = 'Original';
        $this->route = 'index';

        $url = explode('?', $url);
        $url = trim(trim($url[0]), '/');
        if ('' != $url) {
            $partList = explode('/', $url);

            /**
             * Check language.
             */
            if (false !== ($key = array_search($partList[0], $languageList))) {
                unset($partList[0]);
                $partList = array_values($partList);
                $this->language = $languageList[$key];
            }

            /**
             * Check package.
             */
            if (isset($partList[0]) && false !== ($key = array_search(ucfirst($partList[0]), $packageList))) {
                unset($partList[0]);
                $partList = array_values($partList);
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
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 'xmlhttprequest' == strtolower($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            $this->method = 'AJAX';
        } else {
            if (isset($_SERVER['REQUEST_METHOD']) && in_array($_SERVER['REQUEST_METHOD'], ['GET', 'POST', 'HEAD', 'PUT', 'DELETE'])) {
                $this->method = $_SERVER['REQUEST_METHOD'];
            }
        }
    }

    /**
     * Path without an optional query.
     *
     * @return string
     */
    public function url(): string
    {
        return $this->url;
    }

    /**
     * Name of an app package.
     *
     * @return string
     */
    public function package(): string
    {
        return $this->package;
    }

    /**
     * URL path without language code, package name and an optional query parameters.
     *
     * @param bool $dropIndex
     * @return string
     */
    public function route(bool $dropIndex = false): string
    {
        if ($dropIndex) {
            if ('index' == $this->route) {
                return '';
            }
        }
        return $this->route;
    }

    /**
     * Request method.
     *
     * @return string
     */
    public function method(): string
    {
        return $this->method;
    }

    /**
     * A language code.
     *
     * @return string
     */
    public function language(): string
    {
        return $this->language;
    }

    /**
     * A default language code.
     *
     * @return string
     */
    public function defaultLanguage(): string
    {
        return $this->defaultLanguage;
    }

    private $url;
    private $package;
    private $route;
    private $method;
    private $language;
    private $defaultLanguage;
}