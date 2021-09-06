<?php


namespace App\Services;


use App\Exceptions\ApiException;
use App\Models\UserCoupon;
use App\Models\UserParking;
use App\Utils\Parking;
use App\Utils\WecahtPay;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserParkingService
{

    /**
     * 后台列表
     * @param $keyword //关键字
     * @param $type //类型
     * @param $limit //每页条数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author hy
     */
    public function adminList($keyword, $type, $limit)
    {
        return UserParking::adminList($keyword, $type, $limit);
    }

    /**
     * 根据车牌号获取信息
     * @param $carNumber
     * @return array
     * @throws \App\Exceptions\ApiException
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function carNumber($carNumber)
    {
        return Parking::getCarNumberPrice($carNumber);
    }

    /**
     * 选择优惠券后
     * @param $carNumber // 车牌号
     * @param $couponId //优惠券ID
     * @return array
     * @throws ApiException
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function chooseCoupon($carNumber, $couponId)
    {
        $carNumberInfo = Parking::getCarNumberPrice($carNumber);

        $coupon = UserCoupon::getOneById($couponId);

        $price = $carNumberInfo['price'];
        // 实付金额
        $carNumberInfo['pay_price'] = $price;
        // 优惠券名称
        $carNumberInfo['coupon_name'] = '';
        // 优惠金额(免费就是全优惠咯否则就是优惠金额)
        $carNumberInfo['coupon_price'] = 0;

        if ($coupon) {
            //(0=无门槛 1=满减券 2= 免费)
            $type = $coupon->type;
            if ($type == 0) {
                // 当前价格-优惠价格
                $price = bcsub($carNumberInfo['price'], $coupon['price'], 2);
            } else if ($type == 1) {
                // 当前价格-优惠价格
                $price = bcsub($carNumberInfo['price'], $coupon['price'], 2);
            } else if ($type == 2) {
                // 免费
                $price = 0;
            }
            // 实付金额
            $carNumberInfo['pay_price'] = $price;
            // 优惠券名称
            $carNumberInfo['coupon_name'] = $coupon->name;
            // 优惠金额(免费就是全优惠咯否则就是优惠金额)
            $carNumberInfo['coupon_price'] = $coupon->type == 2 ? $carNumberInfo['price'] : $coupon->price;

        }


        return $carNumberInfo;
    }


    /**
     * 拉取支付
     * @param $carNumber
     * @param $couponId
     * @return array|\EasyWeChat\Kernel\Support\Collection|object|\Psr\Http\Message\ResponseInterface|string
     * @throws ApiException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function pay($carNumber, $couponId)
    {
        $user = auth('api')->user();
        $info = $this->chooseCoupon($carNumber, $couponId);
        // 订单编号
        $no = str_replace("-", "", (string)Str::uuid());
        if ($info['pay_price'] == 0) {
            UserParking::create([
                'car_number' => $carNumber, //车牌号
                'no' => $no,
                'user_id' => $user['id'], //用户ID
                'entry_time' => $info['start_time'],//进场时间
                'departure_time' => $info['end_time'],//离场时间
                'stay_time' => Carbon::parse($info['end_time'])->diffInSeconds(Carbon::parse($info['start_time'])),//停留时间(秒)
                'user_coupon_id' => $couponId ?? 0,//优惠券ID
                'discount_amount' => $info['coupon_price'],//优惠金额
                'pay_amount' => $info['pay_price'],//实付金额
                'original_price' => $info['price'],//原价
                'status' => 1,//状态
            ]);
            // 修改优惠券状态
            if (!empty($couponId)) {
                UserCoupon::updateById($couponId, ['status' => 1]);
            }
            return [
                'is_pay' => false,
            ];
        }


        $result = WecahtPay::pay("停车缴费",
            $no, $info['pay_price'],
            route('callback'),
            $user['openid']
        );
        if (!$result) {
            throw new ApiException("拉取支付失败，请重试");
        }

        UserParking::create([
            'car_number' => $carNumber, //车牌号
            'no' => $no,
            'user_id' => $user['id'], //用户ID
            'entry_time' => $info['start_time'],//进场时间
            'departure_time' => $info['end_time'],//离场时间
            'stay_time' => Carbon::parse($info['end_time'])->diffInSeconds(Carbon::parse($info['start_time'])),//停留时间(秒)
            'user_coupon_id' => $couponId,//优惠券ID
            'discount_amount' => $info['coupon_price'],//优惠金额
            'pay_amount' => $info['pay_price'],//实付金额
            'original_price' => $info['price'],//原价
        ]);
        return [
            'is_pay' => true,
            'data' => $result,
        ];
    }


    /**
     * 用户停车记录
     * @param $time
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function list($time)
    {
        $userId = auth('api')->id();
        return UserParking::userList($userId, $time);
    }

    /**
     * 查看详情
     * @param $id
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model
     * @throws ApiException
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function show($id)
    {
        $data = UserParking::getOneById($id);
        if (!$data) {
            throw new ApiException();
        }

        return $data;
    }
}
