<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * 优惠券
 * Class Coupon
 * @package App\Models
 */
class Coupon extends Model
{
    protected $table = "coupon";
    protected $fillable = ['name', 'type', 'range', 'price', 'start_time', 'end_time', 'status', 'created_at', 'updated_at'];

    /**
     * 后台列表
     * @param $name //优惠券名称
     * @param $type //类型
     * @param $minPrice //优惠券金额（小）
     * @param $maxPrice //优惠券金额（大）
     * @param $startTime //开始时间
     * @param $endTime //结束时间
     * @param $status //状态
     * @param $limit //每页条数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author hy
     */
    public static function adminList($name, $type, $minPrice, $maxPrice, $startTime, $endTime, $status, $limit)
    {
        $data = self::query()->orderByDesc('id');

        if ($name) {
            $data = $data->where('name', 'like', "%$name%");
        }
        if (!is_null($type)) {
            $data = $data->where('type', $type);
        }
        if ($minPrice) {
            $data = $data->where('price', '>', $minPrice);
        }
        if ($maxPrice) {
            $data = $data->where('price', '<', $maxPrice);
        }
        if ($startTime) {
            $data = $data->where('start_time', '>=', $startTime);
        }
        if ($endTime) {
            $data = $data->where('end_time', '<=', $endTime);
        }

        if (!is_null($status)) {
            $data = $data->where('status', $status);
        }

        return $data->paginate($limit);
    }

    /**
     * 赠送优惠券列表
     * @param $name
     * @param $type
     * @param $minPrice
     * @param $maxPrice
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author hy
     */
    public static function giveList($name, $type, $minPrice, $maxPrice, $limit)
    {
        $data = self::query()->orderByDesc('id');

        if ($name) {
            $data = $data->where('name', 'like', "%$name%");
        }
        if (!is_null($type)) {
            $data = $data->where('type', $type);
        }
        if ($minPrice) {
            $data = $data->where('price', '>', $minPrice);
        }
        if ($maxPrice) {
            $data = $data->where('price', '<', $maxPrice);
        }

        $data = $data->where('status',0)
            ->where('end_time', '>',Carbon::now()->toDateString());


        return $data->paginate($limit);
    }

    /**
     * 根据ID更新
     * @param $id
     * @param $data
     * @return int
     * author hy
     */
    public static function updateById($id, $data)
    {
        return self::query()->where('id', $id)->update($data);
    }

    /**
     * 根据条件获取一条数据
     * @param $where
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     * author hy
     */
    public static function getWhereOne($where)
    {
        return self::query()->where($where)->first();
    }

    /**
     * 获取key为id
     * @param $ids
     * @return array
     * author hy
     */
    public static function getkeyById($ids)
    {
        return Coupon::query()->whereIn('id', $ids)
            ->get()->keyBy('id')->toArray();
    }

}
