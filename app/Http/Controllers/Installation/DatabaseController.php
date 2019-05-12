<?php

namespace App\Http\Controllers\Installation;

use App\Libs\Result;
use App\Services\Installation\InstallService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class DatabaseController extends Controller
{

    public function store(InstallService $installService)
    {
        if ($installService->hasLock()) {
            return Result::error(
                Result::REQUEST_FIELD_VALIDATION_FAIL,
                '系统已初始化，无法再次初始化',
                Response::HTTP_BAD_REQUEST
            );
        }

        $commandOutput = $installService->migrateTables();
        $commandOutput .= $installService->seedDatabase();
        $installService->createLock();

        return Result::success(
            '',
            [
                'output' => $commandOutput,
            ]
        );
    }
}
