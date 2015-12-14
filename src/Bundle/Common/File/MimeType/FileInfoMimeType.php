<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\File\MimeType;

use Dspbee\Bundle\Common\File\Exception\FileNotFoundException;
use Dspbee\Bundle\Common\File\Exception\AccessDeniedException;

/**
 * Class FileInfoMimeType
 * @package Dspbee\Bundle\Common\File\MimeType
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

        if (!$fileInfo = new \finfo(FILEINFO_MIME_TYPE)) {
            return null;
        }

        return $fileInfo->file($path);
    }
}