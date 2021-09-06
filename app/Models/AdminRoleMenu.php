<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

/**
 * 角色菜单
 * Class AdminRoleMenu
 * @package App\Models
 */
class AdminRoleMenu extends Model
{
    protected $table = "admin_role_menu";

    public $timestamps = false;

    protected $fillable = ['role_id', 'menu_id', 'created_at'];

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
