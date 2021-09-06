<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Services\UserCouponService;
use App\Utils\Result;

class CouponController extends Controller
{

    private $userCouponService;

    /**
     * CouponController constructor.
     */
    public function __construct(UserCouponService $userCouponService)
    {
        $this->userCouponService = $userCouponService;
    }


    /**
     * 用户可用优惠券
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function available()
    {
        $carNumber = request()->query('car_number', '');

        $data = $this->userCouponService->available($carNumber);

        return Result::success($data);
    }

    /**
     * 我的优惠券
     * @return \Illuminate\Http\JsonResponse
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function my()
    {
        //0=未使用 1=已使用 2=已过期
        $type = request()->query('type', 0);

        $data = $this->userCouponService->my($type);

        return Result::success($data);
    }

    /**
     * 赠送记录
     * @return \Illuminate\Http\JsonResponse
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function give()
    {
        $data = $this->userCouponService->giveList();

        return Result::success($data);
    }

    /**
     * 赠送优惠券
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function giveUser()
    {
        // 优惠券ID
        $couponId = request()->post('coupon', 0);
        // 手机号
        $phone = request()->post('phone', '');
        // 赠送数量
        $number = request()->post('number', 0);

        $result = $this->userCouponService->giveUser($couponId, $phone, $number);

        return Result::choose($result);
    }

}


