<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common;

/**
 * Class TFileSystem
 * @package Dspbee\Bundle\Common
 */
trait TFileSystem
{
    /**
     * Deleting all subdirectories and files.
     *
     * @param string $dir   Path to the directory
     * @param bool   $self  If true then delete root directory
     */
    private static function removeFromDir($dir, $self = false)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ('.' != $object && '..' != $object) {
                    if ('dir' == filetype($dir . '/' .$object)) {
                        self::removeFromDir($dir . '/' . $object, true);
                    } else {
                        unlink($dir . '/' . $object);
                    }
                }
            }
            if ($self) {
                reset($objects);
                if (count(scandir($dir)) == 2) {
                    rmdir($dir);
                }
            }
        }
    }
}