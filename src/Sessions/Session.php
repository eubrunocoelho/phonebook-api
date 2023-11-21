<?php

namespace Sessions;

class Session
{
    public static function put(string $name, mixed $value): array
    {
        return $_SESSION[$name] = $value;
    }

    public static function get(string $name): array
    {
        return $_SESSION[$name];
    }
}
