<?php

spl_autoload_register(function ($class): void {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = __DIR__ . '/../src/' . $path . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

