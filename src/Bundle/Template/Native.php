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
     * @param string $language
     * @param bool $cache
     */
    public function __construct(string $packageRoot, string $language = '', bool $cache = true)
    {
        $this->packageRoot = rtrim($packageRoot, '/');
        $this->language = $language;
        $this->cache = $cache;
        $this->isRouteView = false;
        $this->title = '';
    }

    /**
     * Create content from template and data.
     *
     * @param string $name
     * @param array $data
     * @return string
     * @throws FileNotFoundException
     * @throws \RuntimeException
     */
    public function getContent(string $name, array $data = []): string
    {
        $cacheName = $name;
        if ('' == $name) {
            $this->isRouteView = true;

            $stack = debug_backtrace();
            foreach ($stack as $item) {
                if (false !== stripos($item['file'], DIRECTORY_SEPARATOR . 'Route' . DIRECTORY_SEPARATOR)) {
                    $cacheName = pathinfo($item['file'], PATHINFO_DIRNAME) . '/' . $name;
                    $cacheName = explode('Route' . DIRECTORY_SEPARATOR, $cacheName)[1];
                    $cacheName = 'route_' . str_replace(['/', '\\'], '_', $cacheName);
                    break;
                }
            }
        }
        $cacheName .= '_' . $this->language . '.html.php';
        $path = $this->packageRoot . '/view/_cache/' . str_replace('/', '_', $cacheName);

        $exist = file_exists($path);
        if (!$this->cache || !$exist) {
            $code = $this->compile($name . '/view.html.php', true, true, true);

            $code = preg_replace(['/\>[^\S ]+/s', '/[^\S ]+\</s', '/(\s)+/s'], ['>', '<', '\\1'], $code);

            if ($exist) {
                $fh = fopen($path, 'r+b');
            } else {
                $fh = fopen($path, 'wb');
            }
            if (flock($fh, LOCK_EX)) {
                ftruncate($fh, 0);
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

        throw new \RuntimeException('Can\'t render template');
    }

    /**
     * Create solid template.
     *
     * @param string $name
     * @param bool $processLang
     * @param bool $processInclude
     * @param bool $processExtends
     * @return string
     * @throws FileNotFoundException
     */
    private function compile(string $name, bool $processLang, bool $processInclude, bool $processExtends): string
    {
        if ($this->isRouteView) {
            $this->isRouteView = false;
            $path = '';
            $stack = debug_backtrace();
            foreach ($stack as $item) {
                if (false !== stripos($item['file'], DIRECTORY_SEPARATOR . 'Route' . DIRECTORY_SEPARATOR)) {
                    $path = pathinfo($item['file'], PATHINFO_DIRNAME) . '/view.html.php';
                    if ($processLang) {
                        $storagePath = str_replace('view.html.php', '_lang/' . $this->language . '.php', $path);
                    }
                    break;
                }
            }
        } else {
            $path = $this->packageRoot . '/view/' . $name;
            if ($processLang) {
                $storagePath = str_replace('view.html.php', '', $path) . '_lang/' . $this->language . '.php';
            }
        }

        if (file_exists($path)) {
            ob_start();
            readfile($path);
            $code = ob_get_clean();
        } else {
            throw new FileNotFoundException($path);
        }

        if ($processLang && file_exists($storagePath)) {
            $storage = include $storagePath;
            if ('' == $this->title) {
                $this->title = $storage['title'] ?? '';
            }
            preg_match_all('/<!-- lang (.*) -->/', $code, $matchList);
            if (isset($matchList[1])) {
                foreach ($matchList[1] as $key => $index) {
                    $name = explode('>', $index);
                    $default = trim($name[1] ?? '');
                    $name = trim($name[0]);
                    if (!empty($matchList[0][$key]) && false !== strpos($code, $matchList[0][$key])) {
                        $code = str_replace($matchList[0][$key], $storage[$name] ?? $default, $code);
                    }
                }
            }
        } else {
            preg_match_all('/<!-- lang (.*) -->/', $code, $matchList);
            if (isset($matchList[1])) {
                foreach ($matchList[1] as $key => $index) {
                    $name = explode('>', $index);
                    $default = trim($name[1] ?? '');
                    $name = trim($name[0]);
                    if (!empty($matchList[0][$key]) && false !== strpos($code, $matchList[0][$key])) {
                        $code = str_replace($matchList[0][$key], $this->$name ?? $default, $code);
                    }
                }
            }
        }

        if ($processInclude) {
            preg_match_all('/<!-- include (.*) -->/', $code, $matchList);
            if (isset($matchList[1])) {
                foreach ($matchList[1] as $key => $template) {
                    if (!empty($matchList[0][$key]) && false !== strpos($code, $matchList[0][$key])) {
                        $template = trim($template);
                        $code = str_replace($matchList[0][$key], $this->compile($template . '/view.html.php', true, true, false), $code);
                    }
                }
            }
        }

        if ($processExtends) {
            preg_match_all('/<!-- extends (.*) -->/', $code, $matchList);
            if (isset($matchList[1][0])) {
                $template = trim($matchList[1][0]);
                $parentHtml = $this->compile($template . '/view.html.php', true, true, false);

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
     * @return string
     */
    private static function renderTemplate(string $__file__, array $data): string
    {
        ob_start();
        extract($data);
        include $__file__;
        return ob_get_clean();
    }

    private $packageRoot;
    private $language;
    private $cache;
    private $isRouteView;

    private $title;
}