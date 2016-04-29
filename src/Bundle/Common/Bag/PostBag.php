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
        parent::__construct([]);
        $this->bag = &$_POST;
    }
}