<?php

namespace App\Http\Controllers\Installation;

use App\Libs\Helper;
use App\Libs\Result;
use App\Services\Installation\InstallService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class SettingController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param InstallService $installService
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request, InstallService $installService)
    {
        list($rules, $errors) = Helper::buildValidateRules([
            'username' => '管理员用户名',
            'email' => '管理员电子邮箱',
            'password' => '管理员密码',
        ]);

        $input = $this->validate($request, $rules, $errors);

        $installService->initSetting($input);
        $installService->createLock();

        return Result::success('初始化成功');
    }
}
