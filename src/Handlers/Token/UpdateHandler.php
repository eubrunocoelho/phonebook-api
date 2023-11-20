<?php

namespace Handlers\Token;

use Models\{
    DAO\TokenDAO,
    DAO\UserDAO,
    Token,
    User
};

use Handlers\Handler;

class UpdateHandler extends Handler
{
    public function handle(array $data, Object $controller): array|Object
    {
        $TokenDAO = new TokenDAO($controller->connection);
        $Token = new Token();
        $UserDAO = new UserDAO($controller->connection);
        $User = new User();

        if (!$data['result_token']) {
            $Token->setUserId($data['user']['id']);
            $resultToken = $TokenDAO->getTokenByUserId($Token);

            $write = [
                'id' => $resultToken['id'],
                'token' => $data['token']['token'],
                'expiration_date' => $data['token']['expiration_date']
            ];

            $Token->setId($write['id']);
            $Token->setToken($write['token']);
            $Token->setExpirationDate($write['expiration_date']);

            if ($TokenDAO->update($Token)) {
                $User->setId($data['user']['id']);

                unset($data);

                $data['user'] = $UserDAO->getUserById($User);
                $data['user']['token'] = $TokenDAO->getTokenById($Token);

                unset($data['user']['password']);
                unset($data['user']['token']['id']);
                unset($data['user']['token']['user_id']);

                return $controller->jsonResource->toJson(200, 'Usuário autenticado com sucesso!', ['data' => $data]);
            }
        } else {
            foreach ($this->successors as $successor) {
                $data = $successor->handle($data, $controller);
            }

            return $data;
        }
    }
}
