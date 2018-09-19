<?php
/**
 * @license MIT
 */
namespace Pivasic\Core;

use Pivasic\Bundle\Template\Native;

/**
 * Initialize autoload.
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
        $this->packageRoot = $appRoot;

        /**
         * Register autoload to app/$package/src dir's.
         */
        spl_autoload_register(function ($path) use ($appRoot) {
            $path = explode('\\', $path);
            array_shift($path);                           // Vendor
            $package = $appRoot . array_shift($path);     // Package
            $path = $package . '/src/' . implode('/', $path) . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        });
    }

    /**
     * Process request.
     *
     * @param array $packageList
     * @param array $languageList
     * @param array $customRouteList
     * @param null|string $url
     *
     * @return Response
     */
    public function getResponse(array $packageList, array $languageList, array $customRouteList, $url = null): Response
    {
        $request = new Request($languageList, $packageList, $url);

        $this->packageRoot .= $request->package() . '/';

        /**
         * Process request.
         */
        if (isset($customRouteList[$request->package()])) {
            /**
             * Custom routing.
             */
            /**
             * Path to router class.
             */
            $path = $this->packageRoot . 'CustomRoute.php';
            if (file_exists($path)) {
                require $path;
                /**
                 * Name of router class.
                 */
                $route = $request->package() . '\\CustomRoute';
                /**
                 * @var IRoute $route
                 */
                $route = new $route();
                $response = $route->getResponse($this->packageRoot, $request);
                if (null !== $response) {
                    return $response;
                }
            } else {
                throw new \RuntimeException(sprintf('The file "%s" does not exist', $path));
            }
        }

        $response = (new DefaultRoute())->getResponse($this->packageRoot, $request);
        if (null !== $response) {
            return $response;
        }


        /**
         * If not found.
         */
        $response = new Response();
        $response->setStatusCode(404);

        $content = '404 Not Found';
        if (file_exists($this->packageRoot . '/view/404.html.php')) {
            $content = (new Native($this->packageRoot))->getContent('404.html.php');
        }

        $response->setContent($content);

        return $response;
    }

    private $packageRoot;
}