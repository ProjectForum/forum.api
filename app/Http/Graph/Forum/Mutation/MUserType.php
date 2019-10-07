<?php


namespace App\Http\Graph\Forum\Mutation;


use App\Entities\QueryObject\User\UserRegisterQO;
use App\Exceptions\AuthException;
use App\Services\Forum\Interfaces\IUserService;
use App\Supports\GraphQL\Definition\ObjectType;
use App\Supports\GraphQL\Entity\GraphTypeAttrs;
use App\Supports\Helpers\ForumTypes;

class MUserType extends ObjectType
{
    /**
     * @var IUserService
     */
    private $userService;

    public function __construct($args, IUserService $userService)
    {
        parent::__construct($args);
        $this->userService = $userService;
    }

    public function attrs(): GraphTypeAttrs
    {
        return $this->createAttr(
            'MUser',
            '用户变更类型'
        );
    }

    public function fields(): array
    {
        return [
            'createUser' => [
                'type' => ForumTypes::result([
                    'name' => 'RCreateUser',
                    'fields' => [
                        'userId' => ForumTypes::id(),
                    ],
                ]),
                'desc' => '创建用户',
                'args' => [
                    'username' => [
                        'type' => ForumTypes::string(),
                        'desc' => '用户名',
                    ],
                    'email' => [
                        'type' => ForumTypes::string(),
                        'desc' => '电子邮箱',
                    ],
                    'password' => [
                        'type' => ForumTypes::string(),
                        'desc' => '密码',
                    ],
                ],
            ],
            'createSession' => [
                'type' => ForumTypes::result([
                    'name' => 'RCreateSession',
                    'fields' => [
                        'sessionToken' => ForumTypes::string(),
                    ],
                ]),
                'desc' => '创建会话（登录）',
                'args' => [
                    'email' => [
                        'type' => ForumTypes::nonNull(ForumTypes::string()),
                        'desc' => '邮箱',
                    ],
                    'password' => [
                        'type' => ForumTypes::nonNull(ForumTypes::string()),
                        'desc' => '密码',
                    ],
                ],
            ],
        ];
    }

    public function resolveCreateUser(array $val, array $args)
    {
        $user = $this->userService->register(new UserRegisterQO($args));

        return [
            'message' => '注册成功',
            'userId' => $user->id,
        ];
    }

    /**
     * @param array $val
     * @param array $args
     * @return array
     * @throws AuthException
     */
    public function resolveCreateSession(array $val, array $args)
    {
        $sessionToken = $this->userService->createSession($args['email'], $args['password']);

        return [
            'message' => '登录成功',
            'sessionToken' => $sessionToken,
        ];
    }
}