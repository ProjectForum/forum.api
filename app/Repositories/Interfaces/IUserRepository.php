<?php


namespace App\Repositories\Interfaces;


use App\Entities\Model\User;

interface IUserRepository
{
    /**
     * 创建用户
     * @param string $username 用户名
     * @param string $email 电子邮箱
     * @param string $password 密码
     * @return User
     */
    public function createUser(string $username, string $email, string $password): User;

    /**
     * 通过电子邮箱查找用户
     *
     * @param string $email
     * @return User
     */
    public function findByEmail(string $email): ?User;
}
