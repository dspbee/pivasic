<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Template;

use Dspbee\Bundle\Common\TFileSystem;
use Dspbee\Bundle\Debug\Wrap;
use Dspbee\Bundle\Template\Exception\FileNotFoundException;
use Dspbee\Core\Request;

/**
 * Class Native
 * @package Dspbee\Bundle\Template
 */
class Native
{
    use TFileSystem;

    /**
     * @param string $packageRoot
     * @param Request|null $request
     * @param bool $cache
     */
    public function __construct($packageRoot, Request $request = null, $cache = true)
    {
        $this->packageRoot = rtrim($packageRoot, '/');
        $this->request = $request;

        if (class_exists('Dspbee\Bundle\Debug\Wrap')) {
            $this->cache = !Wrap::$debugEnabled;
        } else {
            $this->cache = $cache;
        }
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
        $path = $this->packageRoot . '/view/_cache/' . str_replace('/', '_', $name);

        if (!file_exists($path) || !$this->cache) {
            $code = $this->compile($name, true, true);
            if (empty($code)) {
                return null;
            }

            $fh = fopen($path, 'wb');
            if (flock($fh, LOCK_EX)) {
                fwrite($fh, $code);
                flock($fh, LOCK_UN);
            }
            fflush($fh);
            fclose($fh);
        }

        $fh = fopen($path, 'rb');
        flock($fh, LOCK_SH);

        if (null !== $this->request) {
            $data = array_replace($data, ['request' => $this->request]);
        }

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
        self::removeFromDir($this->packageRoot . '/view/_cache');
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
        $path = $this->packageRoot . '/view/' . $name;

        if (file_exists($path)) {
            ob_start();
            readfile($path);
            $code = ob_get_clean();
        } else {
            throw new FileNotFoundException($path);
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

    private $request;
    private $packageRoot;
    private $cache;
}

