<?php
declare(strict_types= 1);

namespace SONFin\Models;

use Illuminate\Database\Eloquent\Model;
use Jasny\Auth\User as JasnyUser;

class User extends Model implements JasnyUser, UserInterface
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password'
    ];

    public function getHashedPassword(): string
    {
        return $this->password;
    }

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function getUsername(): string
    {
        return $this->email;
    }

    public function onLogin()
    {
        // Implement the method logic here
    }

    public function onLogout()
    {
        // Implement the method logic here
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getFullname(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
