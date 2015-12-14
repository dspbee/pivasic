<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\File\MimeType;

use Dspbee\Bundle\Common\File\Exception\FileNotFoundException;
use Dspbee\Bundle\Common\File\Exception\AccessDeniedException;

/**
 * Class BinaryMimeType
 * @package Dspbee\Bundle\Common\File\MimeType
 */
class BinaryMimeType
{
    /**
     * @return bool
     */
    public static function isSupported()
    {
        return '\\' !== DIRECTORY_SEPARATOR && function_exists('passthru') && function_exists('escapeshellarg');
    }

    /**
     * @param $path
     *
     * @return string|null
     *
     * @throws FileNotFoundException
     * @throws AccessDeniedException
     */
    public function guess($path)
    {
        if (!is_file($path)) {
            throw new FileNotFoundException($path);
        }

        if (!is_readable($path)) {
            throw new AccessDeniedException($path);
        }

        if (!self::isSupported()) {
            return null;
        }

        $return = 1;
        ob_start();
        passthru(sprintf('file -b --mime %s 2>/dev/null', escapeshellarg($path)), $return);
        if ($return > 0) {
            ob_end_clean();
            return null;
        }

        $type = trim(ob_get_clean());
        if (!preg_match('#^([a-z0-9\-]+/[a-z0-9\-\.]+)#i', $type, $match)) {
            return null;
        }

        return $match[1];
    }
}