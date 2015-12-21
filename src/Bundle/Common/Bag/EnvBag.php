<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Bag;

/**
 * Class EnvBag
 * @package Dspbee\Bundle\Common\Bag
 */
class EnvBag extends ValueBag
{
    public function __construct()
    {
        parent::__construct([]);
    }

    /**
     * @param array $bag
     */
    public function add(array $bag = [])
    {
        $_ENV = array_replace($_ENV, $bag);
    }

    /**
     * Returns true if the ENV parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return array_key_exists($key, $_ENV);
    }

    /**
     * Returns the ENV keys.
     *
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return array_keys($_ENV);
    }

    /**
     * Returns a ENV parameter by name.
     *
     * @param string $key
     * @param mixed|null $default The default value if the parameter key does not exist
     *
     * @return mixed|null
     */
    public function fetch($key, $default = null)
    {
        return $_ENV[$key] ?? $default;
    }

    /**
     * Returns the number of values.
     *
     * @return int
     */
    public function count()
    {
        return count($_ENV);
    }
}