<?php

namespace Middlewares;

use Models\{
    DAO\TokenDAO,
    DAO\UserDAO,
    Token,
    User
};

use lib\Connection;
use Resources\JsonResource;
use Sessions\Session;

class Authentication
{
    private static $connection;

    public static function authorization(): bool|JsonResource
    {
        self::$connection = Connection::getConnection();

        $headers = getallheaders();
        $jsonResource = new JsonResource();

        if (!array_key_exists('Authorization', $headers)) return $jsonResource->toJson(403, 'É necessário informar o token de autorização.');

        if (!preg_match('/Bearer\s(\S+)/', $headers['Authorization'], $matches)) return $jsonResource->toJson(401, 'O token de autorização está inválido.');

        $token = $matches[1];
        $result = self::validateToken($token);

        if (!$result) return $jsonResource->toJson(401, 'O token de autorização está inválido.');

        $userId = $result['user_id'];

        if (!self::authentication($userId)) return $jsonResource->toJson(404, 'Usuário inexistente.');

        return true;
    }

    private static function validateToken(string $token): array|bool
    {
        $TokenDAO = new TokenDAO(self::$connection);
        $Token = new Token();

        $Token->setToken($token);

        return $TokenDAO->getTokenByTokenAndExpirationDate($Token);
    }

    private static function authentication(int $userId): bool
    {
        $UserDAO = new UserDAO(self::$connection);
        $User = new User();

        $User->setId($userId);
        $result = $UserDAO->getUserById($User);

        if ($result !== false) {
            Session::put('user', $result);

            return true;
        }

        return false;
    }
}
