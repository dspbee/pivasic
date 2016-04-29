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
        parent::__construct([]);
        $this->bag = &$_ENV;
    }
}