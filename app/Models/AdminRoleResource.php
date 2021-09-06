<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * 角色资源
 * Class AdminRoleResource
 * @package App\Models
 */
class AdminRoleResource extends Model
{
    protected $table = "admin_role_resource";

    public $timestamps = false;

    protected $fillable = ['role_id', 'resource_id', 'created_at'];

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
}
