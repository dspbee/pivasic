<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Alert;

/**
 * Class AlertSuccess
 * @package Dspbee\Bundle\Alert
 */
abstract class AlertSuccess extends Alert
{
    public function __construct()
    {
        parent::__construct();
        $this->color = 'green';
    }
}