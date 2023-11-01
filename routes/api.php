<?php

use Middlewares\Cors;
use Resources\JsonResource;

$router->get('/users', 'Controller\AuthController::store', [
    JsonResource::class => new JsonResource()
])
    ->before(function () {
        Cors::handleCorsHeaders($_SERVER['REQUEST_METHOD']);
        return true;
    });
