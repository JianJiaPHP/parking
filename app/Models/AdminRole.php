<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;


class AdminRole extends Model
{
    protected $table = 'admin_role';
    protected $fillable = ['id', 'name', 'desc', 'created_at', 'updated_at'];


    /**
     * 关联角色菜单
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * author hy
     */
    public function menus()
    {
        return $this->hasMany(AdminRoleMenu::class, "role_id", "id");
    }

    /**
     * 关联角色资源
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * author hy
     */
    public function resources()
    {
        return $this->hasMany(AdminRoleResource::class, "role_id", "id");
    }

    /**
     * 根据管理员获取菜单和资源
     * @param $administratorId
     * @return array
     * author hy
     */
    public static function getRoleByAdministratorId($administratorId)
    {
        // 获取管理员的角色ID
        $ids = Administrator::query()->where('id', $administratorId)->value('role_ids');
        // 所有的菜单和资源
        $roles = self::query()->whereIn('id', explode(',', $ids))
            ->get(['menu_ids', 'resource_ids'])->toArray();
        // ,连接所有的角色的菜单
        $menuIds = implode(',', array_column($roles, 'menu_ids'));
        // 去重后的菜单
        $menuIds = implode(',', array_unique(explode(',', $menuIds)));

        // ,连接所有的角色的资源
        $resourceIds = implode(',', array_column($roles, 'resource_ids'));
        // 去重后的资源
        $resourceIds = implode(',', (explode(',', $resourceIds)));

        return [
            'menu_ids' => $menuIds,
            'resource_ids' => $resourceIds,
        ];
    }

    /**
     * 列表
     * @param $keyword
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author hy
     */
    public static function list($keyword, $limit)
    {
        $data = self::query()
            ->with(['menus', 'resources'])->orderBy('id', 'desc');

        $data->when($keyword, function ($query, $keyword) {
            return $query->where('name', 'like', "%$keyword%")
                ->orWhere('desc', 'like', "%$keyword%");
        });

        return $data->paginate($limit);
    }

    /**
     * 根据ID更新
     * @param $id
     * @param $params
     * @return int
     * author hy
     */
    public static function updateById($id, $params)
    {
        return self::query()->where('id', $id)->update($params);
    }

    /**
     * 获取所有
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * author hy
     */
    public static function getAll()
    {
       return AdminRole::query()
            ->orderBy('id', 'desc')
            ->select('id', 'name')
            ->get();
    }

}
