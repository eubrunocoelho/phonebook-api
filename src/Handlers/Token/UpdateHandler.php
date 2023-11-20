<?php

namespace Handlers\Token;

use Handlers\Handler;
use Models\DAO\TokenDAO;
use Models\DAO\UserDAO;
use Models\Token;
use Models\User;

class UpdateHandler extends Handler
{
    public function handle($data, $controller)
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
        }
    }
}
