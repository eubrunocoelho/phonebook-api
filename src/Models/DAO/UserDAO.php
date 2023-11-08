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
}
