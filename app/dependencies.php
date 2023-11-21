<?php

use lib\Connection;
use Resources\JsonResource;
use Services\JsonRequestService;
use Validations\Validation;

return [
    Connection::class => Connection::getConnection(),
    JsonRequestService::class => new JsonRequestService(),
    JsonResource::class => new JsonResource(),
    Validation::class => new Validation()
];