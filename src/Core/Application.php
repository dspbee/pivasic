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
     * @param string $appRoot
     */
    public function __construct($appRoot)
    {
        $appRoot = rtrim($appRoot, '/') . '/';
        $this->packagePath = $appRoot;

        /**
         * Register autoload to app/$package/src dir's.
         */
        spl_autoload_register(function ($path) use ($appRoot) {
            $path = explode('\\', $path);
            array_shift($path);                           // Vendor
            $package = $appRoot . array_shift($path); // Package
            $path = $package . '/src/' . implode('/', $path) . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        });
    }

    /**
     * Process request.
     *
     * @param array $languageList
     * @param array $packageList
     * @param array $routeClassList
     * @param null|string $url
     *
     * @return Response
     */
    public function run(array $languageList, array $packageList, array $routeClassList, $url = null): Response
    {
        $request = new Request($languageList, $packageList, $url);

        $this->packagePath .= $request->package() . '/';

        /**
         * Process request.
         */
        if (isset($routeClassList[$request->package()])) {
            /**
             * Custom routing.
             */
            /**
             * Path to router class.
             */
            $path = $this->packagePath . $routeClassList[$request->package()] . '.php';
            if (file_exists($path)) {
                require $path;
                /**
                 * Name of router class.
                 */
                $route = $request->package() . '\\' .  $routeClassList[$request->package()];
                /**
                 * @var IRoute $route
                 */
                $route = new $route();
                $response = $route->getResponse($this->packagePath, $request);
                if (null !== $response) {
                    return $response;
                }
            } else {
                throw new \RuntimeException(sprintf('The file "%s" does not exist', $path));
            }
        }

        $response = (new DefaultRoute())->getResponse($this->packagePath, $request);
        if (null !== $response) {
            return $response;
        }


        /**
         * If not found.
         */
        $response = new Response();
        $response->headerStatus(404);

        $content = '404 Not Found';
        if (file_exists($this->packagePath . '/view/404.html.php')) {
            $content = (new Native($this->packagePath))->getContent('404.html.php');
        }

        $response->setContent($content);

        return $response;
    }

    private $packagePath;
}