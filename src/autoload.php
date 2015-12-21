<?php
/**
 * Autoload without composer.
 */
$srcRoot = dirname(__FILE__);
spl_autoload_register(function ($path) use ($srcRoot) {
    $srcRoot = rtrim($srcRoot, '/') . '/';
    /**
     * Delete vendor from path.
     */
    $path = explode('\\', $path);
    array_shift($path);
    $path = $srcRoot . implode('/', $path) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});