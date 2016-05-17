<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

/**
 * Interface IRoute
 * @package Dspbee\Core
 */
interface IRoute
{
    /**
     * IRoute constructor.
     *
     * @param string $packageRoot
     * @param Request $request
     */
    public function __construct($packageRoot, Request $request);

    /**
     * Get object of Response.
     *
     * @return Response|null
     */
    public function getResponse();
}