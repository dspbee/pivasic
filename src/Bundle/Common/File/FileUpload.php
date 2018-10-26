<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\File;

use Pivasic\Bundle\Common\File\Exception\FileException;

/**
 * Class FileUpload
 * @package Pivasic\Bundle\Common\File
 */
class FileUpload extends File
{
    /**
     * @param string $path
     * @param string $name
     * @param int $size
     * @param string $mimeType
     * @param null $error
     */
    public function __construct(string $path, string $name, int $size = 0, string $mimeType = '', $error = null)
    {
        $this->name = $this->getName($name);
        $this->size = $size;
        $this->mimeType = $mimeType ?: 'application/octet-stream';
        $this->error = $error ?: UPLOAD_ERR_OK;

        parent::__construct($path, UPLOAD_ERR_OK == $this->error);
    }

    /**
     * Get file content.
     *
     * @return string|mixed
     */
    public function read()
    {
        $data = '';
        $fileObj = $this->openFile();
        while (!$fileObj->eof()) {
            $data .= $fileObj->fread(4096);
        }
        $fileObj = null;
        return $data;
    }

    /**
     * Returns the original file name.
     *
     * It is extracted from the request from which the file has been uploaded.
     * Then it should not be considered as a safe value.
     *
     * @return string The original name
     */
    public function nameUnsafe(): string
    {
        return $this->name;
    }

    /**
     * Returns the file size.
     *
     * It is extracted from the request from which the file has been uploaded.
     * Then it should not be considered as a safe value.
     *
     * @return int The file size
     */
    public function sizeUnsafe(): int
    {
        return $this->size;
    }

    /**
     * Returns the file mime type.
     *
     * The client mime type is extracted from the request from which the file
     * was uploaded, so it should not be considered as a safe value.
     *
     * @return string The mime type
     */
    public function mimeTypeUnsafe(): string
    {
        return $this->mimeType;
    }

    /**
     * Returns the upload error.
     *
     * If the upload was successful, the constant UPLOAD_ERR_OK is returned.
     * Otherwise one of the other UPLOAD_ERR_XXX constants is returned.
     *
     * @return int The upload error
     */
    public function error()
    {
        return $this->error;
    }

    /**
     * Returns an informative upload error message.
     *
     * @return string The error message regarding the specified error code
     */
    public function errorMessage(): string
    {
        static $errors = array(
            UPLOAD_ERR_INI_SIZE => 'The file "%s" exceeds your upload_max_filesize ini directive (limit is %d KiB).',
            UPLOAD_ERR_FORM_SIZE => 'The file "%s" exceeds the upload limit defined in your form.',
            UPLOAD_ERR_PARTIAL => 'The file "%s" was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_CANT_WRITE => 'The file "%s" could not be written on disk.',
            UPLOAD_ERR_NO_TMP_DIR => 'File could not be uploaded: missing temporary directory.',
            UPLOAD_ERR_EXTENSION => 'File upload was stopped by a PHP extension.',
        );

        $errorCode = $this->error;
        $maxFileSize = $errorCode === UPLOAD_ERR_INI_SIZE ? $this->getMaxFileSize() / 1024 : 0;
        $message = isset($errors[$errorCode]) ? $errors[$errorCode] : 'The file "%s" was not uploaded due to an unknown error.';

        return sprintf($message, $this->name, $maxFileSize);
    }

    /**
     * Returns the original file extension.
     *
     * It is extracted from the original file name that was uploaded.
     * Then it should not be considered as a safe value.
     *
     * @return string The extension
     */
    public function extensionUnsafe(): string
    {
        return pathinfo($this->name, PATHINFO_EXTENSION);
    }

    /**
     * Returns whether the file was uploaded successfully.
     *
     * @return bool True if the file has been uploaded with HTTP and no error occurred.
     */
    public function isValid(): bool
    {
        return $this->error === UPLOAD_ERR_OK && is_uploaded_file($this->getPathname());
    }

    /**
     * Returns the maximum size of an uploaded file as configured in php.ini.
     *
     * @return int The maximum size of an uploaded file in bytes
     */
    public function getMaxFileSize(): int
    {
        $iniMax = strtolower(ini_get('upload_max_filesize'));

        if ('' === $iniMax) {
            return PHP_INT_MAX;
        }

        $max = ltrim($iniMax, '+');
        if (0 === strpos($max, '0x')) {
            $max = intval($max, 16);
        } elseif (0 === strpos($max, '0')) {
            $max = intval($max, 8);
        } else {
            $max = (int) $max;
        }

        switch (substr($iniMax, -1)) {
            case 't':
                $max *= 1024;
            // no break
            case 'g':
                $max *= 1024;
            // no break
            case 'm':
                $max *= 1024;
            // no break
            case 'k':
                $max *= 1024;
            // no break
        }

        return $max;
    }

    /**
     * Moves the file to a new location.
     *
     * @param string $directory Destination folder
     * @param string $name      New file name
     * @return File A File object representing the new file
     * @throws FileException
     */
    public function move(string $directory, string $name = ''): File
    {
        if ($this->isValid()) {
            $target = $this->getTargetFile($directory, $name);

            if (!@move_uploaded_file($this->getPathname(), $target)) {
                $error = error_get_last();
                throw new FileException(sprintf('Could not move the file "%s" to "%s" (%s)', $this->getPathname(), $target, strip_tags($error['message'])));
            }

            $this->customChmod($target);

            return $target;
        }

        throw new FileException($this->errorMessage());
    }

    private $name;
    private $size;
    private $mimeType;
    private $error;
}