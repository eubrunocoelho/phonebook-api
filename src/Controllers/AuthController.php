<?php

namespace Controllers;

use lib\ConnectionFactory;
use Models\DAO\TokenDAO;
use Models\DAO\UserDAO;
use Models\Token;
use Models\User;
use Resources\JsonResource;

class AuthController
{
    private $jsonResource;
    private $jsonRequestService;
    private $validate;
    private $connection;

    public function __construct(array $dependency)
    {
        $this->jsonResource = $dependency['Resources\JsonResource'];
        $this->jsonRequestService = $dependency['Services\JsonRequestService'];
        $this->validate = $dependency['Validate\Validate'];
        $this->connection = ConnectionFactory::getConnection();
    }

    public function register(): JsonResource
    {
        $UserDAO = new UserDAO($this->connection);
        $User = new User();

        $data = $this->jsonRequestService->getData();

        $rules = [
            'username' => [
                'required' => true,
                'min' => 3,
                'max' => 255,
                'unique' => 'username|users',
                'regex' => '/^[a-zA-Z_][a-zA-Z0-9_]*$/'
            ],
            'email' => [
                'required' => true,
                'email' => true,
                'max' => 128,
                'unique' => 'email|users'
            ],
            'password' => [
                'required' => true,
                'min' => 8,
                'max' => 128
            ]
        ];

        $this->validate->validate($data, $rules);
        $_errors = $this->validate->getErrors() ?? [];

        foreach ($_errors as $key => $value) $errors[] = $value;

        if ($this->validate->passed()) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

            $User->setUsername($data['username']);
            $User->setEmail($data['email']);
            $User->setPassword($data['password']);
            $resultId = $UserDAO->register($User);

            if (!!$resultId) {
                unset($data);

                $User->setId($resultId);
                $data = $UserDAO->getUserById($User);
                unset($data['password']);

                return $this->jsonResource->toJson(201, 'Usuário cadastrado com sucesso!', ['data' => $data]);
            } else
                return $this->jsonResource->toJson(500, 'Houve um erro interno.');
        } else
            return $this->jsonResource->toJson(422, 'Erro ao tentar cadastrar o usuário.', ['errors' => $errors]);
    }

    public function login(): JsonResource
    {
        $UserDAO = new UserDAO($this->connection);
        $User = new User();
        $TokenDAO = new TokenDAO($this->connection);
        $Token = new Token();

        $data = $this->jsonRequestService->getData();

        $rules = [
            'username' => [
                'required' => true
            ],
            'password' => [
                'required' => true
            ]
        ];

        $this->validate->validate($data, $rules);
        $_errors = $this->validate->getErrors() ?? [];

        foreach ($_errors as $key => $value) $errors[] = $value;

        if ($this->validate->passed()) {
            $User->setUser($data['username']);
            $result = $UserDAO->getUserByUsernameOrEmail($User);

            if ($result && password_verify($data['password'], $result['password'])) {
                unset($result['password']);

                $Token->setUserId($result['id']);
                $resultToken = $TokenDAO->getTokenByUserIdAndExpirationDate($Token);

                $userId = $result['id'];
                $token = bin2hex(random_bytes(16));
                $expirationDate = date('Y-m-d H:i:s', strtotime('+3 days'));

                $Token->setUserId($userId);
                $Token->setToken($token);
                $Token->setExpirationDate($expirationDate);

                if (!$resultToken) {
                    $resultToken = $TokenDAO->getTokenByUserId($Token);

                    if ($resultToken) {
                        $resultToken = $TokenDAO->update($Token);

                        if ($resultToken) $resultToken = $TokenDAO->getTokenByUserId($Token);
                        else return $this->jsonResource->toJson(500, 'Houve um erro interno.');

                        $result['token'] = $resultToken['token'];

                        return $this->jsonResource->toJson(200, 'Usuário autenticado com sucesso!', ['data' => $result]);
                    } else {
                        $resultToken = $TokenDAO->register($Token);

                        if ($resultToken) $resultToken = $TokenDAO->getTokenByUserId($Token);
                        else return $this->jsonResource->toJson(500, 'Houve um erro interno.');

                        $result['token'] = $resultToken['token'];

                        return $this->jsonResource->toJson(200, 'Usuário autenticado com sucesso!', ['data' => $result]);
                    }
                }

                $result['token'] = $resultToken['token'];

                return $this->jsonResource->toJson(200, 'Usuário autenticado com sucesso!', ['data' => $result]);
            } else
                return $this->jsonResource->toJson(401, 'Usuário ou senha inválidos.');
        } else
            return $this->jsonResource->toJson(422, 'Erro ao tentar autenticar o usuário.', ['errors' => $errors]);
    }
}
