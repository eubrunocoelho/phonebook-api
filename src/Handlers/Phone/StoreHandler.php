<?php

namespace Handlers\Phone;

use Handlers\Handler;

class StoreHandler extends Handler
{
    public function handle(array $data, Object $controller): array|Object
    {
        return $controller->jsonResource->toJson(200, 'HERE'); // type return x.x
    }
}
