<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\File\Exception;

/**
 * Class AccessDeniedException
 * @package Pivasic\Bundle\Common\File\Exception
 */
class AccessDeniedException extends FileException
{
    /**
     * @param string $path Path to the accessed file
     */
    public function __construct(string $path)
    {
        parent::__construct(sprintf('File %s could not be accessed', $path));
    }
}