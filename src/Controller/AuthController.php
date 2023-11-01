<?php

namespace Controller;

use Resources\JsonResource;

class AuthController
{
    private $resource;

    public function __construct($dependency)
    {
        $this->resource = $dependency['Resources\JsonResource'];
    }

    public function store()
    {
        $this->resource->toJson('Olá, mundo!');
    }
}
