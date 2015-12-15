<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

use Dspbee\Bundle\Template\Native;

/**
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
    public function run($packageRoot, $languageList = [], $packageList = [], $url = null)
    {
        $request = new Request($languageList, $packageList, $url);
        $packageRoot .= $request->package() . '/';

        /**
         * Register autoload.
         */
        spl_autoload_register(function ($path) use ($packageRoot) {
            $path = explode('\\', $path);
            array_shift($path);
            $path = $packageRoot . 'src/' . implode('/', $path) . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        });

        if (null !== $request->packageRoute()) {
            /**
             * Custom routing.
             */
            $path = $packageRoot . $request->packageRoute()  . '.php';
            if (file_exists($path)) {
                require_once $path;
                $route = $request->package() . 'Package\\' . $request->packageRoute();
                /**
                 * @var IRoute $route
                 */
                $route = new $route($request);
                if (null !== $route->getProcess()) {
                    $process = $route->getProcess()->process();
                    if (null !== $process) {
                        return $process;
                    }
                }
            }
        } else {
            /**
             * Default routing.
             */
            $handler = 'Index';
            if (isset($_POST['handler'])) {
                $handler = str_replace('.', '', ucfirst($_POST['handler']));
            } elseif (isset($_GET['handler'])) {
                $handler = str_replace('.', '', ucfirst($_GET['handler']));
            }

            $path = $packageRoot . 'route/' . $request->route() . '/#' . $request->method() . '/' . $handler . '.php';
            if (file_exists($path)) {
                require_once $path;
                $handler = $request->package() . 'Package\\' . $request->route() . '\\' . $request->method() . '\\' . $handler;
                /**
                 * @var IProcess $handler
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