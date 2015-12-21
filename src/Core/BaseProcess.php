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
 * Class Process
 * @package Dspbee\Core
 */
abstract class BaseProcess
{
    /**
     * @param string $packageRoot
     * @param Request $request
     */
    public function __construct($packageRoot, Request $request)
    {
        $this->packageRoot = $packageRoot;
        $this->request = $request;
    }

    /**
     * Create response.
     *
     * @return Response|null
     */
    public abstract function process();

    /**
     * Create Response from template.
     *
     * @param string $name
     * @param array $data
     *
     * @return Response
     */
    public function renderNative($name, array $data = []): Response
    {
        $response = new Response;
        $template = new Native($this->packageRoot, $this->request);
        $response->setContent($template->getContent($name, $data));
        return $response;
    }

    /**
     * Create Response from content.
     *
     * @param string $content
     *
     * @return Response
     */
    public function renderContent($content): Response
    {
        $response = new Response();
        $response->setContent($content);
        return $response;
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
}