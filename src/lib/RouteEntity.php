<?php

namespace lib;

class RouteEntity
{
    private $action;
    private $afterMiddleware = [];
    private $beforeMiddleware = [];

    public function __construct($action)
    {
        $this->action = $action;
    }

    public function before($middleware)
    {
        $this->beforeMiddleware[] = $middleware;

        return $this;
    }

    public function after($middleware)
    {
        $this->afterMiddleware[] = $middleware;

        return $this;
    }

    public function getData()
    {
        return [
            'action' => $this->action,
            'after' => $this->afterMiddleware,
            'before' => $this->beforeMiddleware
        ];
    }
}
