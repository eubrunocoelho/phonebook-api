<?php

namespace Validation;

abstract class Validators
{
    protected function required($value)
    {
        return (strlen($value) > 0) ? true : false;
    }
}
