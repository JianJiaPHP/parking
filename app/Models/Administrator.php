<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Redis;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Administrator extends Authenticatable implements JWTSubject
{
    use Notifiable;

    protected $table = "administrators";
    protected $fillable = ['account', 'nickname', 'avatar', 'password', 'deleted_at', 'created_at', 'updated_at'];

    protected $hidden = ['password'];

    private static $KEY = "parking:administrator";


    /**
     * 根据ID查询信息
     * @param $id
     * @return array
     * author gzy
     */
    public static function getAdministratorById($id)
    {
        $key = self::$KEY . ":" . $id;
        $data = Redis::hGetAll($key);
        if (!$data) { //缓存中不存在
            $administrator = self::query()->where('id', $id)->first()->toArray();
            Redis::hmset($key, $administrator);
            // 设置过期
            Redis::EXPIRE($key, auth('admin')->factory()->getTTL() * 60);
            return $administrator;
        }
        return $data;
    }

    /**
     * 根据ID删除缓存
     * @param $id
     * @return mixed
     * author gzy
     */
    public static function delAdministratorById($id)
    {
        $key = self::$KEY . ":" . $id;
        return Redis::del($key);
    }

    /**
     * 删除所有
     * @return mixed
     * author gzy
     */
    public static function delAdministratorAll()
    {
        $pattern = self::$KEY . "*";
        $keys = Redis::keys($pattern);
        return Redis::del($keys);
    }

    /**
     * 关联角色
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * author gzy
     */
    public function roles()
    {
        return $this->hasMany(AdminRoleAdministrator::class, "administrator_id", 'id');
    }

    /**
     * 头像拼接连接
     * @param $value
     * @return mixed
     * @author Aii
     */
    public function getAvatarAttribute($value)
    {
        $url = $value;
        $preg = "/^http(s)?:\\/\\/.+/";
        if (!preg_match($preg, $value)) {
            $url = env('APP_URL') . $value;
        }
        return $url;
    }
    // Rest omitted for brevity

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
     * 列表
     * @param $account
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author gzy
     */
    public static function list($account, $limit)
    {
        $data = self::with('roles')
            ->orderBy('created_at', 'desc')
            ->where('id', '>', 1);

        $data->when($account, function ($query, $account) {
            return $query->where('account', 'like', "%$account%");
        });

        return $data->select('id', 'account', 'nickname', 'avatar', 'created_at')
            ->paginate($limit);
    }


    /**
     * 根据条件查询一条
     * @param $where
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|object|null
     * author gzy
     */
    public static function getByWhereOne($where)
    {
        return self::query()->where($where)->first();
    }

    /**
     * 根据ID更新
     * @param $id
     * @param $data
     * @return int
     * author gzy
     */
    public static function updateById($id, $data)
    {
        return self::query()->where('id', $id)->update($data);
    }

}
