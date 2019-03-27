<?php
/**
 * @license MIT
 */
namespace Pivasic\Core;

/**
 * Interface IRoute
 * @package Pivasic\Core
 */
interface IRoute
{
    /**
     * Find and call controller, get Response object.
     *
     * @param string $packageRoot
     * @param Request $request
     * @return Response
     * @throws \Pivasic\Core\Exception\RouteException
     */
    public function getResponse(string &$packageRoot, Request &$request): Response;
}