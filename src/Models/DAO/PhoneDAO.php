<?php

namespace Models\DAO;

use Models\Phone;
use PDO;

class PhoneDAO
{
    private $database;

    public function __construct(PDO $database)
    {
        $this->database = $database;
    }

    public function getPhoneById(Phone $Phone): array|bool
    {
        $SQL = 'SELECT * FROM phones WHERE id = :id LIMIT 1';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':id', $Phone->getId(), PDO::PARAM_INT);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $stmt->fetch(PDO::FETCH_ASSOC) : false;
    }

    public function getPhonesByContactId(Phone $Phone): array|bool
    {
        $SQL = 'SELECT * FROM phones WHERE contact_id = :contact_id ORDER BY id';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':contact_id', $Phone->getContactId(), PDO::PARAM_INT);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $stmt->fetchAll(PDO::FETCH_ASSOC) : false;
    }

    public function register(Phone $Phone): bool|int
    {
        $SQL = 'INSERT INTO phones (contact_id, phone_number, description) VALUES (:contact_id, :phone_number, :description)';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':contact_id', $Phone->getContactId(), PDO::PARAM_INT);
        $stmt->bindValue(':phone_number', $Phone->getPhoneNumber(), PDO::PARAM_STR);
        $stmt->bindValue(':description', $Phone->getDescription(), PDO::PARAM_STR);
        $stmt->execute();

        return ($stmt->rowCount() > 0) ? $this->database->lastInsertId() : false;
    }

    public function update(Phone $Phone): bool
    {
        $SQL = 'UPDATE phones SET phone_number = :phone_number, description = :description WHERE id = :id';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':phone_number', $Phone->getPhoneNumber(), PDO::PARAM_STR);
        $stmt->bindValue(':description', $Phone->getDescription(), PDO::PARAM_STR);
        $stmt->bindValue(':id', $Phone->getId(), PDO::PARAM_INT);

        return ($stmt->execute()) ? true : false;
    }

    public function deletePhoneById(Phone $Phone): bool
    {
        $SQL = 'DELETE FROM phones WHERE id = :id';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':id', $Phone->getId(), PDO::PARAM_INT);

        return ($stmt->execute()) ? true : false;
    }

    public function deletePhonesByContactId(Phone $Phone): bool
    {
        $SQL = 'DELETE FROM phones WHERE contact_id = :contact_id';

        $stmt = $this->database->prepare($SQL);
        $stmt->bindValue(':contact_id', $Phone->getContactId(), PDO::PARAM_INT);

        return ($stmt->execute()) ? true : false;
    }
}
