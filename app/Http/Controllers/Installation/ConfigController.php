<?php

namespace App\Http\Controllers\Installation;

use App\Libs\Result;
use App\Services\Installation\InstallService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class ConfigController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @param InstallService $installService
     * @return Response
     */
    public function store(Request $request, InstallService $installService)
    {
        if ($installService->hasLock()) {
            return Result::error(
                Result::REQUEST_FIELD_VALIDATION_FAIL,
                '系统已初始化，无法再次初始化',
                Response::HTTP_BAD_REQUEST
            );
        }

        $config = $request->only([
            'appName',
            'appUrl',
            'dbHost',
            'dbPort',
            'dbDatabase',
            'dbUsername',
            'dbPassword',
            'dbPrefix',
        ]);

        $installService->createConfig($config);

        return Result::success('创建成功');
    }
}
