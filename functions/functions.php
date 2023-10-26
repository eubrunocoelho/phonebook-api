<?php

if (!function_exists('dd')) {
    function dd($params = [], $die = false)
    {
        echo '<pre>';
        var_dump($params);
        echo '</pre>';

        if ($die) die();
    }
}