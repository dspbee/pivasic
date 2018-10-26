<?php
/**
 * @license MIT
 */
namespace  Pivasic\Bundle\Debug;

use Pivasic\Bundle\Template\Native;
use Pivasic\Core\Response;

/**
 * Handle exception or fatal error and output it.
 *
 * Class Wrap
 * @package Pivasic\Bundle\Debug
 */
class Wrap
{
    /**
     * @return bool
     */
    public static function isEnabled()
    {
        return self::$debugEnabled;
    }

    /**
     * @param string $path
     */
    public static function setPackageRoot(string $path)
    {
        self::$packageRoot = $path;
    }

    /**
     * Register error handle.
     */
    public static function register()
    {
        self::$debugEnabled = true;
        set_error_handler('Pivasic\Bundle\Debug\Wrap::render');
        register_shutdown_function(['Pivasic\Bundle\Debug\Wrap', 'handleFatal']);
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
     * Handle exception.
     *
     * @param \Throwable $e
     */
    public static function handleException(\Throwable $e)
    {
        self::render($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine(), null, $e->getTrace());
    }

    /**
     * Send response.
     *
     * @param string $code
     * @param string $message
     * @param string $file
     * @param string $line
     * @param null $context
     * @param array $backtrace
     */
    public static function render(string $code, string $message, string $file, string $line, $context = null, array $backtrace = [])
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

        $template = new Native(self::$packageRoot, '', false);
        $response = new Response();
        $response->setStatusCode(418);
        $response->setContent($template->getContent('catch', $data));
        $response->send();
        print_r($data);
        exit;
    }

    private static $debugEnabled = false;
    private static $packageRoot = '';
}