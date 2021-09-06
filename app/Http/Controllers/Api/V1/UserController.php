<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Utils\Result;

class UserController extends Controller
{

    private $userService;

    /**
     * UserController constructor.
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }


    /**
     * 用户授权登录
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function login()
    {
        $redirectUrl = $this->userService->login();
        return redirect($redirectUrl);
    }

    /**
     * 获取用户token
     * @return \Illuminate\Http\JsonResponse
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function token()
    {
        $userInfo = $this->userService->token();

        return Result::success($userInfo);
    }


    /**
     * 获取我的联系方式
     * @return \Illuminate\Http\JsonResponse
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function phone()
    {
        $userInfo = $this->userService->phone();

        return Result::success($userInfo);
    }

    /**
     * 添加手机号
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function phoneCreate()
    {
        $phone = request()->get("phone", '');
        $code = request()->get("code", '');

        $result = $this->userService->phoneCreate($phone, $code);
        return Result::choose($result);
    }

    /**
     * 修改手机号
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function phoneUpdate()
    {
        $phone = request()->get("phone", '');
        $code = request()->get("code", '');
        $newPhone = request()->get("new_phone", '');
        $newCode = request()->get("new_code", '');

        $result = $this->userService->phoneUpdate($phone, $code, $newPhone, $newCode);
        return Result::choose($result);
    }
}
