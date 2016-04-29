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
        parent::__construct([]);
        $this->bag = &$_COOKIE;
    }
}