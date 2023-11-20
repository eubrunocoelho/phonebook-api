<?php

namespace Handlers\User;

use Models\{
    DAO\UserDAO,
    User
};

use Handlers\Handler;

class StoreHandler extends Handler
{
    public function handle(array $data, Object $controller): array|Object
    {
        $UserDAO = new UserDAO($controller->connection);
        $User = new User();

        $write = [
            'username' => $data['request']['username'],
            'email' => $data['request']['email'],
            'password' => password_hash($data['request']['password'], PASSWORD_DEFAULT)
        ];

        $User->setUsername($write['username']);
        $User->setEmail($write['email']);
        $User->setPassword($write['password']);
        $resultId = $UserDAO->register($User);

        if ($resultId !== false) {
            unset($data);

            $User->setId($resultId);
            $data = $UserDAO->getUserById($User);
            
            unset($data['password']);

            return $controller->jsonResource->toJson(201, 'Usuário cadastrado com sucesso!', ['data' => $data]);
        }
    }
}
