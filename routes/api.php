<?php

use Middlewares\Authentication;
use Middlewares\Cors;

$router->post('/register', 'Controllers\AuthController::register')
    ->before(function () use ($method) {
        Cors::handleCorsHeaders($method);

        return true;
    });

$router->post('/login', 'Controllers\AuthController::login')
    ->before(function () use ($method) {
        Cors::handleCorsHeaders($method);

        return true;
    });

$router->get('/contacts', 'Controllers\ContactController::index')
    ->before(function () use ($method) {
        Cors::handleCorsHeaders($method);

        return true;
    })
    ->before(function () {
        $isAuth = Authentication::authorization();

        return (!!$isAuth) ? true : false;
    });

$router->get('/contacts/{id}', 'Controllers\ContactController::show')
    ->before(function () use ($method) {
        Cors::handleCorsHeaders($method);

        return true;
    })
    ->before(function () {
        $isAuth = Authentication::authorization();

        return (!!$isAuth) ? true : false;
    });

$router->post('/contacts', 'Controllers\ContactController::store')
    ->before(function () use ($method) {
        Cors::handleCorsHeaders($method);

        return true;
    })
    ->before(function () {
        $isAuth = Authentication::authorization();

        return (!!$isAuth) ? true : false;
    });

$router->put('/contacts/{id}', 'Controllers\ContactController::update')
    ->before(function () use ($method) {
        Cors::handleCorsHeaders($method);

        return true;
    })
    ->before(function () {
        $isAuth = Authentication::authorization();

        return (!!$isAuth) ? true : false;
    });
