<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * 用户优惠券赠送记录
 * Class UserGiveCoupon
 * @package App\Models
 */
class UserGiveCoupon extends Model
{
    protected $table = "user_give_coupon";
    protected $fillable = ['user_id', 'to_user', 'name', 'number', 'type', 'range', 'price', 'start_time', 'end_time', 'created_at'];
    public $timestamps = false;

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
     * 后台列表
     * @param $keyword //关键字
     * @param $type //类型
     * @param $limit //每页条数
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author gzy
     */
    public static function adminList($keyword, $type, $limit)
    {
        $data = self::query()->orderByDesc('id');
        if (!empty($keyword)) {
            $data = $data->where(function (Builder $q) use ($keyword) {
                $q->where('to_user', 'like', "%$keyword%")
                    ->orWhere('name', 'like', "%$keyword%");
            });
        }
        if (!is_null($type)) {
            $data = $data->where('type', $type);
        }

        return $data->with('user:id,phone')->paginate($limit);
    }

    /**
     * 根据用户ID获取赠送记录
     * @param $userId
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getListByUserId($userId)
    {
        return self::query()->orderByDesc('id')
            ->where('user_id', $userId)
            ->paginate(10);
    }

}
