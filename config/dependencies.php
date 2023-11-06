<?php

use Resources\JsonResource;
use Services\JsonRequestService;
use Validation\Validation;

return [
    JsonResource::class => new JsonResource(),
    JsonRequestService::class => new JsonRequestService(),
    Validation::class => new Validation(),
];