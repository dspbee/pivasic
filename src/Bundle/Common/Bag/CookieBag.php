<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Common\Bag;

/**
 * Class CookieBag
 * @package Dspbee\Bundle\Common\Bag
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