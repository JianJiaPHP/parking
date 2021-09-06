<?php


namespace App\Services;


use App\Models\AdminMenu;
use App\Models\AdminRoleMenu;
use Illuminate\Support\Facades\DB;

class AdminMenuService
{

    /**
     * 所有菜单
     * @return array
     * author hy
     */
    public function listAll()
    {
        return AdminMenu::getAll();
    }

    /**
     * 添加菜单
     * @param $params
     * @return bool
     * author hy
     */
    public function create($params)
    {
        $result = AdminMenu::create([
            'parent_id' => $params['parent_id'],
            'path' => $params['path'],
            'icon' => $params['icon'],
            'name' => $params['name'],
            'sort' => $params['sort'],
            'is_hidden' => $params['is_hidden'],
        ]);
        // 删除缓存
        AdminMenu::delAdminMenuAll();
        return (bool)$result;
    }

    /**
     * 更新菜单
     * @param $id
     * @param $params
     * @return bool
     * author hy
     */
    public function update($id, $params)
    {
        $result = AdminMenu::updateById($id, [
            'parent_id' => $params['parent_id'],
            'path' => $params['path'],
            'icon' => $params['icon'],
            'name' => $params['name'],
            'sort' => $params['sort'],
            'is_hidden' => $params['is_hidden'],
        ]);
        // 删除缓存
        AdminMenu::delAdminMenuAll();

        return (bool)$result;
    }

    /**
     * 删除菜单
     * @param $id
     * @return bool
     * @throws \Exception
     * author hy
     */
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            AdminMenu::deleteById($id);

            AdminRoleMenu::deleteByWhere(['menu_id' => $id]);

            // 删除缓存
            AdminMenu::delAdminMenuAll();
            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 有顶级的所有菜单
     * @return array
     * author hy
     */
    public function listTop()
    {
        $data = AdminMenu::getAll();

        array_push($data, [
            'parent_id' => 0,
            'name' => '顶级',
            'id' => 0
        ]);

        return $data;
    }

    /**
     * 获取管理员菜单
     * @return mixed
     * author hy
     */
    public function getMenu()
    {
        $uid = auth('admin')->id();
        return AdminMenu::getNav($uid);
    }
}
