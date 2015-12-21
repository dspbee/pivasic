<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Bag;

/**
 * Class PostBag
 * @package Dspbee\Bundle\Common\Bag
 */
class PostBag extends ValueBag
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
        $_POST = array_replace($_POST, $bag);
    }

    /**
     * Returns true if the POST parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return array_key_exists($key, $_POST);
    }

    /**
     * Returns the POST keys.
     *
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return array_keys($_POST);
    }

    /**
     * Returns a POST parameter by name.
     *
     * @param string $key
     * @param mixed|null $default The default value if the parameter key does not exist
     *
     * @return mixed|null
     */
    public function fetch($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Returns the number of values.
     *
     * @return int
     */
    public function count()
    {
        return count($_POST);
    }
}