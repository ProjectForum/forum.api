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
        try {
            $commandOutput = $installService->migrateTables();
            $commandOutput .= "\n";
            $commandOutput .= $installService->seedDatabase();
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
