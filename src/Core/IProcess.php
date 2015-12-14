<?
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

/**
 * Interface IProcess
 * @package Dspbee\Core
 */
interface IProcess
{
    /**
     * Handle request.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function process(Request $request);
}