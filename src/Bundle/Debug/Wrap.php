<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace  Dspbee\Bundle\Debug;

use Dspbee\Bundle\Template\Native;
use Dspbee\Core\Response;

/**
 * Render exception.
 *
 * Class Wrap
 * @package Dspbee\Bundle\Debug
 */
class Wrap
{
    /**
     * @param \Throwable $e
     *
     * @return Response
     */
    public static function render(\Throwable $e): Response
    {
        $data = [
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTrace()
        ];

        foreach ($data['trace'] as $k => $item) {
            switch ($item['type']) {
                case '->':
                    $data['trace'][$k]['type'] = 'method';
                    break;
                case '::':
                    $data['trace'][$k]['type'] = 'static method';
                    break;
                default:
                    $data['trace'][$k]['type'] = 'function';
            }
        }

        $template = new Native(dirname(__FILE__) . '/', null, true);
        $response = new Response();
        $response->setContent($template->getContent('catch.html.php', $data));
        return $response;
    }
}