<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\Bag;

/**
 * Class ValueBag
 * @package Pivasic\Bundle\Common\Bag
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
     * Returns true if parameter is defined.
     *
     * @param string $key
     *
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->bag);
    }

    /**
     * An array of parameter names.
     *
     * @return array
     */
    public function keys()
    {
        return array_keys($this->bag);
    }

    /**
     * Get value by parameter name.
     *
     * @param string $key
     * @param mixed|null $default The default value if parameter does not exist
     *
     * @return mixed|null
     */
    public function fetch($key, $default = null)
    {
        $val = $this->bag[$key] ?? $default;
        if (!is_array($val)) {
            return trim($val);
        } else {
            return $val;
        }
    }

    /**
     * Get parameter value converted to integer.
     *
     * @param string $key
     * @param int $default The default value if parameter does not exist
     *
     * @return int
     */
    public function fetchInt($key, $default = 0)
    {
        return intval($this->fetch($key, $default));
    }

    /**
     * Get parameter value converted to float with precision.
     *
     * @param string $key
     * @param float $default The default value if parameter does not exist
     * @param int $precision The optional number of decimal digits to round to
     * @return float
     */
    public function fetchFloat($key, $default = 0.0, $precision = 2)
    {
        return round(floatval($this->fetch($key, $default)), $precision);
    }

    /**
     * Get parameter value converted to boolean.
     *
     * @param string $key
     * @param bool|false $default The default value if parameter does not exist
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
     * @param mixed|null $default The default value if parameter does not exist
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
     * Use mysql escape function.
     *
     * @param $key
     * @param \mysqli $db
     * @param string $default
     *
     * @return string
     */
    public function fetchEscape($key, \mysqli $db, $default = '')
    {
        return $db->real_escape_string($this->fetch($key, $default));
    }

    /**
     * Returns the count of parameters.
     *
     * @return int
     */
    public function count()
    {
        return count($this->bag);
    }

    private $bag;
}