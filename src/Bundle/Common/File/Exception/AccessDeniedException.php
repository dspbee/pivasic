<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\File\Exception;

/**
 * Class AccessDeniedException
 * @package Dspbee\Bundle\Common\File\Exception
 */
class AccessDeniedException extends FileException
{
    /**
     * @param string $path The path to the accessed file
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('The file %s could not be accessed', $path));
    }
}