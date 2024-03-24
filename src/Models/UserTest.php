<?php

use PHPUnit\Framework\TestCase;
use SONFin\Models\User;

class UserTest extends TestCase
{
    public function testGetHashedPassword()
    {
        $user = new User();
        $user->password = 'password123';

        $hashedPassword = $user->getHashedPassword();

        $this->assertEquals('password123', $hashedPassword);
    }

    public function testGetId()
    {
        $user = new User();
        $user->id = 1;

        $id = $user->getId();

        $this->assertEquals(1, $id);
    }

    public function testGetUsername()
    {
        $user = new User();
        $user->email = 'test@example.com';

        $username = $user->getUsername();

        $this->assertEquals('test@example.com', $username);
    }

    public function testGetEmail()
    {
        $user = new User();
        $user->email = 'test@example.com';

        $email = $user->getEmail();

        $this->assertEquals('test@example.com', $email);
    }

    public function testGetFullname()
    {
        $user = new User();
        $user->first_name = 'John';
        $user->last_name = 'Doe';

        $fullname = $user->getFullname();

        $this->assertEquals('John Doe', $fullname);
    }

    public function testGetPassword()
    {
        $user = new User();
        $user->password = 'password123';

        $password = $user->getPassword();

        $this->assertEquals('password123', $password);
    }
}