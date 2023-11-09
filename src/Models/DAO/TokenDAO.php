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
}
