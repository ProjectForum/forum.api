<?php


namespace App\Services;

use App\DAL\Interfaces\IUserDao;
use App\Exceptions\ResultException;
use App\Libs\Result;
use App\Models\User;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    /**
     * @var IUserDao
     */
    protected $userDao;

    public function __construct(IUserDao $userDao)
    {
        $this->userDao = $userDao;
    }

    /**
     * 注册用户
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function register(string $name, string $email, string $password): User
    {
        return $this->userDao->createUser($name, $email, $password);
    }

    /**
     * 生成会话令牌
     *
     * @param string $email
     * @param string $password
     * @return string
     * @throws ResultException
     */
    public function createSessionToken(string $email, string $password): string
    {
        if (auth()->guest()) {
            $user = $this->userDao->findByEmail($email);
            if (empty($user)) {
                throw new ResultException('用户不存在', Result::USER_NOT_FOUND, Response::HTTP_UNAUTHORIZED);
            }
            if (!Hash::check($password, $user->password)) {
                throw new ResultException('密码错误', Result::USER_PASSWORD_ERROR, Response::HTTP_UNAUTHORIZED);
            }
        } else {
            $user = auth()->user();
        }

        return $this->userDao->generateToken($user);
    }
}
