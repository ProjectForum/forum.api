<?php

namespace App\Http\Controllers\Forum\User;

use App\Libs\Result;
use App\Services\Forum\Interfaces\IUserService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * @var IUserService
     */
    protected $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $data = $this->validate(
            $request,
            [
                'name' => 'required|unique:users|min:3|max:10',
                'email' => 'required|unique:users|email',
                'password' => 'required|min:6',
            ],
            [
                'name.required' => '用户名不能为空',
                'name.unique' => '用户名已经他人占用',
                'name.min' => '用户名长度不能小于3位',
                'name.max' => '用户名长度不能大于10位',
                'email.required' => '电子邮箱不能为空',
                'email.unique' => '电子邮箱已被占用',
                'email.email' => '电子邮箱格式不正确',
                'password.required' => '密码不能为空',
                'password.min' => '密码长度不能小于6位',
            ]
        );

        $user = $this->userService->register(
            $data['name'],
            $data['email'],
            $data['password']
        );

        auth()->setUser($user);
        $token = $this->userService->createSessionToken('', '');

        return Result::success('注册成功', [
            'token' => $token,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
