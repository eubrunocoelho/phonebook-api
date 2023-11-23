<?php

namespace lib;

use Closure;
use Exceptions\CustomException;

class Router
{
    private $routes = [];
    private $method;
    private $path;
    private $params;

    public function __construct(string $method, string $path)
    {
        $this->method = $method;
        $this->path = $path;
    }

    public function get(string $route, Closure|string $action): Object
    {
        return $this->add('GET', $route, $action);
    }

    public function post(string $route, Closure|string $action): Object
    {
        return $this->add('POST', $route, $action);
    }

    public function put(string $route, Closure|string $action): Object
    {
        return $this->add('PUT', $route, $action);
    }

    public function patch(string $route, Closure|string $action): Object
    {
        return $this->add('PATCH', $route, $action);
    }

    public function delete(string $route, Closure|string $action): Object
    {
        return $this->add('DELETE', $route, $action);
    }

    public function add(string $method, string $route, Closure|string $action): Object
    {
        $this->routes['routes'][] = ['method' => $method, 'route' => $route];
        $this->routes[$method][$route] = new RouteEntity($action);

        return $this->routes[$method][$route];
    }

    public function getParams(): ?array
    {
        return $this->params;
    }

    public function handler(): Object
    {
        if ($this->checkDuplicates($this->routes['routes'])) throw new CustomException('Existem rotas duplicadas.', 500);

        if (isset($this->routes[$this->method][$this->path])) return $this->routes[$this->method][$this->path];
        
        foreach ($this->routes[$this->method] as $route => $action) {
            $result = $this->checkUrl($route, $this->path);

            if ($result >= 1) return $action;
        }

        throw new CustomException('Página não encontrada.', 404);
    }

    private function checkDuplicates(array $routes): bool
    {
        $combinations = array_map(function ($item) {
            return $item["method"] . $item["route"];
        }, $routes);

        $count = array_count_values($combinations);

        $duplicates = array_filter($count, function ($value) {
            return $value > 1;
        });

        return (!empty($duplicates)) ? true : false;
    }

    private function checkUrl(string $route, string $path): int
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

        if ($result > 0) foreach ($names as $key => $value) $params[$value] = $variables[$key + 1];

        $this->params = $params;

        return $result;
    }
}
