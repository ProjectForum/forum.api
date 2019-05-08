<?php


namespace App\Services\Forum\Interfaces;


use App\Models\User;

interface IUserService
{
    public function register(string $name, string $email, string $password): User;
    public function createSessionToken(string $email, string $password): string;
}
