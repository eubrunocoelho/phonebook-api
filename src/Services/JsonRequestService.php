<?php

namespace Services;

use Exceptions\CustomException;

class JsonRequestService
{
    public function getData(): array
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (json_last_error() !== JSON_ERROR_NONE) throw new CustomException('Houve um erro interno.', 500);

        return array_map('trim', $data);
    }
}
