<?php

namespace Middlewares;

use lib\Connection;
use Models\DAO\TokenDAO;
use Models\DAO\UserDAO;
use Models\Token;
use Models\User;
use Resources\JsonResource;
use Sessions\Session;

class Authentication
{
    private static $connection;
    private static $jsonResource;

    public static function authorization()
    {
        self::$connection = Connection::getConnection();

        $headers = getallheaders();
        $jsonResource = new JsonResource();

        if (array_key_exists('Authorization', $headers) && preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) {
            $token = $matches[1];

            if (!!$result = self::validateToken($token)) {
                $userId = $result['user_id'];

                if (self::authentication($userId)) return true;
                else
                    return $jsonResource->toJson(404, 'Usuário inexistente.');
            } else
                return $jsonResource->toJson(401, 'O token de autorização está inválido.');
        } else
            return $jsonResource->toJson(403, 'É necessário informar o token de autorização.');
    }

    private static function validateToken($token)
    {
        $TokenDAO = new TokenDAO(self::$connection);
        $Token = new Token();

        $Token->setToken($token);
        $result = $TokenDAO->getTokenByTokenAndExpirationDate($Token);

        return (!!$result) ? $result : false;
    }

    private static function authentication($userId)
    {
        $UserDAO = new UserDAO(self::$connection);
        $User = new User();

        $User->setId($userId);
        if (!!$result = $UserDAO->getUserById($User)) {
            Session::put('user', $result);

            return true;
        } else return false;
    }
}
