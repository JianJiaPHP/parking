<?php


namespace App\Services;


use App\Exceptions\ApiException;
use App\Models\Coupon;
use App\Models\User;
use App\Models\UserCoupon;
use App\Models\UserGiveCoupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * 用户优惠券
 * Class UserCouponService
 * @package App\Services
 */
class UserCouponService
{

    /**
     * 后台赠送用户优惠券
     * @param $userIds //用户ID
     * @param $couponIds //优惠券ID
     * @param $number //数量
     * @return bool
     * @throws \Exception
     * author hy
     */
    public function give($userIds, $couponIds, $number)
    {
        $data = [];
        $now = Carbon::now()->toDateTimeString();
        $coupons = Coupon::getkeyById($couponIds);
        foreach ($userIds as $userId) {
            foreach ($couponIds as $couponId) {
                $coupon = $coupons[$couponId];
                $data[] = [
                    'user_id' => $userId,
                    'coupon_id' => $couponId,
                    'name' => $coupon['name'],
                    'type' => $coupon['type'],
                    'range' => $coupon['range'],
                    'price' => $coupon['price'],
                    'start_time' => $coupon['start_time'],
                    'end_time' => $coupon['end_time'],
                    'source' => 0,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
        }

        DB::beginTransaction();
        try {
            for ($i = 0; $i < $number; $i++) {
                UserCoupon::insert($data);
            }
            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }


    /**
     * 用户可用优惠券
     * @param $carNumber
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \App\Exceptions\ApiException
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function available($carNumber)
    {
        $userParkingService = new UserParkingService();
        $carNumberInfo = $userParkingService->carNumber($carNumber);
        $userId = auth('api')->id();
        return UserCoupon::getAvailable($userId, $carNumberInfo['price']);
    }

    /**
     * 我的优惠券
     * @param $type //0=未使用 1=已使用 2=已过期
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function my($type)
    {
        $userId = auth('api')->id();
        return UserCoupon::getUserCoupon($userId, $type);
    }

    /**
     * 赠送记录
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function giveList()
    {
        $userId = auth('api')->id();
        return UserGiveCoupon::getListByUserId($userId);
    }


    /**
     * 赠送优惠券
     * @param $couponId
     * @param $phone
     * @param $number
     * @return bool
     * @throws ApiException
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function giveUser($couponId, $phone, $number)
    {
        $coupon = UserCoupon::getOneById($couponId);
        if (!$coupon) {
            throw new ApiException("该优惠券不存在");
        }

        $user = User::getOneByWhere(['phone' => $phone]);
        if (!$user) {
            throw new ApiException('该手机号暂未注册');
        }
        $userId = auth('api')->id();
        $coupons = UserCoupon::getByWhere([
            'user_id' => $userId,
            'coupon_id' => $coupon->coupon_id,
        ]);
        if (count($coupons) < $number) {
            throw new ApiException('超出持有数量');
        }
        $ids = array_column($coupons->toArray(), 'id');
        // 需要更改的用户优惠券ID
        $updateIds = array_slice($ids, 0, $number);

        // 赠送数据
        $insertData = [];
        for ($i = 0; $i < $number; $i++) {
            $insertData[] = [
                'user_id' => $user->id,
                'coupon_id' => $coupon->coupon_id,
                'name' => $coupon->name,
                'type' => $coupon->type,
                'range' => $coupon->range,
                'price' => $coupon->price,
                'start_time' => $coupon->start_time,
                'end_time' => $coupon->end_time,
                'source' => 1,
            ];
        }
        DB::beginTransaction();
        try {
            // 用户优惠券
            UserCoupon::insert($insertData);

            //用户优惠券赠送记录
            UserGiveCoupon::create([
                'user_id' => $userId,
                'to_user' => $user->phone,
                'name' => $coupon->name,
                'number' => $number,
                'type' => $coupon->type,
                'range' => $coupon->range,
                'price' => $coupon->price,
                'start_time' => $coupon->start_time,
                'end_time' => $coupon->end_time,
                'created_at' => Carbon::now()->toDateTimeString()
            ]);

            // 更新原来的
            UserCoupon::updateByIds($updateIds, ['status' => 2]);

            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }


}
