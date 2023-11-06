<?php

namespace Controller;

class AuthController
{
    private $jsonResource;
    private $jsonRequestService;
    private $validation;

    public function __construct($dependency)
    {
        $this->jsonResource = $dependency['Resources\JsonResource'];
        $this->jsonRequestService = $dependency['Services\JsonRequestService'];
        $this->validation = $dependency['Validation\Validation'];
    }

    public function store()
    {
        $data = array_map('trim', $this->jsonRequestService->getData());

        $rules = [
            'name' => [
                'required' => true,
                'min' => 3,
                'max' => 255
            ]
        ];

        $this->validation->setData($data);
        $this->validation->setRules($rules);
        $this->validation->validation();
        
        dd($this->validation->getErrors());

        // OK

        // return $this->resource->toJson(200, 'Olá, mundo!', ['index' => 'testando controller']);
    }
}
