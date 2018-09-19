<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\Bag;

/**
 * Class PostBag
 * @package Pivasic\Bundle\Common\Bag
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