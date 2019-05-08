<?php


namespace App\Repositories\Forum\Interfaces;

use App\Models\User;

interface IUserRepository
{
    public function createUser(string $name, string $email, string $password): User;

    public function generateToken(User $user): ?string;

    public function findByName(string $userName): ?User;

    /**
     * 通过电子邮箱查找用户
     *
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email): ?User;
}
