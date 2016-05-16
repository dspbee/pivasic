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
class DefaultRoute
{
    /**
     * DefaultRoute constructor.
     *
     * @param string $packageRoot
     * @param Request $request
     */
    public function __construct($packageRoot, Request $request)
    {
        $this->response = null;

        $packageRoot = rtrim($packageRoot, '/');
        $path = $packageRoot . '/Route/' . $request->route() . '/' . $request->method() . '.php';
        if (file_exists($path)) {
            require $path;
            $controllerClass = $request->package() . '\\Route_' . str_replace('/', '_', $request->route()) . '\\' . $request->method();

            if (preg_match('/^[\\a-zA-Z0-9_-]*$/iD', $controllerClass)) {
                /**
                 * @var BaseController $controller
                 */
                $controller = new $controllerClass($packageRoot, $request);

                /**
                 * Call handler.
                 */
                $handler = $_POST['handler'] ?? $_GET['handler'] ?? 'index';
                if (preg_match('/^[a-zA-Z0-9_-]*$/iD', $handler)) {
                    if (method_exists($controllerClass, $handler)) {
                        $controller->$handler();
                        $this->response = $controller->getResponse();
                    }
                }
            }
        }
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

    private $response;
}