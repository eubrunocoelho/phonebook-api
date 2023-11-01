<?php

namespace Controller;

use Resources\JsonResource;

class AuthController
{
    private $resource;

    public function __construct(JsonResource $resource)
    {
        $this->resource = $resource;
    }

    public function store()
    {
    }
}
