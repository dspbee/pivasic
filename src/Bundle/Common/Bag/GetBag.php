<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\Bag;

/**
 * Class GetBag
 * @package Pivasic\Bundle\Common\Bag
 */
class GetBag extends ValueBag
{
    public function __construct()
    {
        $get = filter_input_array(INPUT_GET);
        if (!is_array($get)) {
            $get = [];
        }
        parent::__construct($get);
    }
}