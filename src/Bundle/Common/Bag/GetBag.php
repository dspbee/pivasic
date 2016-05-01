<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Bag;

/**
 * Class GetBag
 * @package Dspbee\Bundle\Common\Bag
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