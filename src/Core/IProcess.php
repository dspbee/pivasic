<?
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

/**
 * Controller interface.
 *
 * Interface IProcess
 * @package Dspbee\Core
 */
interface IProcess
{
    /**
     * @param string $packageRoot
     * @param Request $request
     */
    public function __construct($packageRoot, Request $request);

    /**
     * Handle request.
     *
     * @return Response
     */
    public function process(): Response;
}