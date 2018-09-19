<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\File\Exception;

/**
 * Class FileNotFoundException
 * @package Pivasic\Bundle\Common\File\Exception
 */
class FileNotFoundException extends FileException
{
    /**
     * @param string $path Path to the file that was not found
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('File "%s" does not exist', $path));
    }
}
