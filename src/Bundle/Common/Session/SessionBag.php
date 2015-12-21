<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Session;

use Dspbee\Bundle\Common\Bag\ValueBag;

/**
 * Class SessionBag
 * @package Dspbee\Bundle\Common\Session
 */
class SessionBag extends ValueBag
{
    public function __construct()
    {
        parent::__construct([]);
    }

    /**
     * @param array $bag
     *
     * @throws \LogicException
     */
    public function add(array $bag = [])
    {
        if (isset($_SESSION)) {
            $_SESSION = array_replace($_SESSION, $bag);
        } else {
            throw new \LogicException('Cannot set the value of an inactive session');
        }
    }

    /**
     * Returns true if the SESSION parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return isset($_SESSION) ? array_key_exists($key, $_SESSION) : false;
    }

    /**
     * Returns the SESSION keys.
     *
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return isset($_SESSION) ? array_keys($_SESSION) : [];
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @throws \LogicException
     */
    public function set($key, $value)
    {
        if (isset($_SESSION)) {
            $_SESSION[$key] = $value;
        } else {
            throw new \LogicException('Cannot set the value of an inactive session');
        }
    }

    /**
     * Returns a SESSION parameter by name.
     *
     * @param string $key
     * @param mixed|null $default The default value if the parameter key does not exist
     *
     * @return mixed|null
     */
    public function fetch($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Returns the number of values.
     *
     * @return int
     */
    public function count()
    {
        return isset($_SESSION) ? count($_SESSION) : 0;
    }
}