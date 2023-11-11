<?php

namespace Models\DAO;

use Models\Token;
use PDO;

class TokenDAO
{
    private $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function getTokenByUserId(Token $Token): bool
    {
        $SQL = 'SELECT * FROM tokens WHERE user_id = :user_id;';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':user_id', $Token->getUserId());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function register(Token $Token): bool
    {
        $SQL = 'INSERT INTO tokens (user_id, token, expiration_date) VALUES (:user_id, :token, :expiration_date);';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':user_id', $Token->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':token', $Token->getToken(), PDO::PARAM_STR);
        $stmt->bindValue(':expiration_date', $Token->getExpirationDate(), PDO::PARAM_STR);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }
}
