<?php

namespace Validation;

use lib\ConnectionFactory;
use PDO;

abstract class Validators
{
    protected function required($value)
    {
        return (strlen($value) > 0) ? true : false;
    }

    protected function min($value, $ruleValue)
    {
        return (!(strlen($value) < $ruleValue)) ? true : false;
    }

    protected function max($value, $ruleValue)
    {
        return (!(strlen($value) > $ruleValue)) ? true : false;
    }

    protected function email($value)
    {
        return (filter_var($value, FILTER_VALIDATE_EMAIL)) ? true : false;
    }

    protected function regex($value, $ruleValue)
    {
        return (preg_match($ruleValue, $value)) ? true : false;
    }

    protected function unique($value, $ruleValue)
    {
        $ex = explode('|', $ruleValue);
        $database = ConnectionFactory::getConnection();

        $SQL = 'SELECT * FROM ' . $ex[1] . ' WHERE ' . $ex[0] . ' = :value;';
        $stmt = $database->prepare($SQL);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        $stmt->execute();

        return (!$stmt->rowCount() > 0) ? true : false;
    }
}
