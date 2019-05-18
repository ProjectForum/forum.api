<?php

namespace App\Http\Controllers\Installation;

use App\Libs\Result;
use App\Services\Installation\InstallService;
use Illuminate\Database\QueryException;
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

        try {
            $commandOutput = $installService->migrateTables();
            $commandOutput .= "\n";
            $commandOutput .= $installService->seedDatabase();
//            $installService->createLock();
        } catch (QueryException $e) {
            return Result::error(
                Result::DATABASE_QUERY_FAILED,
                $e->getMessage(),
                Response::HTTP_BAD_REQUEST
            );
        }

        return Result::success(
            '数据库初始化成功',
            [
                'output' => $commandOutput,
            ]
        );
    }
}
