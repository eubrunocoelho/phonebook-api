<?php

namespace lib;

use Exceptions\CustomException;
use PDO;
use PDOException;

class Connection
{
    private static $connection;

    public static function getConnection(): PDO
    {
        try {
            $dsn = DB_DRIVER . ':host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME;
            self::$connection = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
        } catch (PDOException $e) {
            throw new CustomException($e->getMessage(), 500, $e);
        }

        return self::$connection;
    }
}
