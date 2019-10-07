<?php

namespace App\Repositories;

use App\Entities\Model\User;
use App\Repositories\Interfaces\IUserRepository;
use Hash;

class UserRepository implements IUserRepository
{

    /**
     * 创建用户
     * @param string $username 用户名
     * @param string $email 电子邮箱
     * @param string $password 密码
     * @return User
     */
    public function createUser(string $username, string $email, string $password): User
    {
        $user = new User;
        $user->username = $username;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }

    /**
     * 通过电子邮箱查找用户
     *
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
