<?php

namespace entity;

class User extends BaseEntity
{
    private $email;
    private $passwordHash;
    private $username;
    private $firstName;
    private $lastName;
    private $createdAt;

    public function __construct($id, $email, $passwordHash, $username, $firstName, $lastName, $createdAt)
    {
        parent::setId($id);
        $this->email = $email;
        $this->passwordHash = $passwordHash;
        $this->username = $username;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->createdAt = $createdAt;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPasswordHash()
    {
        return $this->passwordHash;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getFirstName()
    {
        return $this->firstName;
    }

    public function getLastName()
    {
        return $this->lastName;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }
}