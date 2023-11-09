<?php

namespace lib;

use Exceptions\Exceptions;
use PDO;
use PDOException;

class ConnectionFactory
{
    private static $connection;

    public static function getConnection(): PDO
    {
        if (self::$connection === null)
            try {
                $dsn = DB_DRIVER . ':host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
                self::$connection = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
            } catch (PDOException $e) {
                throw new Exceptions($e->getMessage(), 500, $e);
            }

        return self::$connection;
    }
}
