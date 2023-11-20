<?php

namespace Handlers;

abstract class Handler
{
    protected $successors = [];
    
    public function setSuccessor($successor)
    {
        $this->successors[] = $successor;
    }

    abstract public function handle($data, $controller);
}
