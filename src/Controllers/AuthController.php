<?php

namespace Controllers;

use Handlers\{
    Auth\AuthenticatedHandler,
    Auth\AuthenticateHandler,
    Token\StoreHandler as TokenStoreHandler,
    Token\UpdateHandler as TokenUpdateHandler,
    User\StoreHandler as UserStoreHandler,
    ValidationHandler
};

class AuthController
{
    public $jsonResource;
    public $jsonRequestService;
    public $validation;
    public $connection;

    public function __construct(array $dependency)
    {
        $this->connection = $dependency['lib\Connection'];
        $this->jsonResource = $dependency['Resources\JsonResource'];
        $this->jsonRequestService = $dependency['Services\JsonRequestService'];
        $this->validation = $dependency['Validations\Validation'];
    }

    public function register(): void
    {
        $data['rules'] = [
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

        $ValidationHandler = new ValidationHandler();
        $UserStoreHandler = new UserStoreHandler();

        $ValidationHandler->setSuccessor($UserStoreHandler);
        $ValidationHandler->handle($data, $this);
    }

    public function login(): void
    {
        $data['rules'] = [
            'username' => [
                'required' => true
            ],
            'password' => [
                'required' => true
            ]
        ];

        $ValidationHandler = new ValidationHandler();
        $AuthenticateHandler = new AuthenticateHandler();
        $TokenStoreHandler = new TokenStoreHandler();
        $TokenUpdateHandler = new TokenUpdateHandler;
        $AuthenticatedHandler = new AuthenticatedHandler();

        $ValidationHandler->setSuccessor($AuthenticateHandler);
        $ValidationHandler->setSuccessor($TokenStoreHandler);
        $ValidationHandler->setSuccessor($TokenUpdateHandler);
        $ValidationHandler->setSuccessor($AuthenticatedHandler);
        $ValidationHandler->handle($data, $this);
    }
}
