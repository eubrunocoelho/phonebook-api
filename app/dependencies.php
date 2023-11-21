<?php

use lib\Connection;
use Resources\JsonResource;
use Services\JsonRequestService;
use Validate\Validate;

return [
    Connection::class => Connection::getConnection(),
    JsonRequestService::class => new JsonRequestService(),
    JsonResource::class => new JsonResource(),
    Validate::class => new Validate()
];