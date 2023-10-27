<?php

namespace lib;

class Router
{
    private $routes = [];
    private $method;
    private $path;
    private $params;

    public function __construct($method, $path)
    {
        $this->method = $method;
        $this->path = $path;
    }

    public function get($route, $action)
    {
        return $this->add('GET', $route, $action);
    }

    public function post($route, $action)
    {
        return $this->add('POST', $route, $action);
    }

    public function put($route, $action)
    {
        return $this->add('PUT', $route, $action);
    }

    public function patch($route, $action)
    {
        return $this->add('PATCH', $route, $action);
    }

    public function delete($route, $action)
    {
        return $this->add('DELETE', $route, $action);
    }

    public function add($method, $route, $action)
    {
        $this->routes[$method][$route] = new RouteEntity($action);
        return $this->routes[$method][$route];
    }

    public function getParams()
    {
        return $this->params;
    }

    public function handler()
    {
        if (empty($this->routes[$this->method])) {
            return false;
        }

        if (isset($this->routes[$this->method][$this->path])) {
            return $this->routes[$this->method][$this->path];
        }

        foreach ($this->routes[$this->method] as $route => $action) {
            $result = $this->checkUrl($route, $this->path);

            if ($result >= 1) {
                return $action;
            }
        }

        return false;
    }

    private function checkUrl($route, $path)
    {
        $route = str_replace('/', '\/', $route);
        preg_match_all('/\{([^{}}]*)\}/', $route, $matches);

        foreach ($matches[0] as $key => $value) {
            $replacement = '([a-zA-Z0-9\-\_\ ]+)';
            $route = str_replace($value, $replacement, $route);
            $names[$key] = $matches[1][$key];
        }

        $route = preg_replace('/{([a-zA-Z]+)}/', '([a-zA-Z0-9+])', $route);
        $result = preg_match('/^' . $route . '$/', $path, $variables);
        
        $params = [];

        if ($result > 0) {
            foreach ($names as $key => $value) {
                $params[$value] = $variables[$key + 1];
            }
        }
        
        $this->params = $params;

        return $result;
    }
}
