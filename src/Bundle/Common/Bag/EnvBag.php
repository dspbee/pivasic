<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Bag;

/**
 * Class EnvBag
 * @package Dspbee\Bundle\Common\Bag
 */
class EnvBag extends ValueBag
{
    public function __construct()
    {
        $env = filter_input_array(INPUT_ENV);
        if (!is_array($env)) {
            $env = [];
        }
        parent::__construct($env);
    }
}