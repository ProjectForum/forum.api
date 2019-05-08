<?php


namespace App\Services\Forum;

use App\Repositories\Forum\Interfaces\IUserRepository;
use App\Exceptions\ResultException;
use App\Libs\Result;
use App\Models\User;
use App\Services\Forum\Interfaces\IUserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class UserService implements IUserService
{
    /**
     * @var IUserRepository
     */
    protected $userRepository;

    public function __construct(IUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
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
        return $this->userRepository->createUser($name, $email, $password);
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
            $user = $this->userRepository->findByEmail($email);
            if (empty($user)) {
                throw new ResultException('用户不存在', Result::USER_NOT_FOUND, Response::HTTP_UNAUTHORIZED);
            }
            if (!Hash::check($password, $user->password)) {
                throw new ResultException('密码错误', Result::USER_PASSWORD_ERROR, Response::HTTP_UNAUTHORIZED);
            }
        } else {
            $user = auth()->user();
        }

        return $this->userRepository->generateToken($user);
    }
}
