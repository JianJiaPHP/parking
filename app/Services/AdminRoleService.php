<?php


namespace App\Services;


use App\Exceptions\ApiException;
use App\Models\AdminMenu;
use App\Models\AdminResource;
use App\Models\AdminRole;
use App\Models\AdminRoleAdministrator;
use App\Models\AdminRoleMenu;
use App\Models\AdminRoleResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AdminRoleService
{

    /**
     * 列表
     * @param $keyword
     * @param $limit
     * @return mixed
     * author hy
     */
    public function list($keyword, $limit)
    {
        return AdminRole::list($keyword, $limit);
    }

    /**
     * 添加
     * @param $params
     * @return mixed
     * author hy
     */
    public function create($params)
    {
        $now = Carbon::now()->toDateTimeString();
        DB::beginTransaction();
        try {
            $role = AdminRole::create([
                'name' => $params['name'],
                'desc' => $params['description'],
            ]);

            if (!empty($params['menus'])) {
                $menuData = [];
                $menus = explode(',', $params['menus']);
                foreach ($menus as $v) {
                    $menuData[] = [
                        'menu_id' => $v,
                        'role_id' => $role->id,
                        'created_at' => $now
                    ];
                }
                AdminRoleMenu::insert($menuData);

            }

            if (!empty($params['resources'])) {
                $resourcesData = [];
                $resources = explode(',', $params['resources']);
                foreach ($resources as $v) {
                    $resourcesData[] = [
                        'resource_id' => $v,
                        'role_id' => $role->id,
                        'created_at' => $now
                    ];
                }
                AdminRoleResource::insert($resourcesData);
            }

            DB::commit();
            return true;

        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
            DB::rollback();
            return false;
        }
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
        $now = Carbon::now()->toDateTimeString();
        DB::beginTransaction();
        try {
            AdminRole::query()->where('id', $id)->update([
                'name' => $params['name'],
                'desc' => $params['description'],
            ]);

            // 删除角色资源
            AdminRoleResource::deleteByWhere(['role_id' => $id]);

            // 删除角色菜单
            AdminRoleMenu::deleteByWhere(['role_id' => $id]);

            // 添加角色菜单
            if (!empty($params['menus'])) {
                $menuData = [];
                $menus = explode(',', $params['menus']);
                foreach ($menus as $v) {
                    $menuData[] = [
                        'menu_id' => $v,
                        'role_id' => $id,
                        'created_at' => $now
                    ];
                }
                AdminRoleMenu::insert($menuData);

            }

            // 添加角色资源
            if (!empty($params['resources'])) {
                $resourcesData = [];
                $resources = explode(',', $params['resources']);
                foreach ($resources as $v) {
                    $resourcesData[] = [
                        'resource_id' => $v,
                        'role_id' => $id,
                        'created_at' => $now
                    ];
                }
                AdminRoleResource::insert($resourcesData);

            }
            // 和此角色有关的管理员ID
            $administratorIds = AdminRoleAdministrator::getAdministratorIdByRoleId($id);
            foreach ($administratorIds as $v) {
                // 删除资源缓存
                AdminResource::delAdminResourceByAdministratorId($v);
                // 删除菜单缓存
                AdminMenu::delAdminByAdministratorId($v);
            }

            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 删除
     * @param $id
     * @return mixed
     * author hy
     * @throws ApiException
     */
    public function delete($id)
    {
        $exist = AdminRoleAdministrator::getOneByWhere(['role_id' => $id]);
        if ($exist) {
            throw new ApiException("该角色下还有管理员不能删除");
        }

        DB::beginTransaction();
        try {

            AdminRole::destroy($id);

            AdminRoleResource::deleteByWhere(['role_id' => $id]);

            AdminRoleMenu::deleteByWhere(['role_id' => $id]);

            // 和此角色有关的管理员ID
            $administratorIds = AdminRoleAdministrator::getAdministratorIdByRoleId($id);
            foreach ($administratorIds as $v) {
                // 删除资源缓存
                AdminResource::delAdminResourceByAdministratorId($v);
                // 删除菜单缓存
                AdminMenu::delAdminByAdministratorId($v);
            }

            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 所有角色
     * @return mixed
     * author hy
     */
    public function all()
    {
        return AdminRole::getAll();
    }
}
