<?php

namespace Validations;

use lib\Connection;
use PDO;

abstract class Validators
{
    protected function isEmpty(int|string $value): bool
    {
        return (!(strlen($value) > 0)) ? true : false;
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
        $database = Connection::getConnection();

        $SQL = 'SELECT * FROM ' . $ex[1] . ' WHERE ' . $ex[0] . ' = :value;';
        $stmt = $database->prepare($SQL);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        $stmt->execute();

        return (!$stmt->rowCount() > 0) ? true : false;
    }

    protected function contactUnique(int|string $value, bool|int|string $ruleValue): bool
    {
        $ex = explode('|', $ruleValue);
        $database = Connection::getConnection();

        $SQL = 'SELECT * FROM ' . $ex[1] . ' WHERE ' . $ex[2] . ' = ' . $ex[3] . ' AND ' . $ex[0] . ' = :value';
        $stmt = $database->prepare($SQL);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        $stmt->execute();

        return (!$stmt->rowCount() > 0) ? true : false;
    }

    protected function contactUniqueForUpdate(int|string $value, bool|int|string $ruleValue): bool
    {
        $ex = explode('|', $ruleValue);
        $database = Connection::getConnection();

        $SQL = 'SELECT * FROM ' . $ex[1] . ' WHERE ' . $ex[2] . ' = ' . $ex[3] . ' AND ' . $ex[0] . ' = :value AND ' . $ex[4] . ' != ' . $ex[5] . ';';
        $stmt = $database->prepare($SQL);
        $stmt->bindValue(':value', $value, PDO::PARAM_STR);
        $stmt->execute();

        return (!$stmt->rowCount() > 0) ? true : false;
    }
}
