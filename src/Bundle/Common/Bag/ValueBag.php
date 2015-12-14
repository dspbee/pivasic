<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Bag;

/**
 * Class ValueBag
 * @package Dspbee\Bundle\Common\Bag
 */
class ValueBag
{
    /**
     * @param array $bag
     */
    public function __construct(array $bag = [])
    {
        $this->bag = $bag;
    }

    /**
     * @param array $bag
     */
    public function add(array $bag = [])
    {
        $this->bag = array_replace($this->bag, $bag);
    }

    /**
     * Returns true if the parameter is defined.
     *
     * @param string $key The key
     *
     * @return bool true if the parameter exists, false otherwise
     */
    public function has($key)
    {
        return array_key_exists($key, $this->bag);
    }

    /**
     * Returns the parameter keys.
     *
     * @return array An array of parameter keys
     */
    public function keys()
    {
        return array_keys($this->bag);
    }

    /**
     * Returns a parameter by name.
     *
     * @param string $key
     * @param mixed|null $default The default value if the parameter key does not exist
     *
     * @return mixed|null
     */
    public function fetch($key, $default = null)
    {
        return isset($this->bag[$key]) ? $this->bag[$key] : $default;
    }

    /**
     * Returns the bag value converted to integer.
     *
     * @param string $key
     * @param int $default The default value if the parameter key does not exist
     *
     * @return int
     */
    public function fetchInt($key, $default = 0)
    {
        return (int) $this->fetch($key, $default);
    }

    /**
     * Returns the bag value converted to float with precision.
     *
     * @param string $key
     * @param float $default The default value if the parameter key does not exist
     * @param int $precision The optional number of decimal digits to round to
     * @return float
     */
    public function fetchFloat($key, $default = 0.0, $precision = 2)
    {
        return round((float) $this->fetch($key, $default), $precision);
    }

    /**
     * Returns the bag value converted to boolean.
     *
     * @param string $key
     * @param bool|false $default The default value if the parameter key does not exist
     * @return mixed
     */
    public function fetchBool($key, $default = false)
    {
        return $this->fetchFilter($key, $default, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Filter value.
     *
     * @param string $key
     * @param mixed|null $default The default value if the parameter key does not exist
     * @param int $filter         FILTER_* constant
     * @param array $options      Filter options
     *
     * @see http://php.net/manual/en/function.filter-var.php
     *
     * @return mixed
     */
    public function fetchFilter($key, $default = null, $filter = FILTER_DEFAULT, $options = [])
    {
        $value = $this->fetch($key, $default);

        if (!is_array($options) && $options) {
            $options = ['flags' => $options];
        }

        if (is_array($value) && !isset($options['flags'])) {
            $options['flags'] = FILTER_REQUIRE_ARRAY;
        }

        return filter_var($value, $filter, $options);
    }

    /**
     * Use custom function to process value.
     *
     * @param string $key
     * @param callable $callback
     * @param mixed|null $default
     *
     * @return mixed
     */
    public function fetchCustom($key, $callback, $default = null)
    {
        return $callback($this->fetch($key, $default));
    }

    /**
     * Returns the number of values.
     *
     * @return int
     */
    public function count()
    {
        return count($this->bag);
    }

    protected $bag;
}