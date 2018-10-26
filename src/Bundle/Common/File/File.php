<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\File;

use Pivasic\Bundle\Common\File\Exception\FileException;
use Pivasic\Bundle\Common\File\Exception\FileNotFoundException;
use Pivasic\Bundle\Common\File\Extension\Extension;
use Pivasic\Bundle\Common\File\MimeType\MimeType;

/**
 * Class File
 * @package Pivasic\Bundle\Common\File
 */
class File extends \SplFileInfo
{
    /**
     * @param string $path      The path to the file
     * @param bool   $checkPath Whether to check the path or not
     * @throws FileNotFoundException
     */
    public function __construct(string $path, bool $checkPath = true)
    {
        if ($checkPath && !is_file($path)) {
            throw new FileNotFoundException($path);
        }

        parent::__construct($path);
    }

    /**
     * @return string
     */
    public function guessExtension(): string
    {
        return Extension::getInstance()->guess($this->guessMimeType());
    }

    /**
     * @return string
     */
    public function guessMimeType(): string
    {
        return MimeType::getInstance()->guess($this->getPathname());
    }

    /**
     * Moves the file to a new location.
     *
     * @param string $directory   Destination folder
     * @param string $name   New file name
     * @return File
     * @throws FileException
     */
    public function move(string $directory, string $name = ''): File
    {
        $target = $this->getTargetFile($directory, $name);

        if (!@rename($this->getPathname(), $target)) {
            $error = error_get_last();
            throw new FileException(sprintf('Could not rename the file "%s" (%s)', $this->getPathname(), strip_tags($error['message'])));
        }

        $this->customChmod($target);

        return $target;
    }

    /**
     * @param string $directory
     * @param string $name
     * @return File
     * @throws FileException
     */
    protected function getTargetFile(string $directory, string $name = ''): File
    {
        if (!is_dir($directory)) {
            if (false === @mkdir($directory, 0777, true) && !is_dir($directory)) {
                throw new FileException(sprintf('Unable to create the "%s" directory', $directory));
            }
        } elseif (!is_writable($directory)) {
            throw new FileException(sprintf('Unable to write in the "%s" directory', $directory));
        }

        $target = rtrim($directory, '/\\') . DIRECTORY_SEPARATOR . ('' == $name ? $this->getBasename() : $this->getName($name));

        return new self($target, false);
    }

    /**
     * Chmod function with exception
     *
     * @param $target
     * @param $mode
     * @throws FileException
     */
    protected function customChmod(string $target, $mode = 0666)
    {
        if (false === @chmod($target, $mode & ~umask())) {
            throw new FileException(sprintf('Unable to change mode of the "%s"', $target));
        }
    }

    /**
     * Returns locale independent base name of the given path.
     *
     * @param string $name The new file name
     * @return string containing
     */
    protected function getName(string $name): string
    {
        $originalName = str_replace('\\', '/', $name);
        $pos = strrpos($originalName, '/');
        $originalName = false === $pos ? $originalName : substr($originalName, $pos + 1);

        return $originalName;
    }
}