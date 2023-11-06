<?php

namespace Controller;

class AuthController
{
    private $resource;

    public function __construct($dependency)
    {
        $this->resource = $dependency['Resources\JsonResource'];
    }

    public function store()
    {
        return $this->resource->toJson(200, 'Olá, mundo!', ['index' => 'testando controller']);
    }
}
