<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Services\UserCouponService;
use App\Services\UserService;
use App\Utils\Result;
use Illuminate\Http\Request;

class UserController extends Controller
{

    private $service;
    private $userCouponService;

    /**
     * UserController constructor.
     */
    public function __construct(UserService $service, UserCouponService $couponService)
    {
        $this->service = $service;
        $this->userCouponService = $couponService;
    }

    /**
     * 后台列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function list(Request $request)
    {
        $params = $request->all();

        $data = $this->service->adminList(
            $params['phone'],
            $params['min_parking'],
            $params['max_parking'],
            $params['last_parking_start'] ?? '',
            $params['last_parking_end'] ?? '',
            $params['limit'] ?? 10
        );

        return Result::success($data);
    }

    /**
     * 赠送优惠券
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     * author gzy
     */
    public function give(Request $request)
    {
        $params = $request->all();

        $result = $this->userCouponService->give($params['user_ids'], $params['coupon_ids'], $params['number']);

        return Result::choose($result);
    }
}
