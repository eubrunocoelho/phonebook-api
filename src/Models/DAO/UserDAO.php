<?php

namespace Models\DAO;

use Models\User;

class UserDAO
{
    private $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function register(User $User)
    {
        // ...
    }
}
