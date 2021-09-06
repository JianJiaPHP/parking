<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

/**
 * 管理员角色
 * Class AdminRoleAdministrator
 * @package App\Models
 */
class AdminRoleAdministrator extends Model
{
    protected $table = 'admin_role_administrator';
    protected $fillable = ['administrator_id', 'role_id', 'created_at'];

    public $timestamps = false;

    private static $KEY = "parking:admin_role_administrator";

    /**
     * 根据管理员获取角色
     * @param $administratorId
     * @return array|mixed
     * author gzy
     */
    public static function getRoleIdsByAdministratorId($administratorId)
    {
        $key = self::$KEY . ":" . "administrator:" . $administratorId;
        $data = Redis::get($key);
        if (!$data) {
            $roleIds = self::query()->where('administrator_id', $administratorId)
                ->pluck('role_id')->toArray();
            Redis::set($key, json_encode($roleIds));

            return $roleIds;
        }
        return json_decode($data, true);
    }

    /**
     * 根据管理员删除缓存
     * @param $administratorId
     * @return mixed
     * author gzy
     */
    public static function delRoleIdsByAdministratorId($administratorId)
    {
        $key = self::$KEY . ":" . "administrator:" . $administratorId;
        return Redis::del($key);
    }

    /**
     * 根据条件删除
     * @param $where
     * @return mixed
     * author gzy
     */
    public static function deleteByWhere($where)
    {
        return self::query()->where($where)->delete();
    }

    /**
     * 和此角色有关的管理员ID
     * @param $id
     * @return array
     * author gzy
     */
    public static function getAdministratorIdByRoleId($id)
    {
        return self::query()->where('role_id', $id)
            ->pluck('administrator_id')->toArray();
    }

    /**
     * 根据条件获取一条
     * @param $where
     * @return \Illuminate\Database\Eloquent\Builder|Model|object|null
     * author gzy
     */
    public static function getOneByWhere($where)
    {
        return self::query()->where($where)->first();
    }

}
