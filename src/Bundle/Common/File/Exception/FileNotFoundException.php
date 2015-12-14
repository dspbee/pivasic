<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\File\Exception;

/**
 * Class FileNotFoundException
 * @package Dspbee\Bundle\Common\File\Exception
 */
class FileNotFoundException extends FileException
{
    /**
     * @param string $path The path to the file that was not found
     */
    public function __construct($path)
    {
        parent::__construct(sprintf('The file "%s" does not exist', $path));
    }
}
