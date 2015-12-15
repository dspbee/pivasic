<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

use Dspbee\Bundle\Template\Native;

/**
 * Class Process
 * @package Dspbee\Core
 */
abstract class Process implements IProcess
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
     * @return Response
     */
    public abstract function process();

    /**
     * Response template content.
     *
     * @param $name
     * @param array $data
     *
     * @return Response
     */
    public function renderNative($name, array $data = [])
    {
        $response = new Response;
        $template = new Native($this->packageRoot);
        $response->setContent($template->getContent($name, $data));
        return $response;
    }

    /**
     * Response content.
     *
     * @param string $content
     *
     * @return Response
     */
    public function renderContent($content)
    {
        $response = new Response();
        $response->setContent($content);
        return $response;
    }

    protected $packageRoot;
    protected $request;
}