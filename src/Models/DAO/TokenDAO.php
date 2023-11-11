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

    public function getTokenByUserId(Token $Token): array|bool
    {
        $SQL = 'SELECT * FROM tokens WHERE user_id = :user_id LIMIT 1;';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':user_id', $Token->getUserId(), PDO::PARAM_STR);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public function getTokenByUserIdAndExpirationDate(Token $Token): array|bool
    {
        $SQL = 'SELECT * FROM tokens WHERE user_id = :user_id AND expiration_date > NOW() LIMIT 1;';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':user_id', $Token->getUserId(), PDO::PARAM_INT);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public function register(Token $Token): int|bool
    {
        $SQL = 'INSERT INTO tokens (user_id, token, expiration_date) VALUES (:user_id, :token, :expiration_date);';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':user_id', $Token->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':token', $Token->getToken(), PDO::PARAM_STR);
        $stmt->bindValue(':expiration_date', $Token->getExpirationDate(), PDO::PARAM_STR);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $this->database->lastInsertId() : false;
    }

    public function update(Token $Token): bool
    {
        $SQL = 'UPDATE tokens SET token = :token, expiration_date = :expiration_date WHERE user_id = :user_id;';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':token', $Token->getToken(), PDO::PARAM_STR);
        $stmt->bindValue(':expiration_date', $Token->getExpirationDate(), PDO::PARAM_STR);
        $stmt->bindValue(':user_id', $Token->getUserId(), PDO::PARAM_INT);

        return ($stmt->execute()) ? true : false;
    }
}
