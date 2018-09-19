<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Template;

use Pivasic\Bundle\Template\Exception\FileNotFoundException;

/**
 * Class Native
 * @package Pivasic\Bundle\Template
 */
class Native
{
    /**
     * @param string $packageRoot
     */
    public function __construct($packageRoot)
    {
        $this->packageRoot = rtrim($packageRoot, '/');
        $this->isRouteView = false;
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
        $cacheName = $name;
        if (false === strpos($name, '.')) {
            if (!empty($name)) {
                $name = '.' . $name;
            }
            $name = 'view' . $name . '.html.php';
            $this->isRouteView = true;

            $stack = debug_backtrace();
            foreach ($stack as $item) {
                if (false !== stripos($item['file'], DIRECTORY_SEPARATOR . 'Route' . DIRECTORY_SEPARATOR)) {
                    $cacheName = pathinfo($item['file'], PATHINFO_DIRNAME) . '/' . $name;
                    $cacheName = explode('Route' . DIRECTORY_SEPARATOR, $cacheName)[1];
                    $cacheName = str_replace(['/', '\\'], '_', $cacheName);
                    break;
                }
            }
        }
        $path = $this->packageRoot . '/view/_cache/' . str_replace('/', '_', $cacheName);

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
        if (flock($fh, LOCK_SH)) {
            $html = self::renderTemplate($path, $data);

            flock($fh, LOCK_UN);
            fclose($fh);

            return $html;
        }

        return null;
    }

    /**
     * Create solid template.
     *
     * @param string $name
     * @param bool $processInclude
     * @param bool $processExtends
     *
     * @return string|null
     *
     * @throws FileNotFoundException
     */
    private function compile($name, $processInclude, $processExtends)
    {
        if ($this->isRouteView) {
            $this->isRouteView = false;
            $path = '';
            $stack = debug_backtrace();
            foreach ($stack as $item) {
                if (false !== stripos($item['file'], DIRECTORY_SEPARATOR . 'Route' . DIRECTORY_SEPARATOR)) {
                    $path = pathinfo($item['file'], PATHINFO_DIRNAME) . '/' . $name;
                    break;
                }
            }
        } else {
            $path = $this->packageRoot . '/view/' . $name;
        }

        if (file_exists($path)) {
            ob_start();
            readfile($path);
            $code = ob_get_clean();
        } else {
            throw new FileNotFoundException($path);
        }

        if ($processInclude) {
            preg_match_all('/<!-- include (.*) -->/', $code, $matchList);
            if (isset($matchList[1])) {
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
     * @param string $__file__  File to include
     * @param array  $data      Data passed to template
     *
     * @return string
     */
    private static function renderTemplate($__file__, array $data)
    {
        ob_start();
        extract($data);
        include $__file__;
        return ob_get_clean();
    }

    private $packageRoot;
    private $isRouteView;
}

