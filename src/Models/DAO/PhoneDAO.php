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
}
