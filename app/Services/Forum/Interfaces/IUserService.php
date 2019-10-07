<?php


namespace App\Services\Forum\Interfaces;


use App\Entities\Model\User;
use App\Entities\QueryObject\User\UserRegisterQO;
use App\Exceptions\AuthException;

interface IUserService
{
    /**
     * 注册用户
     * @param UserRegisterQO $registerQO
     * @return User
     */
    public function register(UserRegisterQO $registerQO): User;

    /**
     * @param $email
     * @param $password
     * @return string
     * @throws AuthException
     */
    public function createSession($email, $password): string;
}
