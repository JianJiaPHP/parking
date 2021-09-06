<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 用户停车
 * Class UserParking
 * @package App\Models
 */
class UserParking extends Model
{
    protected $table = "user_parking";
    protected $fillable = ['no', 'car_number', 'user_id', 'name', 'entry_time', 'departure_time', 'stay_time', 'user_coupon_id', 'discount_amount', 'pay_amount', 'original_price', 'status', 'created_at', 'updated_at'];

    /**
     * 关联用户
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * author gzy
     */
    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    /**
     * 关联用户优惠券
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     * author gzy
     */
    public function userCoupon()
    {
        return $this->hasOne(UserCoupon::class, 'id', 'user_coupon_id');
    }

    /**
     * 后台列表
     * @param $keyword //关键字
     * @param $type //类型
     * @param $limit //每页条数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @author gzy
     */
    public static function adminList($keyword, $type, $limit)
    {
        $data = self::query()->orderByDesc('id')->with(['user:id,phone', 'userCoupon:id,name,type']);
        if (!empty($keyword)) {
            $data = $data->whereHas('user', function (Builder $query) use ($keyword) {
                $query->where('phone', 'like', "%$keyword%");
            })->orWhereHas('userCoupon', function (Builder $query) use ($keyword) {
                $query->where('name', 'like', "%$keyword%");
            });
        }
        if (!is_null($type)) {
            $data = $data->whereHas('userCoupon', function (Builder $query) use ($type) {
                $query->where('type', $type);
            });
        }
        return $data->paginate($limit);
    }


    /**
     * 用户停车记录
     * @param $userId
     * @param $time
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function userList($userId, $time)
    {
        $data = self::query()->orderByDesc('id')->where('user_id', $userId)
            ->where('status', 1);
        if (!empty($time)) {
            $data = $data->whereDate('created_at', $time);
        }
        return $data->select(['id', 'stay_time', 'pay_amount', 'created_at'])
            ->paginate(10);
    }

    /**
     * 根据id获取一条数据
     * @param $id
     * @return Builder|Builder[]|\Illuminate\Database\Eloquent\Collection|Model|null
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getOneById($id)
    {
        return self::query()->with('userCoupon')->find($id);
    }

    /**
     * 根据条件查询一条
     * @param $where
     * @return Builder|Model|object|null
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getOneByWhere($where)
    {
        return self::query()->where($where)->first();
    }

    /**
     * 根据条件更新
     * @param $where
     * @param $data
     * @return int
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function updateByWehre($where, $data)
    {
        return self::query()->where($where)->update($data);
    }


}
