<?php

function autoload($class)
{
    $dirBase = DIR_APP;
    $class = $dirBase . 'src' . DS . str_replace('\\', DS, $class) . '.php';

    if (file_exists($class) && !is_dir($class)) include $class;
}

spl_autoload_register('autoload');
