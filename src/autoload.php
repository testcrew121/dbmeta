<?php

// register autoloader
spl_autoload_register(function ($class) {
    $file = str_replace('\\', '/', __DIR__ . '/' . $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
        return true;
    }
    return false;
});
