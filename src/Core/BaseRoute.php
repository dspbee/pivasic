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
    /**
     * Get object of Response.
     *
     * @return Response|null.
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * @var Response|null
     */
    protected $response = null;
}