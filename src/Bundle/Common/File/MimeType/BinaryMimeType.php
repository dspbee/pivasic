<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\File\MimeType;

use Pivasic\Bundle\Common\File\Exception\FileNotFoundException;
use Pivasic\Bundle\Common\File\Exception\AccessDeniedException;

/**
 * Class BinaryMimeType
 * @package Pivasic\Bundle\Common\File\MimeType
 */
class BinaryMimeType
{
    /**
     * @return bool
     */
    public static function isSupported(): bool
    {
        return '\\' !== DIRECTORY_SEPARATOR && function_exists('passthru') && function_exists('escapeshellarg');
    }

    /**
     * @param $path
     * @return string
     * @throws FileNotFoundException
     * @throws AccessDeniedException
     */
    public function guess(string $path): string
    {
        if (!is_file($path)) {
            throw new FileNotFoundException($path);
        }

        if (!is_readable($path)) {
            throw new AccessDeniedException($path);
        }

        if (!self::isSupported()) {
            return '';
        }

        $return = 1;
        ob_start();
        passthru(sprintf('file -b --mime %s 2>/dev/null', escapeshellarg($path)), $return);
        if ($return > 0) {
            ob_end_clean();
            return '';
        }

        $type = trim(ob_get_clean());
        if (!preg_match('#^([a-z0-9\-]+/[a-z0-9\-\.]+)#i', $type, $match)) {
            return '';
        }

        return $match[1];
    }
}