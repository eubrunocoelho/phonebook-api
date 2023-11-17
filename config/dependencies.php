<?php

use lib\ConnectionFactory;
use Resources\JsonResource;
use Services\JsonRequestService;
use Validate\Validate;

return [
    ConnectionFactory::class => ConnectionFactory::getConnection(),
    JsonRequestService::class => new JsonRequestService(),
    JsonResource::class => new JsonResource(),
    Validate::class => new Validate()
];