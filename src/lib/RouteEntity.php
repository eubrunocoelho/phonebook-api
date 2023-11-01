<?php

namespace lib;

use Closure;

class RouteEntity
{
    private $action;
    private $dependency;
    private $afterMiddleware = [];
    private $beforeMiddleware = [];

    public function __construct(Closure|string $action, $dependency)
    {
        $this->action = $action;
        $this->dependency = $dependency;
    }

    public function before(Closure $middleware): Object
    {
        $this->beforeMiddleware[] = $middleware;

        return $this;
    }

    public function after(Closure $middleware): Object
    {
        $this->afterMiddleware[] = $middleware;

        return $this;
    }

    public function getData(): array
    {
        return [
            'action' => $this->action,
            'dependency' => $this->dependency,
            'after' => $this->afterMiddleware,
            'before' => $this->beforeMiddleware
        ];
    }
}
