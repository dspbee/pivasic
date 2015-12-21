<?php
/**
 * Autoload without composer.
 */
$srcRoot = dirname(__FILE__);
spl_autoload_register(function ($path) use ($srcRoot) {
    $srcRoot = rtrim($srcRoot, '/') . '/';
    $path = str_replace('Dspbee\\Test\\', '', $path);
    $path = str_replace('Dspbee\\', '', $path);
    $path = $srcRoot . $path . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});