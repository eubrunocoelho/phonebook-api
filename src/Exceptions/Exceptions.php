<?php

namespace Exceptions;

use Exception;

class Exceptions extends Exception
{
    public function __construct(string $message, int $code, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $response['status'] = $this->getCode();
        $response['message'] = $this->getMessage();

        header_remove();
        header('Content-Type: application/json; charset=UTF-8');

        http_response_code($this->getCode());

        echo json_encode($response);
        die();
    }
}
