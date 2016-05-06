<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

use Dspbee\Bundle\Template\Native;

/**
 * Process request and get response.
 *
 * Class Application
 * @package Dspbee\Core
 */
class Application
{
    /**
     * Application constructor.
     *
     * @param string $packageRoot
     * @param array $languageList
     * @param array $packageList
     */
    public function __construct($packageRoot, array $languageList = [], array $packageList = [])
    {
        $packageRoot = rtrim($packageRoot, '/') . '/';

        self::$packagePath = $packageRoot;
        self::$languageList = $languageList;
        self::$packageList = $packageList;

        /**
         * Register autoload to app/$package/src dir's.
         */
        spl_autoload_register(function ($path) use ($packageRoot) {
            $path = explode('\\', $path);
            array_shift($path);                           // Vendor
            $package = $packageRoot . array_shift($path); // Package
            $path = $package . '/src/' . implode('/', $path) . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        });
    }

    /**
     * Process request.
     *
     * @param array $packageClassList
     * @param null|string $url
     *
     * @return Response
     */
    public function run(array $packageClassList = [], $url = null): Response
    {
        self::$request = new Request(self::$languageList, self::$packageList, $url);

        self::$packagePath .= self::$request->package() . '/';

        /**
         * Process request.
         */
        if (isset($packageClassList[self::$request->package()])) {
            /**
             * Custom routing.
             */
            /**
             * Path to router class.
             */
            $path = self::$packagePath . $packageClassList[self::$request->package()] . '.php';
            if (file_exists($path)) {
                require $path;
                /**
                 * Name of router class.
                 */
                $route = self::$request->package() . '\\' .  $packageClassList[self::$request->package()];
                /**
                 * @var DefaultRoute $route
                 */
                $route = new $route(self::$packagePath, self::$request);
                if (null !== $route->getResponse()) {
                    return $route->getResponse();
                }
            } else {
                throw new \RuntimeException(sprintf('The file "%s" does not exist', $path));
            }
        }

        $response = (new DefaultRoute(self::$packagePath, self::$request))->getResponse();
        if (null !== $response) {
            return $response;
        }


        /**
         * If not found.
         */
        $response = new Response();
        $response->headerStatus(404);

        $content = '404 Not Found';
        if (file_exists(self::$packagePath . '/view/404.html.php')) {
            $content = (new Native(self::$packagePath))->getContent('404.html.php');
        }

        $response->setContent($content);

        return $response;
    }

    public static $packagePath = '';
    public static $languageList = [];
    public static $packageList = [];
    /**
     * @var null|Request
     */
    public static $request = null;
    /**
     * @var null|\mysqli
     */
    public static $mysql = null;
}