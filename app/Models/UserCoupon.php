<?php


namespace App\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 用户优惠券
 * Class UserCoupon
 * @package App\Models
 */
class UserCoupon extends Model
{
    protected $table = "user_coupon";
    protected $fillable = ['user_id', 'coupon_id', 'name', 'type', 'range', 'price', 'start_time', 'end_time', 'status', 'source',
        'created_at', 'updated_at'];


    /**
     * 查询用户可用优惠券
     * @param $userId //用户ID
     * @param $price //当前金额
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getAvailable($userId, $price)
    {
        $now = Carbon::now()->toDateString();
        return self::query()->where('user_id', $userId)
            ->where('status', 0)
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->where(function (Builder $query) use ($price) {
                $query->where(function (Builder $q) use ($price) {//满减券 免费
                    $q->where('type', 1)
                        ->orWhere('type', 2)
                        ->where('range', '<=', $price);
                })->orWhere('type', 0);
            })
            ->get();
    }

    /**
     * 根据ID获取一条数据
     * @param $id
     * @return Builder|Model|object|null
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getOneById($id)
    {
        return self::query()->where('id', $id)->first();
    }

    /**
     * 获取用户的优惠券
     * @param $userId //用户ID
     * @param $type //0=未使用 1=已使用 2=已过期
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getUserCoupon($userId, $type)
    {
        $data = self::query()->where('user_id', $userId);
        if ($type == 0) {
            $data = $data->where('status', 0);
        }
        if ($type == 1) {
            $data = $data->where('status', 1);
        }
        if ($type == 2) {
            $data = $data->where('end_time', '<', Carbon::now()->toDateString());
        }

        return $data->orderByDesc('id')->paginate(10);
    }


    /**
     * 根据条件获取数量
     * @param $where
     * @return int
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getCountByWhere($where)
    {
        return self::query()->where($where)->count();
    }

    /**
     * 根据条件获取
     * @param $where
     * @return Builder[]|\Illuminate\Database\Eloquent\Collection
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getByWhere($where)
    {
        return self::query()->where($where)->get();
    }

    /**
     * 根据ids更新
     * @param $ids
     * @param $data
     * @return int
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function updateByIds($ids, $data)
    {
        return self::query()->whereIn('id', $ids)->update($data);
    }

    /**
     * 根据id更新
     * @param $id
     * @param $data
     * @return int
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function updateById($id, $data)
    {
        return self::query()->where('id', $id)->update($data);
    }
}
