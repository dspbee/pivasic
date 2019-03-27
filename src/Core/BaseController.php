<?php
/**
 * @license MIT
 */
namespace Pivasic\Core;

use Pivasic\Bundle\Debug\Wrap;
use Pivasic\Bundle\Template\Native;

/**
 * Base functions to service request.
 *
 * Class BaseController
 * @package Pivasic\Core
 */
class BaseController
{
    /**
     * @param string $packageRoot
     * @param Request $request
     */
    public function __construct(string &$packageRoot, Request &$request)
    {
        $this->packageRoot =& $packageRoot;
        $this->request =& $request;
        $this->response = new Response();
        $this->data['request'] =& $this->request;
        $this->deleteJsonKeys = true;
    }

    /**
     * @param string $handler
     */
    public function invoke(string $handler)
    {
        $this->$handler();
    }

    /**
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @param bool $delete
     */
    protected function deleteJsonKeys(bool $delete)
    {
        $this->deleteJsonKeys = $delete;
    }

    /**
     * @param Response $response
     */
    protected function setResponse(Response &$response)
    {
        $this->response =& $response;
    }

    /**
     * Add items to the template data array.
     *
     * @param array $data
     */
    protected function addData(array $data)
    {
        $this->data = array_replace($this->data, $data);
    }

    /**
     * Create Response from template.
     *
     * @param string $name
     * @param array $data
     */
    protected function setView(string $name = '', array $data = [])
    {
        if (!empty($data)) {
            $this->data = array_replace($this->data, $data);
        }
        $content = (new Native($this->packageRoot, $this->request->language(), !Wrap::isEnabled()))->getContent($name, $this->data);
        $this->response->setContent($content);
    }

    /**
     * Create Response from content.
     *
     * @param string $content
     */
    protected function setContent(string $content)
    {
        $this->response->setContent($content);
    }

    /**
     * Create Response from content.
     *
     * @param array $content
     */
    protected function setJSONContent(array $content)
    {
        if ($this->deleteJsonKeys) {
            $content = $this->deleteArrayKeys($content);
        }
        $content = json_encode($content, JSON_UNESCAPED_UNICODE);
        $this->response->setContentTypeJson();
        $this->response->setContent($content);
    }

    /**
     * Redirect to the URL with statusCode.
     *
     * @param string $url
     * @param int $statusCode
     */
    protected function setRedirect(string $url = '', int $statusCode = 303)
    {
        $this->response->redirect($url, $statusCode);
    }

    /**
     * Get route digit's.
     *
     * @return array
     */
    protected function getNumList(): array
    {
        preg_match_all('/\/\d+/u', $this->request->route(), $numList);
        $numList = $numList[0];
        $numList = array_map(function($val) {
            return intval(ltrim($val, '/'));
        }, $numList);
        return $numList;
    }

    /**
     * @param $arr
     * @return array
     */
    private function deleteArrayKeys(&$arr): array
    {
        $lst = [];
        foreach($arr as $k => $v){
            if (is_array($v)) {
                $lst[] = $this->deleteArrayKeys($v);
            } else {
                $lst[] = $v;
            }
        }
        return $lst;
    }

    protected $request;
    protected $response;
    protected $data;
    protected $deleteJsonKeys;

    private $packageRoot;
}