<?php

namespace Validation;

use lib\ConnectionFactory;
use PDO;

abstract class Validators
{
    protected function required(int|string $value): bool
    {
        return (strlen($value) > 0) ? true : false;
    }

    protected function min(int|string $value, bool|int|string $ruleValue): bool
    {
        return (!(strlen($value) < $ruleValue)) ? true : false;
    }

    protected function max(int|string $value, bool|int|string $ruleValue): bool
    {
        return (!(strlen($value) > $ruleValue)) ? true : false;
    }

    protected function email(int|string $value): bool
    {
        return (filter_var($value, FILTER_VALIDATE_EMAIL)) ? true : false;
    }

    protected function regex(int|string $value, bool|int|string $ruleValue): bool
    {
        return (preg_match($ruleValue, $value)) ? true : false;
    }

    protected function unique(int|string $value, bool|int|string $ruleValue): bool
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
