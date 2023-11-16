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

    public function getContactById(Contact $Contact): array|bool
    {
        $SQL = 'SELECT * FROM contacts WHERE id = :id LIMIT 1;';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':id', $Contact->getId(), PDO::PARAM_INT);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public function register(Contact $Contact): bool|int
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
