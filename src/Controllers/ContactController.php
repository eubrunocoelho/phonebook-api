<?php

namespace Controllers;

use lib\ConnectionFactory;

class ContactController
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

    public function index(): void
    {
        echo 'Contacts';
    }

    public function store()
    {
        $data = $this->jsonRequestService->getData();

        $rules = [
            'name' => [
                'required' => true,
                'min' => 3,
                'max' => 255,
                'regex' => '/^[a-zA-ZÀ-ÿ\s]+$/u'
            ],
            'email' => [
                'required' => false,
                'email' => true,
                'max' => 128,
                'unique' => 'email|contacts'
            ]
        ];

        $this->validate->validate($data, $rules);
        $_errors = $this->validate->getErrors() ?? [];

        foreach ($_errors as $key => $value) $errors[] = $value;

        if ($this->validate->passed()) {
            //
        } else
            return $this->jsonResource->toJson(422, 'Erro ao tentar cadastrar o contato.', ['errors' => $errors]);
    }
}
