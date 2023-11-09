<?php

namespace lib;

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
                http_response_code($e->getCode());
                
                echo 'Erro na conexão com o banco de dados: ' . $e->getMessage();
                die();
            }

        return self::$connection;
    }
}
