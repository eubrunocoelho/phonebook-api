<?php

function autoload($class)
{
    $dirBase = __DIR__ . DS;
    $class = $dirBase . 'app' . DS . str_replace('\\', DS, $class) . '.php';

    if (file_exists($class) && !is_dir($class)) include $class;
}

spl_autoload_register('autoload');
