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
         * Register autoload to package package/src dir.
         */
        spl_autoload_register(function ($path) use ($packageRoot) {
            $packageRoot = rtrim($packageRoot, '/') . '/';
            /**
             * Delete vendor from path.
             */
            $path = explode('\\', $path);
            array_shift($path);
            $path = $packageRoot . 'src/' . implode('/', $path) . '.php';
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
                if (null !== $route->getProcess()) {
                    $response = $route->getProcess()->process();
                    if (null !== $response) {
                        return $response;
                    }
                }
            }
        } else {
            /**
             * Default routing.
             */
            $handler = $_POST['handler'] ?? $_GET['handler'] ?? 'Index';
            $handler = str_replace('.', '', ucfirst($handler));

            /**
             * Path to controller class.
             */
            $path = $packageRoot . 'Route/' . $request->route() . '/' . $request->method() . '/' . $handler . '.php';
            if (file_exists($path)) {
                require $path;
                /**
                 * Name of controller class.
                 */
                $handler = $request->package() . '\\Route\\' . $request->route() . '\\' . $request->method() . '\\' . $handler;
                /**
                 * @var BaseProcess $handler
                 */
                $handler = new $handler($packageRoot, $request);
                $process = $handler->process();
                if (null !== $process) {
                    return $process;
                }
            }
        }

        /**
         * If not found.
         */
        $response = new Response();
        $response->headerStatus(404);

        $template = new Native($packageRoot);
        $content = $template->getContent('404.html.php');
        if (null === $content) {
            $content = '404 Not Found';
        }

        $response->setContent($content);

        return $response;
    }
}