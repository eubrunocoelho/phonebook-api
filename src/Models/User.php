<?php

namespace Models;

class User
{
    private $id;
    private $username;
    private $email;
    private $password;

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setEmail($email): void
    {
        $this->email = $email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setPassword(int|string $password): void
    {
        $this->password = $password;
    }

    public function getPassword(): int|string
    {
        return $this->password;
    }
}
