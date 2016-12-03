<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace  Dspbee\Bundle\Debug;

use Dspbee\Bundle\Template\Native;
use Dspbee\Core\Response;

/**
 * Render exception or fatal error.
 *
 * Class Wrap
 * @package Dspbee\Bundle\Debug
 */
class Wrap
{
    public static $debugEnabled = false;

    /**
     * Register error handle.
     */
    public static function register()
    {
        set_error_handler('Dspbee\Bundle\Debug\Wrap::render');
        register_shutdown_function(['Dspbee\Bundle\Debug\Wrap', 'handleFatal']);
    }

    /**
     * Handle exception.
     *
     * @param \Throwable $e
     *
     * @return Response
     */
    public static function handleException(\Throwable $e): Response
    {
        self::render($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), null, $e->getTrace());
    }

    /**
     * Handle fatal error.
     */
    public static function handleFatal()
    {
        $error = error_get_last();
        if(null !== $error) {
            self::render($error["type"], $error["message"], $error["file"], $error["line"]);
        }
    }

    /**
     * Send response.
     *
     * @param string $code
     * @param string $message
     * @param string $file
     * @param string $line
     * @param null $context
     * @param array|null $backtrace
     */
    public static function render($code, $message, $file, $line, $context = null, array $backtrace = [])
    {
        if (ob_get_length()) {
            ob_clean();
        }
        $data = [
            'message' => $message,
            'code' => $code,
            'file' => $file,
            'line' => $line,
            'trace' => $backtrace,
            'context' => $context
        ];

        if (!empty($data['trace'])) {
            foreach ($data['trace'] as $k => $item) {
                if (isset($item['type'])) {
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
            }
        }

        $template = new Native(dirname(__FILE__) . '/', null, true);
        $response = new Response();
        $response->headerStatus(418);
        $response->setContent($template->getContent('catch.html.php', $data));
        $response->send();
        exit;
    }
}