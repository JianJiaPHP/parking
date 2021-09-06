<?php


namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use App\Services\CouponService;
use App\Services\UserGiveCouponService;
use App\Services\UserParkingService;
use App\Utils\Result;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CouponController extends Controller
{

    private $couponService;
    private $userGiveCouponService;
    private $userParkingService;


    /**
     * CouponController constructor.
     */
    public function __construct(CouponService $couponService,
                                UserGiveCouponService $userGiveCouponService,
                                UserParkingService $userParkingService
    )
    {
        $this->couponService = $couponService;
        $this->userGiveCouponService = $userGiveCouponService;
        $this->userParkingService = $userParkingService;
    }

    /**
     * 列表
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function list(Request $request)
    {
        $params = $request->query();

        $data = $this->couponService->adminList(
            $params['name'],
            $params['type'],
            $params['min_price'],
            $params['max_price'],
            $params['start_time'] ?? '',
            $params['end_time'] ??'',
            $params['status'],
            $params['limit'] ?? 10
        );

        return Result::success($data);
    }


    /**
     * 添加
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function create(Request $request)
    {
        $params = $request->all();

        $data = [
            'name' => $params['name'],
            'type' => $params['type'],
            'range' => $params['range'] ?? 0,
            'price' => $params['price'] ?? 0,
            'start_time' => $params['start_time'],
            'end_time' => $params['end_time'],
            'status' => $params['status'],
        ];
        $result = $this->couponService->create($data);
        return Result::choose($result);
    }

    /**
     * 更新
     * @param $id
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function update($id, Request $request)
    {
        $params = $request->all();

        $data = [
            'name' => $params['name'],
            'type' => $params['type'],
            'range' => $params['range'] ?? 0,
            'price' => $params['price'] ?? 0,
            'start_time' => $params['start_time'],
            'end_time' => $params['end_time'],
            'status' => $params['status'],
        ];
        $result = $this->couponService->updateById($id, $data);
        return Result::choose($result);
    }

    /**
     * 更改状态
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function status(Request $request)
    {
        $params = $request->all();

        $result = $this->couponService->status($params['id'], $params['status']);
        return Result::choose($result);
    }

    /**
     * 赠送优惠券列表
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function give()
    {
        $params = \request()->all();

        $data = $this->couponService->giveList(
            $params['name'],
            $params['type'],
            $params['min_price'],
            $params['max_price'],
            $params['limit'] ?? 10
        );
        return Result::success($data);
    }


    /**
     * 互赠记录
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function mutualGift()
    {
        $params = \request()->all();

        $data = $this->userGiveCouponService->adminList(
            $params['keyword'],
            $params['type'],
            $params['limit'] ?? 10
        );
        return Result::success($data);
    }

    /**
     * 优惠券使用记录
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function use()
    {
        $params = \request()->all();

        $data = $this->userParkingService->adminList(
            $params['keyword'],
            $params['type'],
            $params['limit'] ?? 10
        );
        return Result::success($data);

    }
}
