<?php

namespace App\Http\Controllers\Installation;

use App\Libs\Helper;
use App\Libs\Result;
use App\Services\Installation\InstallService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Illuminate\Validation\ValidationException;

class ConfigController extends Controller
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
        // 验证字段
        list($rules, $errors) = Helper::buildValidateRules([
            'appName' => '应用名称',
            'appUrl' => '站点地址',
            'dbHost' => '数据库主机',
            'dbPort' => '数据库端口',
            'dbDatabase' => '数据库名',
            'dbUsername' => '数据库用户名',
            'dbPassword' => null,
            'dbPrefix' => null,
        ]);

        $config = $this->validate($request, $rules, $errors);

        $installService->createConfig($config);

        return Result::success('创建成功');
    }
}
