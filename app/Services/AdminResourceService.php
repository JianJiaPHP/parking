<?php


namespace App\Services;


use App\Models\AdminResource;
use App\Models\AdminRoleResource;
use Illuminate\Support\Facades\DB;

class AdminResourceService
{

    /**
     * 列表
     * @param $name
     * @param $limit
     * @return mixed
     * author hy
     */
    public function list($name, $limit)
    {
        return AdminResource::list($name, $limit);
    }

    /**
     * 添加
     * @param $params
     * @return mixed
     * author hy
     */
    public function create($params): void
    {
        AdminResource::create([
            'name' => $params['name'],
            'http_method' => $params['http_method'],
            'url' => $params['url'],
        ]);
        // 删除所有的缓存
        AdminResource::delAdminResourceAll();
    }

    /**
     * 更新
     * @param $id
     * @param $params
     * @return mixed
     * author hy
     */
    public function update($id, $params)
    {
        $result = AdminResource::updateById($id, [
            'name' => $params['name'],
            'http_method' => $params['http_method'],
            'url' => $params['url'],
        ]);
        // 删除所有的缓存
        AdminResource::delAdminResourceAll();
        return $result;
    }

    /**
     * 删除
     * @param $id
     * @return mixed
     * author hy
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            AdminResource::deleteById($id);
            AdminRoleResource::deleteByWhere(['resource_id' => $id]);

            // 删除所有的缓存
            AdminResource::delAdminResourceAll();
            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 获取所有
     * @return mixed
     * author hy
     */
    public function all()
    {
        return AdminResource::getAll();
    }
}
