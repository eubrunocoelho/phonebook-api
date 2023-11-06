<?php

namespace Validation;

abstract class Validators
{
    protected function required($value)
    {
        return (strlen($value) > 0) ? true : false;
    }

    protected function min($value, $ruleValue)
    {
        return (strlen($value) < $ruleValue) ? true : false;
    }
}
