<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * 用户
 * Class User
 * @package App\Models
 */
class User extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = "user";
    protected $fillable = ['nickname', 'avatar', 'phone', 'openid', 'unionid', 'give_away_count', 'get_gift_count', 'total_parking', 'total_price', 'last_parking',
        'created_at', 'updated_at'];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 关联停车
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * author hy
     */
    public function userParking()
    {
        return $this->hasMany(UserParking::class, 'user_id', 'id');
    }


    /**
     * 关联用户优惠券
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * author hy
     */
    public function userCoupon()
    {
        return $this->hasMany(UserCoupon::class, 'user_id', 'id');
    }

    /**
     * 后台列表
     * @param $phone //手机号码
     * @param $minParking //最小停车时间
     * @param $maxParking //最大停车时间
     * @param $lastParkingStart //开始停车时间
     * @param $lastParkingEnd //结束停车时间
     * @param $limit //每页显示
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author hy
     */
    public static function adminList($phone, $minParking, $maxParking, $lastParkingStart, $lastParkingEnd, $limit)
    {
        $data = self::query()->orderByDesc("id");
        if (!empty($phone)) {
            $data = $data->where('phone', 'like', "%$phone%");
        }
        if (!empty($minParking)) {
            $data = $data->where('total_parking', '>=', $minParking * 60 * 60);
        }
        if (!empty($maxParking)) {
            $data = $data->where('total_parking', '<=', $maxParking * 60 * 60);
        }
        if (!empty($lastParkingStart)) {
            $data = $data->where('last_parking', '>', $lastParkingStart);
        }
        if (!empty($lastParkingEnd)) {
            $data = $data->where('last_parking', '<', $lastParkingEnd);
        }

        return $data->with('userParking:car_number,user_id')
            ->withCount(['userCoupon' => function (Builder $query) {
                $query->where('status', 0);
            }])->paginate($limit);
    }


    /**
     * 根据条件查询
     * @param $where
     * @return Builder|\Illuminate\Database\Eloquent\Model|object|null
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getOneByWhere($where)
    {
        return self::query()->where($where)->first();
    }


    /**
     * 根据ID更新
     * @param $id
     * @param $data
     * @return int
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function updateById($id, $data)
    {
        return self::query()->where('id', $id)->update($data);
    }


}
