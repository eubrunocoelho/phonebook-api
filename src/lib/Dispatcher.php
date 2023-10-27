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

    public function dispatch()
    {
        $result = $this->router->handler();

        if (!$result) {
            echo '404';
            die();
        }

        $data = $result->getData();

        foreach ($data['before'] as $before) {
            if (!$before($this->router->getParams())) {
                die();
            }
        }

        if ($data['action'] instanceof Closure) {
            echo $data['action']($this->router->getParams());
        } elseif (is_string($data['action'])) {
            $data['action'] = explode('::', $data['action']);
            $controller = new $data['action'][0];
            $action = $data['action'][1];

            echo $controller->$action($this->router->getParams());
        }

        foreach ($data['after'] as $after) {
            if (!$after($this->router->getParams())) {
                die();
            }
        }
    }
}
