<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Alert;

/**
 * Save message to the cookie.
 *
 * Class Alert
 * @package Dspbee\Bundle\Alert
 */
abstract class Alert
{
    /**
     * @param string $message
     *
     * @return bool
     */
    public function alert($message)
    {
        if (!headers_sent()) {
            setcookie('__alert__', $message . '|' . $this->color, time() + 3600 * 24 * 30, '/');
            return true;
        }
        return false;
    }

    protected $color;
}