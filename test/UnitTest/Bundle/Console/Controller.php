<?php
namespace Dspbee\Bundle\Console;

class Controller
{
    public static function process($root)
    {
        $argv = $_SERVER['argv'];
        array_shift($argv);
        switch ($argv[0]) {
            case 'cache:clear':
                if ($handle = opendir($root)) {
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != "..") {
                            $path = $root . '/' . $entry . '/view/cache';
                            if (file_exists($path)) {
                                self::removeFromDir($path);
                            }
                        }
                    }
                    closedir($handle);
                }
                break;
            default:
                echo "\nAvailable commands:\n\n";
                echo "cache:clear\n";
        }
    }

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