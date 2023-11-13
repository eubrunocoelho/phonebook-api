<?php

namespace Middlewares;

use lib\ConnectionFactory;
use Models\DAO\TokenDAO;
use Models\DAO\UserDAO;
use Models\Token;
use Models\User;
use Sessions\Session;

class Authentication
{
    public static function authorization()
    {
        $headers = getallheaders();

        if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            $token = $matches[1];

            if (!!$result = self::validateToken($token)) {
                $userId = $result['user_id'];

                if (self::authentication($userId)) {
                    dd('Autenticado');
                } else return false;
            } else return false;
        } else return false;
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
        $connection = ConnectionFactory::getConnection();
        $UserDAO = new UserDAO($connection);
        $User = new User();

        $User->setId($userId);
        if (!!$result = $UserDAO->getUserById($User)) {
            Session::put('user', $result);

            return true;
        } else return false;
    }
}
