<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

/**
 * Class Application
 * @package Dspbee\Core
 */
class Application
{
    public function __construct($root)
    {
        $this->root = $root;
        $this->bootstrap();
    }

    /**
     * Process request.
     *
     * @param array $languageList
     * @param array $packageList
     * @param null $url
     *
     * @return Response
     */
    public function run($languageList = [], $packageList = [], $url = null)
    {
        $request = new Request($languageList, $packageList, $url);

        if (null !== $request->packageRoute()) {
            /**
             * Custom routing.
             */
            $path = $this->root . $request->package() . '/' . $request->packageRoute()  . '.php';
            if (file_exists($path)) {
                require_once $path;
                $route = $request->package() . 'Package\\' . $request->packageRoute();
                /**
                 * @var IRoute $route
                 */
                $route = new $route($request);
                return $route->getProcess()->process($request);
            }
        } else {
            /**
             * Default routing.
             */
            $handler = 'Index';
            if (isset($_POST['handler'])) {
                $handler = str_replace('.', '', $_POST['handler']);
            } elseif (isset($_GET['handler'])) {
                $handler = str_replace('.', '', $_GET['handler']);
            }

            $path = $this->root . $request->package() . '/route/' . $request->route() . '/#' . $request->method() . '/' . $handler . '.php';
            if (file_exists($path)) {
                require_once $path;
                $handler = $request->package() . 'Package\\' . $request->route() . '\\' . $request->method() . '\\' . $handler;
                /**
                 * @var IProcess $handler
                 */
                $handler = new $handler;
                return $handler->process($request);
            }
        }

        /**
         * If not found.
         */
        $response = new Response();
        $response->headerStatus(404);

        $content = '404 Not Found';
        $path = $this->root . $request->package() . '/view/404.html.php';
        if (file_exists($path)) {
            ob_start();
            require $path;
            $content = ob_get_clean();
        }
        $response->setContent($content);

        return $response;
    }


    private function bootstrap()
    {
        /**
         * Autoload from app/class directory.
         */
        $root = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
        spl_autoload_register(function ($path) use ($root) {
            $path = explode('\\', $path);
            if (1 < count($path)) {
                unset($path[0]);
            }
            $path = $root . implode(DIRECTORY_SEPARATOR, $path) . '.php';
            if (file_exists($path)) {
                require_once $path;
            }
        });
    }

    private $root;
}