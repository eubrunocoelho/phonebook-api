<?php

namespace Resources;

class JsonResource
{
    public function toJson(int $status, mixed $message = null, array $extra = []): void
    {
        $response['status'] = $status;

        if ($message) $response['message'] = $message;

        http_response_code($status);

        echo json_encode(array_merge($response, $extra));
        die();
    }
}
