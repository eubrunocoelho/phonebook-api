<?php

namespace Middlewares;

class Cors
{
    public static function handleCorsHeaders(string $method): void
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: access');
        header('Access-Control-Allow-Methods: ' . $method);
        header('Content-Type: application/json; charset=UTF-8');
        header('Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With');
    }
}
