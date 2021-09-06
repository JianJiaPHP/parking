<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Services\UserParkingService;
use App\Utils\Result;

class ParkingController extends Controller
{

    private $userParkingService;

    /**
     * ParkingController constructor.
     */
    public function __construct(UserParkingService $userParkingService)
    {
        $this->userParkingService = $userParkingService;
    }

    /**
     * 根据车牌号获取信息
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function carNumber()
    {
        $carNumber = request()->get('car_number', '');

        $data = $this->userParkingService->carNumber($carNumber);

        return Result::success($data);
    }

    /**
     * 选择优惠券后
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function chooseCoupon()
    {
        // 车牌号
        $carNumber = request()->query('car_number', '');
        // 优惠券ID
        $couponId = request()->query('coupon', '');

        $data = $this->userParkingService->chooseCoupon($carNumber, $couponId);

        return Result::success($data);
    }


    /**
     * 支付
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function pay()
    {
        // 车牌号
        $carNumber = request()->get('car_number', '');
        // 优惠券ID
        $couponId = request()->get('coupon', '');
        $data = $this->userParkingService->pay($carNumber, $couponId);

        return Result::success($data);

    }

    /**
     * 用户停车记录
     * @return \Illuminate\Http\JsonResponse
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function list()
    {
        // 时间
        $time = request()->query('time', '');

        $data = $this->userParkingService->list($time);

        return Result::success($data);
    }

    /**
     * 记录详情
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \App\Exceptions\ApiException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function show($id)
    {
        $data = $this->userParkingService->show($id);

        return Result::choose($data);
    }


}
