<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Bag;

/**
 * Class ServerBag
 * @package Dspbee\Bundle\Common\Bag
 */
class ServerBag extends ValueBag
{
    /**
     * @param array $bag
     */
    public function add(array $bag = [])
    {
        $_SERVER = array_replace($_SERVER, $bag);
    }

    /**
     * Returns true if the SERVER parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return array_key_exists($key, $_SERVER);
    }

    /**
     * Returns the SERVER keys.
     *
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return array_keys($_SERVER);
    }
    /**
     * Returns a SERVER parameter by name.
     *
     * @param string $key
     * @param mixed|null $default The default value if the parameter key does not exist
     *
     * @return mixed|null
     */
    public function fetch($key, $default = null)
    {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        } else {
            $key = 'HTTP_' . $key;
            return $_SERVER[$key] ?? $default;
        }
    }

    /**
     * Returns the number of values.
     *
     * @return int
     */
    public function count()
    {
        return count($_SERVER);
    }
}