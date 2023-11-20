<?php

namespace Handlers\Token;

use Handlers\Handler;
use Models\DAO\TokenDAO;
use Models\DAO\UserDAO;
use Models\Token;
use Models\User;

class StoreHandler extends Handler
{
    public function handle($data, $controller)
    {
        $TokenDAO = new TokenDAO($controller->connection);
        $Token = new Token();
        $UserDAO = new UserDAO($controller->connection);
        $User = new User();

        if (!$data['result_token']) {
            
            $write = [
                'user_id' => $data['user']['id'],
                'token' => $data['token']['token'],
                'expiration_date' => $data['token']['expiration_date']
            ];

            $Token->setUserId($data['user']['id']);
            $Token->setToken($write['token']);
            $Token->setExpirationDate($write['expiration_date']);

            $resultId = $TokenDAO->register($Token);
            
            if ($resultId !== false) {
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
            foreach ($this->successors as $successor) {
                $data = $successor->handle($data, $controller);
            }

            return $data;
        }
    }
}
