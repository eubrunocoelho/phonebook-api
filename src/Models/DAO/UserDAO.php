<?php

namespace Models\DAO;

use Models\User;
use PDO;

class UserDAO
{
    private $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function getUserById(User $User): array|bool
    {
        $SQL = 'SELECT * FROM users WHERE id = :id LIMIT 1;';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':id', $User->getId());
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public function getUserByUsernameOrEmail(User $User): array|bool
    {
        $SQL = 'SELECT * FROM users WHERE email = :user OR username = :user LIMIT 1;';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':user', $User->getUser(), PDO::PARAM_STR);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public function register(User $User): bool
    {
        $SQL = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password);';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':username', $User->getUsername(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $User->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $User->getPassword(), PDO::PARAM_STR);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $this->database->lastInsertId() : false;
    }
}
