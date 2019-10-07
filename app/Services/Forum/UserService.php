<?php


namespace App\Services\Forum;

use App\Entities\Model\User;
use App\Entities\QueryObject\User\UserRegisterQO;
use App\Exceptions\AuthException;
use App\Repositories\Interfaces\IUserRepository;
use App\Services\Forum\Interfaces\IUserService;
use Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserService implements IUserService
{
    /**
     * @var IUserRepository
     */
    private $userRepository;

    public function __construct(
        IUserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * 注册用户
     * @param UserRegisterQO $registerQO
     * @return User
     */
    public function register(UserRegisterQO $registerQO): User
    {
        $registerQO->validate([
            'username' => ['bail', 'required', 'min:3', 'max:10', 'regex:/^[\x{4e00}-\x{9fa5}_a-zA-Z0-9]+$/u', 'unique:users'],
            'email' => 'bail|required|email|unique:users',
            'password' => 'bail|required|min:6',
        ], [
            'name.required' => '用户名不能为空',
            'name.unique' => '用户名已经他人占用',
            'name.min' => '用户名长度不能小于3位',
            'name.max' => '用户名长度不能大于10位',
            'name.regex' => '用户名',
            'email.required' => '电子邮箱不能为空',
            'email.unique' => '电子邮箱已被占用',
            'email.email' => '电子邮箱格式不正确',
            'password.required' => '密码不能为空',
            'password.min' => '密码长度不能小于6位',
        ]);

        return $this->userRepository->createUser(
            $registerQO->username,
            $registerQO->email,
            $registerQO->password
        );
    }

    /**
     * @param $email
     * @param $password
     * @return string
     * @throws AuthException
     */
    public function createSession($email, $password): string
    {
        if (auth()->guest()) {
            $user = $this->userRepository->findByEmail($email);
            if (empty($user)) {
                throw new AuthException('用户不存在');
            }
            if (!Hash::check($password, $user->password)) {
                throw new AuthException('密码错误');
            }
        } else {
            $user = auth()->user();
        }

        return JWTAuth::fromUser($user);
    }
}