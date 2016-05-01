<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Bag;

/**
 * Class PostBag
 * @package Dspbee\Bundle\Common\Bag
 */
class PostBag extends ValueBag
{
    public function __construct()
    {
        $post = filter_input_array(INPUT_POST);
        if (!is_array($post)) {
            $post = [];
        }
        parent::__construct($post);
    }
}