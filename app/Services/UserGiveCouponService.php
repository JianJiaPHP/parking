<?php


namespace App\Services;


use App\Models\UserGiveCoupon;

/**
 * 用户优惠券赠送记录
 * Class UserGiveCouponService
 * @package App\Services
 */
class UserGiveCouponService
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
        return UserGiveCoupon::adminList($keyword, $type, $limit);
    }


}
