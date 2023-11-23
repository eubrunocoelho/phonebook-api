<?php

namespace lib;

use Closure;
use Exception;
use Exceptions\CustomException;
use lib\Router;

class Dispatcher
{
    private $router;
    private $dependencies;

    public function __construct(Router $router, array $dependencies)
    {
        $this->router = $router;
        $this->dependencies = $dependencies;
    }

    public function dispatch(): void
    {
        try {
            $result = $this->router->handler();
        } catch (Exception $e) {
            throw new CustomException($e->getMessage(), $e->getCode(), $e);
        }

        $data = $result->getData();

        foreach ($data['before'] as $before) if (!$before($this->router->getParams())) die();

        if ($data['action'] instanceof Closure) $data['action']($this->router->getParams(), $this->dependencies);
        elseif (is_string($data['action'])) {
            $ex = explode('::', $data['action']);
            $controller = $ex[0];
            $action = $ex[1];

            $this->loadController($controller, $action, $this->dependencies);
        }

        foreach ($data['after'] as $after) if (!$after($this->router->getParams())) die();
    }

    public function loadController(string $controller, string $action, array $dependencies): void
    {
        if (class_exists($controller) && method_exists($controller, $action)) {
            $controller = new $controller($dependencies);
            $controller->$action($this->router->getParams());
        } else throw new CustomException('Houve um erro interno.', 500);
    }
}
