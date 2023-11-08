<?php

namespace Resources;

class JsonResource
{
    public function toJson(int $status, string $message, array $extra = []): void
    {
        $response['status'] = $status;

        if ($message) $response['message'] = $message;

        http_response_code($status);

        echo json_encode(array_merge($response, $extra));

        die();
    }
}
