<?php

namespace Resources;

class JsonResource
{
    public function toJson($data)
    {
        echo json_encode($data);
    }
}
