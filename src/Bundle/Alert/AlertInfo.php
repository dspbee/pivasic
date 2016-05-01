<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Alert;

/**
 * Class AlertInfo
 * @package Dspbee\Bundle\Alert
 */
abstract class AlertInfo extends Alert
{
    public function __construct()
    {
        $this->color = 'green';
    }
}