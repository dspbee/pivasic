<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Core;

/**
 * Static key => value storage.
 *
 * Class Application
 * @package Dspbee\Core
 */
class Storage
{
    /**
     * Save data in storage.
     *
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value)
    {
        self::$storage[$key] = $value;
    }

    /**
     * Get data by key.
     *
     * @param string $key
     * @return mixed|null
     */
    public static function get($key)
    {
        return self::$storage[$key] ?? null;
    }

    private static $storage;
}