<?php

namespace lib;

use Closure;

class RouteEntity
{
    private $action;
    private $afterMiddleware = [];
    private $beforeMiddleware = [];

    public function __construct(Closure|string $action)
    {
        $this->action = $action;
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
            'after' => $this->afterMiddleware,
            'before' => $this->beforeMiddleware
        ];
    }
}
