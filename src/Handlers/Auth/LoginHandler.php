<?php

namespace Handlers\Auth;

use Handlers\Handler;
use Models\DAO\TokenDAO;
use Models\DAO\UserDAO;
use Models\Token;
use Models\User;

class LoginHandler extends Handler
{
    public function handle($data, $controller)
    {
        $TokenDAO = new TokenDAO($controller->connection);
        $Token = new Token();
        $UserDAO = new UserDAO($controller->connection);
        $User = new User();

        $User->setUser($data['request']['username']);
        $resultUser = $UserDAO->getUserByUsernameOrEmail($User);

        if ($resultUser !== false && password_verify($data['request']['password'], $resultUser['password'])) {
            $Token->setUserId($resultUser['id']);
            $resultToken = $TokenDAO->getTokenByUserIdAndExpirationDate($Token);

            unset($data);

            $data['user'] = [
                'id' => $resultUser['id'],
                'username' => $resultUser['username'],
                'email' => $resultUser['email']
            ];

            $data['token'] = [
                'user_id' => $resultUser['id'],
                'token' => bin2hex(random_bytes(16)),
                'expiration_date' => date('Y-m-d H:i:s', strtotime('+3 days'))
            ];
            
            $data['result_token'] = $resultToken;

            foreach ($this->successors as $successor) {
                $data = $successor->handle($data, $controller);
            }
        } else return $controller->jsonResource->toJson(401, 'Usuário ou senha inválidos.');

        return $data;
    }
}
