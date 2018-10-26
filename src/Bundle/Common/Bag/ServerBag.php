<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\Bag;

/**
 * Class ServerBag
 * @package Pivasic\Bundle\Common\Bag
 */
class ServerBag extends ValueBag
{
    public function __construct()
    {
        parent::__construct($_SERVER);
    }

    /**
     * Returns a SERVER parameter by name.
     *
     * @param string $key
     * @param mixed|null $default The default value if the parameter key does not exist
     * @return mixed|null
     */
    public function fetch(string $key, $default = null)
    {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        } else {
            $key = 'HTTP_' . $key;
            return $_SERVER[$key] ?? $default;
        }
    }
}