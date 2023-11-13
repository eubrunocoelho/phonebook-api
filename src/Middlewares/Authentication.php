<?php

namespace Middlewares;

use lib\ConnectionFactory;
use Models\DAO\TokenDAO;
use Models\Token;

class Authentication
{
    public static function authorization()
    {
        $headers = getallheaders();

        if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            $token = $matches[1];

            if (!!$result = self::validateToken($token)) {
                $userId = $result['user_id'];

                self::authentication($userId);
            }
        }
    }

    private static function validateToken($token)
    {
        $connection = ConnectionFactory::getConnection();
        $TokenDAO = new TokenDAO($connection);
        $Token = new Token();

        $Token->setToken($token);
        $result = $TokenDAO->getTokenByTokenAndExpirationDate($Token);

        return (!!$result) ? $result : false;
    }

    private static function authentication($userId)
    {
        dd($userId, true);
    }
}
