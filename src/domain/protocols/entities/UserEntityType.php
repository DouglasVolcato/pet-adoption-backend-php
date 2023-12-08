<?php

namespace PetAdoption\domain\protocols\entities;

class UserEntityType
{
    public string $id;
    public string $name;
    public string $email;
    public string $password;
    public bool $admin;
};
