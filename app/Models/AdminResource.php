<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

/**
 * 资源
 * Class AdminResource
 * @package App\Models
 */
class AdminResource extends Model
{
    protected $table = "admin_resource";
    protected $fillable = ['name', 'url', 'http_method', 'created_at', 'updated_at'];

    private static $KEY = "parking:admin_resource";


    /**
     * 根据管理员获取资源
     * @return mixed|void
     * author gzy
     */
    public static function getAdminResourceByAdministratorId($administratorId)
    {

        $key = self::$KEY . ":" . "administrator:" . $administratorId;
        $data = Redis::get($key);
        if (!$data) {
            // 所有的角色
            $roleIds = AdminRoleAdministrator::getRoleIdsByAdministratorId($administratorId);
            // 当前管理员的资源ids
            $resourceIds = AdminRoleResource::query()->whereIn('role_id', $roleIds)
                ->pluck('resource_id')->toArray();

            // 根据资源ids获取资源
            $adminResources = AdminResource::query()->whereIn('id', array_unique($resourceIds))
                ->get(['url', 'http_method'])
                ->toArray();

            Redis::set($key, json_encode($adminResources));
            return $adminResources;
        }
        return json_decode($data, true);
    }


    /**
     * 根据管理员id删除资源缓存
     * @param $administratorId
     * @return mixed
     * author gzy
     */
    public static function delAdminResourceByAdministratorId($administratorId)
    {
        $key = self::$KEY . ":" . "administrator:" . $administratorId;
        return Redis::del($key);
    }


    /**
     * 删除所有的资源
     * @return mixed
     * author gzy
     */
    public static function delAdminResourceAll()
    {
        $pattern = self::$KEY . ":" . "administrator:" . "*";
        $keys = Redis::keys($pattern);

        return Redis::del($keys);
    }

    /**
     * 列表
     * @param $name
     * @param $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author hy
     */
    public static function list($name, $limit)
    {
        $data = self::query()->orderBy('id', 'desc');

        if ($name) {
            $data = $data->where('name', 'like', "%$name%");
        }
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
     * 根据ID删除
     * @param $id
     * @return mixed
     * author hy
     */
    public static function deleteById($id)
    {
        return self::query()->where('id', $id)->delete();
    }

    /**
     * 查询所有
     * @return array
     * author hy
     */
    public static function getAll()
    {
        return AdminResource::query()
            ->get(['id', 'name', 'url', 'http_method'])
            ->toArray();
    }

}
