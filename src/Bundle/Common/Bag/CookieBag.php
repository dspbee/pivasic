<?php
/**
 * @license MIT
 */
namespace Pivasic\Bundle\Common\Bag;

/**
 * Class CookieBag
 * @package Pivasic\Bundle\Common\Bag
 */
class CookieBag extends ValueBag
{
    public function __construct()
    {
        $cookie = filter_input_array(INPUT_COOKIE);
        if (!is_array($cookie)) {
            $cookie = [];
        }
        parent::__construct($cookie);
    }
}