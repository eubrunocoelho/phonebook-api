<?php

namespace Handlers;

abstract class Handler
{
    protected $successors = [];
    
    public function setSuccessor(Object $successor): void
    {
        $this->successors[] = $successor;
    }

    abstract public function handle(array $data, Object $controller): array|Object;
}
