<?php

use Resources\JsonResource;
use Services\JsonRequestService;
use Validate\Validate;

return [
    JsonResource::class => new JsonResource(),
    JsonRequestService::class => new JsonRequestService(),
    Validate::class => new Validate()
];