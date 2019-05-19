<?php

namespace App\Http\Controllers\Forum\User;

use App\Exceptions\ResultException;
use App\Libs\Result;
use App\Services\Forum\Interfaces\IUserService;
use App\Services\Forum\UserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     * @throws ValidationException
     * @throws ResultException
     */
    public function store(Request $request)
    {
        $input = $this->validate(
            $request,
            [
                'email' => 'required',
                'password' => 'required',
            ],
            [
                'email.required' => '电子邮箱不能为空',
                'password.required' => '密码不能为空',
            ]
        );

        // 创建token
        $token = $this->userService->createSessionToken($input['email'], $input['password']);

        return Result::success(
            '登录成功',
            [
                'token' => $token
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
