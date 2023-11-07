<?php

namespace Controller;

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
        
        // return $this->resource->toJson(200, 'Olá, mundo!', ['index' => 'testando controller']);
    }
}
