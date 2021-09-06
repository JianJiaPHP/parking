<?php


namespace App\Services;


use App\Exceptions\ApiException;
use App\Models\Coupon;

class CouponService
{

    /**
     * 列表
     * @param $name //优惠券名称
     * @param $type //类型
     * @param $minPrice //优惠券金额（小）
     * @param $maxPrice //优惠券金额（大）
     * @param $startTime //开始时间
     * @param $endTime //结束时间
     * @param $status //状态
     * @param $limit //每页条数
     * @return mixed
     * author hy
     */
    public function adminList($name, $type, $minPrice, $maxPrice, $startTime, $endTime, $status, $limit)
    {
        return Coupon::adminList($name, $type, $minPrice, $maxPrice, $startTime, $endTime, $status, $limit);
    }

    /**
     * @param $name //优惠券名称
     * @param $type //类型
     * @param $minPrice //优惠券金额（小）
     * @param $maxPrice //优惠券金额（大）
     * @param $limit //每页条数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author hy
     */
    public function giveList($name, $type, $minPrice, $maxPrice,$limit)
    {
        return Coupon::giveList($name, $type, $minPrice, $maxPrice, $limit);
    }

    /**
     * 添加
     * @param $data
     * @return mixed
     * author hy
     */
    public function create($data)
    {
        return Coupon::create($data);
    }

    /**
     * 更新
     * @param $id
     * @param $data
     * @return mixed
     * author hy
     */
    public function updateById($id, $data)
    {
        return Coupon::updateById($id, $data);
    }

    /**
     * 更改状态
     * @param $id
     * @param $status
     * @return mixed
     * author hy
     */
    public function status($id, $status)
    {
        $data = Coupon::getWhereOne(['id' => $id]);
        if ($data->status = $status) {
            new ApiException("该条数据已被操作过了");
        }
        return Coupon::updateById($id, ['status' => $status]);
    }
}
