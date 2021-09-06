<?php

namespace App\Http\Controllers\Admin\Base;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LoginRequests;
use App\Models\Administrator;
use App\Models\AdminLoginLog;
use App\Services\AdministratorService;
use App\Utils\Ip;
use App\Utils\Result;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    private $administratorService;

    /**
     * AuthController constructor.
     */
    public function __construct(AdministratorService $administratorService)
    {
        $this->administratorService = $administratorService;
    }


    /**
     * 登录
     * @param LoginRequests $loginRequests
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function login(LoginRequests $loginRequests)
    {
        $params = $loginRequests->validated();

        $ips = request()->getClientIp();

        $data = $this->administratorService->login($params['account'], $params['password'], $ips);

        return Result::success($data);
    }

}
