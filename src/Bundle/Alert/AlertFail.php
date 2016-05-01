<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Alert;

/**
 * Class AlertFail
 * @package Dspbee\Bundle\Alert
 */
abstract class AlertFail extends Alert
{
    public function __construct()
    {
        $this->color = 'red';
    }
}