<?php

if (!function_exists('dd')) {
    function dd(array|string $params = [], bool $die = false): void
    {
        echo '<pre>';
        var_dump($params);
        echo '</pre>';

        if ($die) die();
    }
}
