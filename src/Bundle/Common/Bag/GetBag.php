<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Bag;

/**
 * Class GetBag
 * @package Dspbee\Bundle\Common\Bag
 */
class GetBag extends ValueBag
{
    /**
     * @param array $bag
     */
    public function add(array $bag = [])
    {
        $_GET = array_replace($_GET, $bag);
    }

    /**
     * Returns true if the GET parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return array_key_exists($key, $_GET);
    }

    /**
     * Returns the GET keys.
     *
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return array_keys($_GET);
    }


    /**
     * Returns a GET parameter by name.
     *
     * @param string $key
     * @param mixed|null $default The default value if the parameter key does not exist
     *
     * @return mixed|null
     */
    public function fetch($key, $default = null)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }

    /**
     * Returns the number of values.
     *
     * @return int
     */
    public function count()
    {
        return count($_GET);
    }
}