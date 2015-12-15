<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Template;

/**
 * Class Native
 * @package Dspbee\Bundle\Template
 */
class Native
{
    /**
     * @param string $packageRoot
     */
    public function __construct($packageRoot)
    {
        $this->packageRoot = $packageRoot;
    }

    /**
     * Create content from template and data.
     *
     * @param string $name
     * @param array $data
     *
     * @return string|null
     */
    public function getContent($name, array $data = [])
    {
        $path = $this->packageRoot . 'view/cache/' . $name;
        if (!file_exists($path)) {
            $code = $this->compile($name, true, true);
            if (empty($code)) {
                return null;
            }

            $fh = fopen($path, 'wb');
            if (flock($fh, LOCK_EX)) {
                fwrite($fh, $code);
                flock($fh, LOCK_UN);
            }
            fclose($fh);
        }

        $fh = fopen($path, 'rb');
        flock($fh, LOCK_SH);

        $html = self::renderTemplate($path, $data);

        flock($fh, LOCK_UN);
        fclose($fh);

        return $html;
    }

    /**
     * Delete all cached templates.
     */
    public function clearCache()
    {
        $this->removeDir($this->packageRoot . 'view/cache');
    }

    /**
     * Create solid template.
     *
     * @param string $name
     * @param bool $processInclude
     * @param bool $processExtends
     *
     * @return string|null
     */
    private function compile($name, $processInclude, $processExtends)
    {
        $code = null;
        $path = $this->packageRoot . 'view/' . $name;

        if (file_exists($path)) {
            ob_start();
            readfile($path);
            $code = ob_get_clean();
        }

        if ($processInclude) {
            preg_match_all('/<!-- include (.*) -->/', $code, $matchList);
            if (count($matchList)) {
                foreach ($matchList[1] as $key => $template) {
                    if (!empty($matchList[0][$key]) && false !== strpos($code, $matchList[0][$key])) {
                        $template = trim($template);
                        $code = str_replace($matchList[0][$key], $this->compile($template, true, false), $code);
                    }
                }
            }
        }

        if ($processExtends) {
            preg_match_all('/<!-- extends (.*) -->/', $code, $matchList);
            if (isset($matchList[1][0])) {
                $template = trim($matchList[1][0]);
                $parentHtml = $this->compile($template, true, false);

                $code = str_replace($matchList[0][0], '', $code);
                $parentHtml = str_replace('<!-- section -->', $code, $parentHtml);
                $code = $parentHtml;
            }
        }

        return $code;
    }

    /**
     * Safe include. Used for scope isolation.
     *
     * @param string $file  File to include
     * @param array  $data  Data passed to template
     *
     * @return string
     */
    private static function renderTemplate($file, array $data)
    {
        ob_start();
        extract($data);
        include $file;
        return ob_get_clean();
    }

    /**
     * Deleting all subdirectories and files.
     *
     * @param string $dir   Path to the directory
     * @param bool   $self  If true then delete root directory
     */
    private function removeDir($dir, $self = false)
    {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ('.' != $object && '..' != $object) {
                    if ('dir' == filetype($dir . '/' .$object)) {
                        $this->removeDir($dir . '/' . $object, true);
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

    private $packageRoot;
}

