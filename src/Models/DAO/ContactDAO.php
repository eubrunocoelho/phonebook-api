<?php

namespace Models\DAO;

use Models\Contact;
use PDO;

class ContactDAO
{
    private $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function register(Contact $Contact)
    {
        $SQL = 'INSERT INTO contacts (user_id, name, email) VALUES (:user_id, :name, :email);';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':user_id', $Contact->getUserId(), PDO::PARAM_INT);
        $stmt->bindValue(':name', $Contact->getName(), PDO::PARAM_STR);
        $stmt->bindValue(':email', $Contact->getEmail(), PDO::PARAM_STR);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $this->database->lastInsertId() : false;
    }
}
