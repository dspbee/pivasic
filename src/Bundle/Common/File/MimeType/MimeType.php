<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\File\MimeType;

use Pivasic\Bundle\Common\File\Exception\FileNotFoundException;
use Pivasic\Bundle\Common\File\Exception\AccessDeniedException;

/**
 * Class MimeTypeGuesser
 * @package Pivasic\Bundle\Common\File\MimeType
 */
class MimeType
{
    /**
     * Returns the singleton instance.
     *
     * @return MimeType
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }


    /**
     * Get mime type or NULL, if none could be guessed.
     *
     * @param string $path Path to the file
     *
     * @return string
     *
     * @throws \LogicException
     * @throws FileNotFoundException
     * @throws AccessDeniedException
     */
    public function guess($path)
    {
        if (!is_file($path)) {
            throw new FileNotFoundException($path);
        }

        if (!is_readable($path)) {
            throw new AccessDeniedException($path);
        }

        if (0 == count($this->guesserList)) {
            $msg = 'Unable to guess the mime type as no guessers are available';
            if (!FileInfoMimeType::isSupported()) {
                $msg .= ' (Did you enable the php_fileinfo extension?)';
            }
            throw new \LogicException($msg);
        }

        /** @var FileInfoMimeType|BinaryMimeType $guesser */
        foreach ($this->guesserList as $guesser) {
            if (null !== $mimeType = $guesser->guess($path)) {
                return $mimeType;
            }
        }

        return null;
    }


    /**
     * Registers all natively provided mime type guessers.
     */
    private function __construct()
    {
        $this->guesserList = [];

        if (FileInfoMimeType::isSupported()) {
            $this->guesserList[] = new FileInfoMimeType();
        }
        if (BinaryMimeType::isSupported()) {
            $this->guesserList[] = new BinaryMimeType();
        }
    }


    /**
     * @var MimeType
     */
    private static $instance = null;

    private $guesserList;
}
