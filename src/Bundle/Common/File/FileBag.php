<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\File;

/**
 * Class FileBag
 * @package Pivasic\Bundle\Common\File
 */
class FileBag
{
    /**
     * Initialize bag from $_FILES.
     *
     * @throws \InvalidArgumentException
     */
    public function __construct()
    {
        $this->bag = [];

        foreach ($_FILES as $key => $file) {
            if (!is_array($file) && !$file instanceof FileUpload) {
                throw new \InvalidArgumentException('An uploaded file must be an array or an instance of FileUpload.');
            }
            $this->bag[$key] = $this->convertFileInformation($file);
        }
    }

    /**
     * Returns true if the FILE parameter is defined.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return array_key_exists($key, $this->bag);
    }

    /**
     * An array of parameter names.
     *
     * @return array
     */
    public function keys(): array
    {
        return array_keys($this->bag);
    }

    /**
     * Get value by parameter name.
     *
     * @param string $key
     * @param mixed|null $default The default value if parameter does not exist
     * @return FileUpload|array|null
     */
    public function fetch(string $key, $default = null)
    {
        return $this->bag[$key] ?? $default;
    }

    /**
     * Converts uploaded files to FileUpload instances.
     *
     * @param array|FileUpload $file A (multi-dimensional) array of uploaded file information
     * @return array A (multi-dimensional) array of FileUpload instances
     */
    private function convertFileInformation($file)
    {
        if ($file instanceof FileUpload) {
            return $file;
        }

        $file = $this->fixPhpFilesArray($file);
        if (is_array($file)) {
            $keys = array_keys($file);
            sort($keys);

            if ($keys == ['error', 'name', 'size', 'tmp_name', 'type']) {
                if (UPLOAD_ERR_NO_FILE == $file['error']) {
                    $file = null;
                } else {
                    $file = new FileUpload($file['tmp_name'], $file['name'], $file['size'], $file['type'], $file['error']);
                }
            } else {
                $file = array_map([$this, 'convertFileInformation'], $file);
            }
        }

        return $file;
    }

    /**
     * Fixes a malformed PHP $_FILES array.
     *
     * PHP has a bug that the format of the $_FILES array differs, depending on
     * whether the uploaded file fields had normal field names or array-like
     * field names ("normal" vs. "parent[child]").
     *
     * This method fixes the array to look like the "normal" $_FILES array.
     *
     * It's safe to pass an already converted array, in which case this method
     * just returns the original array unmodified.
     *
     * @param array $data
     * @return array
     */
    private function fixPhpFilesArray(array $data): array
    {
        if (!is_array($data)) {
            return $data;
        }

        $keys = array_keys($data);
        sort($keys);

        if (['error', 'name', 'size', 'tmp_name', 'type'] != $keys || !isset($data['name']) || !is_array($data['name'])) {
            return $data;
        }

        $files = $data;
        foreach (['error', 'name', 'size', 'tmp_name', 'type'] as $k) {
            unset($files[$k]);
        }

        foreach ($data['name'] as $key => $name) {
            $files[$key] = $this->fixPhpFilesArray(array(
                'error' => $data['error'][$key],
                'name' => $name,
                'type' => $data['type'][$key],
                'tmp_name' => $data['tmp_name'][$key],
                'size' => $data['size'][$key],
            ));
        }

        return $files;
    }

    private $bag;
}