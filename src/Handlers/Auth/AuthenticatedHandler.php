<?php

namespace Handlers\Auth;

use Models\{
    DAO\TokenDAO,
    DAO\UserDAO,
    Token,
    User
};

use Handlers\Handler;

class AuthenticatedHandler extends Handler
{
    public function handle($data, $controller)
    {
        $TokenDAO = new TokenDAO($controller->connection);
        $Token = new Token();
        $UserDAO = new UserDAO($controller->connection);
        $User = new User();

        $User->setId($data['user']['id']);
        $Token->setId($data['result_token']['id']);

        unset($data);

        $data['user'] = $UserDAO->getUserById($User);
        $data['user']['token'] = $TokenDAO->getTokenById($Token);

        unset($data['user']['password']);
        unset($data['user']['token']['id']);
        unset($data['user']['token']['user_id']);

        return $controller->jsonResource->toJson(200, 'Usuário autenticado com sucesso!', ['data' => $data]);
    }
}
