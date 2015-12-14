<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\File\MimeType;

use Dspbee\Bundle\Common\File\Exception\FileNotFoundException;
use Dspbee\Bundle\Common\File\Exception\AccessDeniedException;

/**
 * Class MimeTypeGuesser
 * @package Dspbee\Bundle\Common\File\MimeType
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
     * @param string $path The path to the file
     *
     * @return string The mime type or NULL, if none could be guessed
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

        if (0 == count($this->guessers)) {
            $msg = 'Unable to guess the mime type as no guessers are available';
            if (!FileInfoMimeType::isSupported()) {
                $msg .= ' (Did you enable the php_fileinfo extension?)';
            }
            throw new \LogicException($msg);
        }

        /** @var FileInfoMimeType|BinaryMimeType $guesser */
        foreach ($this->guessers as $guesser) {
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
        $this->guessers = [];

        if (FileinfoMimeType::isSupported()) {
            $this->guessers[] = new FileinfoMimeType();
        }
        if (BinaryMimeType::isSupported()) {
            $this->guessers[] = new BinaryMimeType();
        }
    }


    /**
     * @var MimeType
     */
    private static $instance = null;

    private $guessers;
}
