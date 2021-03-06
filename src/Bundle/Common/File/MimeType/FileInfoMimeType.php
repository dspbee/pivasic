<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\File\MimeType;

use Pivasic\Bundle\Common\File\Exception\FileNotFoundException;
use Pivasic\Bundle\Common\File\Exception\AccessDeniedException;

/**
 * Class FileInfoMimeType
 * @package Pivasic\Bundle\Common\File\MimeType
 */
class FileInfoMimeType
{
    /**
     * @return bool
     */
    public static function isSupported()
    {
        return function_exists('finfo_open');
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

        if (!$fileInfo = new \finfo(FILEINFO_MIME_TYPE)) {
            return '';
        }

        return $fileInfo->file($path);
    }
}