<?php

namespace lib;

use Closure;
use lib\Router;

class Dispatcher
{
    private $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function dispatch(): void
    {
        $result = $this->router->handler();

        if (!$result) {
            http_response_code(404);
            die();
        }

        $data = $result->getData();

        foreach ($data['before'] as $before)
            if (!$before($this->router->getParams())) die();

        if ($data['action'] instanceof Closure)
            $data['action']($this->router->getParams());
        elseif (is_string($data['action'])) {
            $action = explode('::', $data['action']);
            $controller = $action[0];
            $action = $action[1];

            $this->loadController($controller, $action);
        }

        foreach ($data['after'] as $after)
            if (!$after($this->router->getParams())) die();
    }

    public function loadController(string $controller, string $action): void
    {
        if (class_exists($controller) && method_exists($controller, $action)) {
            $controller = new $controller;
            $controller->$action($this->router->getParams());
        }
    }
}
