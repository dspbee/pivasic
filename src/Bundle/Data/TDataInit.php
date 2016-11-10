<?php
/**
 * @license MIT
 * @author Igor Sorokin <dspbee@pivasic.com>
 */
namespace Dspbee\Bundle\Data;

/**
 * Initialize class properties from array.
 *
 * Class TDataFilter
 * @package Dspbee\Core
 */
trait TDataInit
{
    /**
     * Init class members.
     *
     * @param array $data
     */
    public function initFromArray(array $data)
    {
        foreach ($data as $name => $value) {
            $method = 'set' . ucfirst($name);
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], [$value]);
            } else {
                if (property_exists(get_called_class(), $name)) {
                    $this->$name = $value;
                }
            }
        }
    }

    /**
     * Init class members from $_POST array.
     *
     * @param null $callback
     */
    public function initFromPost($callback = null)
    {
        $input = $_POST;
        if (null !== $callback) {
            $input = array_map($callback, $input);
        }
        $this->initFromArray($input);
    }
}