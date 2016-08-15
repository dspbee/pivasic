<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

/**
 * Base routing.
 *
 * Class DefaultRoute
 * @package Dspbee\Core
 */
class DefaultRoute implements IRoute
{
    /**
     * Get object of Response.
     *
     * @param string $packageRoot
     * @param Request $request
     *
     * @return Response|null
     */
    public function getResponse($packageRoot, Request $request)
    {
        $packageRoot = rtrim($packageRoot, '/');
        $path = $packageRoot . '/Route/' . $request->route() . '/' . $request->method() . '.php';
        if (file_exists($path)) {
            require $path;
            $controllerClass = $this->nonsense($request->package() . '\\Route_' . str_replace('/', '_', $request->route()) . '\\' . $request->method());
            /**
             * @var BaseController $controller
             */
            $controller = new $controllerClass($packageRoot, $request);

            /**
             * Call handler.
             */
            $handler = $_POST['handler'] ?? $_GET['handler'] ?? 'index';
            if (preg_match('/[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*/', $handler) && method_exists($controllerClass, $handler)) {
                $handler = $this->nonsense($handler);
                $controller->$handler();
                return $controller->getResponse();
            }
        }

        return null;
    }

    private function nonsense($val)
    {
        return $val;
    }
}