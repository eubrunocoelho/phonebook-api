<?php

namespace Handlers\Auth;

use Models\{
    DAO\TokenDAO,
    DAO\UserDAO,
    Token,
    User
};

use Handlers\Handler;

class AuthenticateHandler extends Handler
{
    public function handle(array $data, Object $controller): array|Object
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

            foreach ($this->successors as $successor) $data = $successor->handle($data, $controller);

            return $data;
        } else return $controller->jsonResource->toJson(401, 'Usuário ou senha inválidos.');
    }
}
