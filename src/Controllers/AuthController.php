<?php

namespace Controllers;

class AuthController
{
    private $jsonResource;
    private $jsonRequestService;
    private $validate;

    public function __construct($dependency)
    {
        $this->jsonResource = $dependency['Resources\JsonResource'];
        $this->jsonRequestService = $dependency['Services\JsonRequestService'];
        $this->validate = $dependency['Validation\Validate'];
    }

    public function store()
    {
        $data = array_map('trim', $this->jsonRequestService->getData());

        $rules = [
            'username' => [
                'required' => true,
                'min' => 3,
                'max' => 255
            ],
            'email' => [
                'required' => true,
                'email' => true,
                'max' => 128,
            ],
            'password' => [
                'required' => true,
                'min' => 8,
                'max' => 128
            ]
        ];

        $this->validate->validation($data, $rules);
        $_errors = $this->validate->getErrors();

        foreach ($_errors as $key => $value) {
            $errors[] = $value;
        }

        if (!$this->validate->passed())
            return $this->jsonResource->toJson(422, 'Erro ao tentar cadastrar o usuário.', ["errors" => $errors]);
    }
}
