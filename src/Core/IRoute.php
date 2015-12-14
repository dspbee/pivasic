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
     * @param Request $request
     */
    public function __construct(Request $request);

    /**
     * Get object of IProcess to handle the request.
     *
     * @return IProcess.
     */
    public function getProcess();
}