<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\File;

use Dspbee\Bundle\Common\File\Exception\FileException;
use Dspbee\Bundle\Common\File\Exception\FileNotFoundException;
use Dspbee\Bundle\Common\File\Extension\Extension;
use Dspbee\Bundle\Common\File\MimeType\MimeType;

/**
 * Class File
 * @package Dspbee\Bundle\Common\File
 */
class File extends \SplFileInfo
{
    /**
     * @param string $path      The path to the file
     * @param bool   $checkPath Whether to check the path or not
     *
     * @throws FileNotFoundException If the given path is not a file
     */
    public function __construct($path, $checkPath = true)
    {
        if ($checkPath && !is_file($path)) {
            throw new FileNotFoundException($path);
        }

        parent::__construct($path);
    }

    /**
     * @param string $directory
     * @param string|null $name
     *
     * @return File
     *
     * @throws FileException
     */
    public function move($directory, $name = null)
    {
        $target = $this->getTargetFile($directory, $name);

        if (!@rename($this->getPathname(), $target)) {
            $error = error_get_last();
            throw new FileException(sprintf('Could not rename the file "%s" (%s)', $this->getPathname(), strip_tags($error['message'])));
        }

        if (false === @chmod($target, 0666 & ~umask())) {
            throw new FileException(sprintf('Unable to change mode of the "%s"', $target));
        }

        return $target;
    }

    /**
     * @return string|null
     */
    public function guessExtension()
    {
        return Extension::getInstance()->guess($this->guessMimeType());
    }

    /**
     * @return string|null
     */
    public function guessMimeType()
    {
        return MimeType::getInstance()->guess($this->getPathname());
    }

    /**
     * @param $directory
     * @param string|null $name
     *
     * @return File
     *
     * @throws FileException
     */
    protected function getTargetFile($directory, $name = null)
    {
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new FileException(sprintf('Unable to create the "%s" directory', $directory));
            }
        } elseif (!is_writable($directory)) {
            throw new FileException(sprintf('Unable to write in the "%s" directory', $directory));
        }

        $target = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . (null === $name ? $this->getBasename() : $this->getName($name));

        return new self($target, false);
    }

    /**
     * Returns locale independent base name of the given path.
     *
     * @param string $name The new file name
     *
     * @return string containing
     */
    protected function getName($name)
    {
        $originalName = str_replace('\\', '/', $name);
        $pos = strrpos($originalName, '/');
        $originalName = false === $pos ? $originalName : substr($originalName, $pos + 1);

        return $originalName;
    }
}