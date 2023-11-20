<?php

namespace Handlers\Token;

use Models\{
    DAO\TokenDAO,
    DAO\UserDAO,
    Token,
    User
};

use Handlers\Handler;

class StoreHandler extends Handler
{
    public function handle(array $data, Object $controller): array|Object
    {
        $TokenDAO = new TokenDAO($controller->connection);
        $Token = new Token();
        $UserDAO = new UserDAO($controller->connection);
        $User = new User();

        $Token->setUserId($data['user']['id']);
        $tokenExists = $TokenDAO->getTokenByUserId($Token);

        if (!$tokenExists) {
            $write = [
                'user_id' => $data['user']['id'],
                'token' => $data['token']['token'],
                'expiration_date' => $data['token']['expiration_date']
            ];

            $Token->setUserId($write['user_id']);
            $Token->setToken($write['token']);
            $Token->setExpirationDate($write['expiration_date']);

            if ($resultId = $TokenDAO->register($Token)) {
                $User->setId($data['user']['id']);
                $Token->setId($resultId);

                unset($data);

                $data['user'] = $UserDAO->getUserById($User);
                $data['user']['token'] = $TokenDAO->getTokenById($Token);

                unset($data['user']['password']);
                unset($data['user']['token']['id']);
                unset($data['user']['token']['user_id']);

                return $controller->jsonResource->toJson(200, 'Usuário autenticado com sucesso!', ['data' => $data]);
            }
        } else {
            foreach ($this->successors as $successor) $data = $successor->handle($data, $controller);

            return $data;
        }
    }
}
