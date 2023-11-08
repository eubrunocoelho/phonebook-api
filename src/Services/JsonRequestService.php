<?php

namespace Services;

class JsonRequestService
{
    public function getData(): array
    {
        return json_decode(file_get_contents('php://input'), true);
    }
}
