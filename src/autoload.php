<?php
/**
 * Autoload without composer.
 */
$srcRoot = dirname(__FILE__);
spl_autoload_register(function ($path) use ($srcRoot) {
    $root = rtrim($srcRoot, '/') . '/';
    $path = str_replace('Pivasic\\Test\\', '', $path);
    $path = str_replace('Pivasic\\', '', $path);
    $path = $root . $path . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});