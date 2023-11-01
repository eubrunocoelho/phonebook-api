<?php

namespace Resources;

class JsonResource
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function toArray()
    {
        return $this->data;
    }

    public function toJson()
    {
        return json_encode($this->toArray());
    }
}