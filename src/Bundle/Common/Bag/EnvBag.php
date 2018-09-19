<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\Bag;

/**
 * Class EnvBag
 * @package Pivasic\Bundle\Common\Bag
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