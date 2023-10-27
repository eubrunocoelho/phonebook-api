<?php

$router->get('/{ID}', function ($params) {
    echo $params['ID'];
});

$router->get('/testing/{ID}', 'Controller\IndexController::index')
    ->before(function () {
        $checkUserIsAuth = true;

        if (!$checkUserIsAuth) {
            http_response_code(401);

            echo 'Você não está autenticado';
        }

        return $checkUserIsAuth;
    })
    ->before(function () {
        dd('middleware 2');

        return true;
    })
    ->after(function () {
        dd('middleware 3');

        return true;
    });
