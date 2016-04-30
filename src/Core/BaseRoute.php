<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

/**
 * Base functions to custom route.
 *
 * Class Route
 * @package Dspbee\Core
 */
class BaseRoute
{
    public function __construct()
    {
        $this->response = null;
    }

    /**
     * Get object of Response.
     *
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param string $packageRoot
     * @param Request $request
     */
    public function default($packageRoot, Request $request)
    {
        $packageRoot = rtrim($packageRoot, '/');
        $path = $packageRoot . '/Route/' . $request->route() . '/' . $request->method() . '.php';
        if (file_exists($path)) {
            $this->loadResource($path);
            $controllerClass = $request->package() . '\\Route_' . str_replace('/', '_', $request->route()) . '\\' . $request->method();

            /**
             * @var BaseController $controller
             */
            $controller = new $controllerClass($packageRoot, $request);

            /**
             * Call handler.
             */
            $handler = $_POST['handler'] ?? $_GET['handler'] ?? 'index';
            $handler = str_replace('.', '', $handler);
            if (method_exists($controllerClass, $handler)) {
                $controller->$handler();
                $this->response = $controller->getResponse();
            }
        }
    }

    private function loadResource($resource)
    {
        return require_once $resource;
    }

    private $response;
}