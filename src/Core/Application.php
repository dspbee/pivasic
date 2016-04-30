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
     * Process request.
     *
     * @param string $packageRoot
     * @param array $languageList
     * @param array $packageList
     * @param null $url
     *
     * @return Response
     */
    public function run($packageRoot, array $languageList = [], array $packageList = [], $url = null): Response
    {
        $request = new Request($languageList, $packageList, $url);

        /**
         * Register autoload to app/$package/src dir's.
         */
        spl_autoload_register(function ($path) use ($packageRoot) {
            $package = rtrim($packageRoot, '/') . '/';
            $path = explode('\\', $path);
            array_shift($path);             // Vendor
            $package .= array_shift($path); // Package
            $path = $package . '/src/' . implode('/', $path) . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        });

        $packageRoot .= $request->package() . '/';

        /**
         * Process request.
         */
        if (false !== $request->packageRoute()) {
            /**
             * Custom routing.
             */
            /**
             * Path to router class.
             */
            $path = $packageRoot . $request->packageRoute()  . '.php';
            if (file_exists($path)) {
                require $path;
                /**
                 * Name of router class.
                 */
                $route = $request->package() . '\\' . $request->packageRoute();
                /**
                 * @var BaseRoute $route
                 */
                $route = new $route($request);
                if (null !== $route->getResponse()) {
                    return $route->getResponse();
                }
            } else {
                throw new \RuntimeException(sprintf('The file "%s" does not exist', $path));
            }
        }  else {
            $route = new BaseRoute();
            $route->default($packageRoot, $request);
            if (null !== $route->getResponse()) {
                return $route->getResponse();
            }
        }

        /**
         * If not found.
         */
        $response = new Response();
        $response->headerStatus(404);

        $content = '404 Not Found';
        if (file_exists($packageRoot . '/view/404.html.php')) {
            $template = new Native($packageRoot);
            $content = $template->getContent('404.html.php');
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * @var null|\mysqli
     */
    public static $mysql = null;
}