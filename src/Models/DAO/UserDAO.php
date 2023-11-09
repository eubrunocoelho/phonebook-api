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

    public function register(User $User): bool
    {
        $SQL = 'INSERT INTO users (username, email, password) VALUES (:username, :email, :password);';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':username', $User->getUsername(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $User->getEmail(), PDO::PARAM_STR);
        $stmt->bindValue(':password', $User->getPassword(), PDO::PARAM_STR);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? true : false;
    }

    public function getUserByUsernameOrEmail(User $User): array|bool
    {
        $SQL = 'SELECT * FROM users WHERE email = :user OR username = :user LIMIT 1;';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':user', $User->getUser(), PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result) ? $result : false;
    }
}
