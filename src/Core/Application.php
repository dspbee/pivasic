<?php
/**
 * @license MIT
 */
namespace Pivasic\Core;

use Pivasic\Bundle\Debug\Wrap;
use Pivasic\Bundle\Template\Native;
use Pivasic\Core\Exception\RouteException;

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
    public function __construct(string $appRoot)
    {
        $appRoot = rtrim($appRoot, '/') . '/';
        $this->packageRoot = $appRoot;

        /**
         * Register autoload to app/package/$package/src dir's.
         */
        spl_autoload_register(function ($path) use ($appRoot) {
            $path = explode('\\', $path);
            array_shift($path);                                        // Vendor
            $package = $appRoot . 'package/' . array_shift($path);     // Package
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
     * @param string $url
     *
     * @return Response
     * @throws RouteException
     */
    public function getResponse(array $packageList, array $languageList, array $customRouteList, string $url = ''): Response
    {
        $request = new Request($languageList, $packageList, $url);

        $this->packageRoot .= 'package/' . $request->package() . '/';

        try {
            /**
             * Process request.
             */
            if (isset($customRouteList[$request->package()])) {
                /**
                 * Path to custom router class.
                 */
                $path = $this->packageRoot . 'CustomRoute.php';
                if (file_exists($path)) {
                    require $path;
                    $route = $request->package() . '\\CustomRoute';
                    /**
                     * @var IRoute $route
                     */
                    $route = new $route();
                    $this->response = $route->getResponse($this->packageRoot, $request);
                    if ($this->response->is404()) {
                        $this->set404();
                    }
                    return $this->response;
                } else {
                    throw new RouteException(sprintf('The file "%s" does not exist', $path));
                }
            } else {
                $this->response = (new DefaultRoute())->getResponse($this->packageRoot, $request);
                if ($this->response->is404()) {
                    $this->set404();
                }
                return $this->response;
            }
        } catch (RouteException $e) {
            if (Wrap::isEnabled()) {
                throw $e;
            } else {
                $this->response = new Response();
                $this->set404();
                return $this->response;
            }
        }
    }

    /**
     * Set 404 code and content.
     */
    private function set404()
    {
        $this->response->setStatusCode(404);
        $content = '404 Not Found';
        if (file_exists($this->packageRoot . '/view/404.html.php')) {
            $content = (new Native($this->packageRoot))->getContent('404.html.php');
        }
        $this->response->setContent($content);
    }

    private $packageRoot;
    /**
     * @var Response
     */
    private $response;
}