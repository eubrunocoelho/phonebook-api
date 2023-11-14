<?php

namespace Sessions;

class Session
{
    public static function put($name, $value): array
    {
        return $_SESSION[$name] = $value;
    }

    public static function get($name): array
    {
        return $_SESSION[$name];
    }
}
