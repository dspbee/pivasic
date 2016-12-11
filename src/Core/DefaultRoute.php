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
        $route = preg_replace('/\/\d+/u', '/D', $request->route());
        $path = $packageRoot . '/Route/' . $route . '/' . $request->method() . '.php';
        if (file_exists($path)) {
            require $path;
            $controllerClass = $request->package() . '\\Route_' . str_replace('/', '_', $route) . '\\' . $request->method();
            /**
             * @var BaseController $controller
             */
            $controller = new $controllerClass($packageRoot, $request);

            /**
             * Call handler.
             */
            $handler = $_POST['handler'] ?? $_GET['handler'] ?? 'index';
            if (method_exists($controllerClass, $handler)) {
                $controller->$handler();
                return $controller->getResponse();
            }
        }

        return null;
    }
}