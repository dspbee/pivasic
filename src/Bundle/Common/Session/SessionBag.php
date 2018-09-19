<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\Session;

use Pivasic\Bundle\Common\Bag\ValueBag;

/**
 * Class SessionBag
 * @package Pivasic\Bundle\Common\Session
 */
class SessionBag extends ValueBag
{
    public function __construct()
    {
        parent::__construct([]);
    }

    /**
     * Get true if the SESSION parameter is defined.
     *
     * @param string $key
     *
     * @return bool true
     */
    public function has($key)
    {
        return isset($_SESSION) ? array_key_exists($key, $_SESSION) : false;
    }

    /**
     * Get the SESSION keys.
     *
     * @return array
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
     * @param mixed|null $default The default value if parameter does not exist
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