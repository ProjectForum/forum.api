<?php

namespace App\Http\Middleware;

use App\Libs\Result;
use App\Services\Installation\InstallService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InstallationLock
{
    /**
     * @var InstallService
     */
    protected $installService;

    public function __construct(InstallService $installService)
    {
        $this->installService = $installService;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if ($this->installService->hasLock()) {
            return Result::error(
                Result::CANNOT_REINSTALL,
                '系统已初始化，无法再次初始化',
                Response::HTTP_BAD_REQUEST
            );
        }

        return $next($request);
    }
}
