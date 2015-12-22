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
abstract class BaseController
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
     * Create Response from template.
     *
     * @param string $name
     * @param array $data
     */
    public function setTemplate($name, array $data = [])
    {
        $response = new Response;
        $template = new Native($this->packageRoot, $this->request);
        $response->setContent($template->getContent($name, $data));
        $this->response = $response;
    }

    /**
     * Create Response from content.
     *
     * @param string $content
     */
    public function setContent($content)
    {
        $response = new Response();
        $response->setContent($content);
        $this->response = $response;
    }

    /**
     * Redirect to URL with statusCode and terminate app.
     *
     * @param null $url
     * @param int $statusCode
     */
    public function renderRedirect($url = null, $statusCode = 302)
    {
        $response = new Response();
        $response->redirect($url, $statusCode);
    }

    protected $packageRoot;
    protected $request;
    protected $response;
}