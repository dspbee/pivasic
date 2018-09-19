<?php
/**
 * @license MIT
 */
namespace Pivasic\Core;

/**
 * Base routing.
 *
 * Class DefaultRoute
 * @package Pivasic\Core
 */
class DefaultRoute implements IRoute
{
    /**
     * Find and call controller, get Response object.
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
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass($packageRoot, $request);
            } else {
                throw new \RuntimeException(sprintf('The class "%s" does not exist', $controllerClass));
            }

            /**
             * Call handler.
             */
            $handler = filter_input_array(INPUT_POST)['handler'] ?? filter_input_array(INPUT_GET)['handler'] ?? 'index';
            if (method_exists($controllerClass, $handler)) {
                $controller->$handler();
                return $controller->getResponse();
            } else {
                throw new \RuntimeException(sprintf('The method "%s" does not exist', $handler));
            }
        }

        return null;
    }
}