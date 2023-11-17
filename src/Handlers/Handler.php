<?php

namespace Handlers;

abstract class Handler
{
    public $successor;
    
    public function setSuccessor($successor)
    {
        $this->successor = $successor;
    }

    abstract public function handle($data, $controller);
}
