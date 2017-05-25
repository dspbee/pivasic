<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

use Dspbee\Bundle\Template\Native;

/**
 * Base functions to process request.
 *
 * Class BaseController
 * @package Dspbee\Core
 */
class BaseController
{
    /**
     * @param string $packageRoot
     * @param Request $request
     */
    public function __construct($packageRoot, Request $request)
    {
        $this->packageRoot = $packageRoot;
        $this->request = $request;
        $this->response = null;
    }

    /**
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
    }

    /**
     * Create Response from template.
     *
     * @param string $name
     * @param array $data
     */
    public function setView($name = '', array $data = [])
    {
        $this->response = new Response;
        $this->response->setContent((new Native($this->packageRoot, $this->request))->getContent($name, $data));
    }

    /**
     * Create Response from content.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $this->response = new Response();
        $this->response->setContent($content);
    }

    /**
     * Redirect to URL with statusCode and terminate app.
     *
     * @param null $url
     * @param int $statusCode
     */
    public function setRedirect($url = null, $statusCode = 303)
    {
        $this->response = new Response();
        $this->response->redirect($url, $statusCode);
    }

    protected $packageRoot;
    protected $request;
    protected $response;
}