<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Models\AdminMenu;
use App\Models\AdminResource;
use App\Models\AdminRole;
use App\Models\AdminRoleAdministrator;
use App\Models\AdminRoleMenu;
use App\Models\AdminRoleResource;
use App\Services\AdminRoleService;
use App\Utils\Result;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    private $adminRoleService;

    /**
     * RoleController constructor.
     */
    public function __construct(AdminRoleService $adminRoleService)
    {
        $this->adminRoleService = $adminRoleService;
    }


    /**
     * 角色列表
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function index()
    {
        $params = request()->all();
        $keyword = $params['name'];
        $limit = $params['limit'];
        $data = $this->adminRoleService->list($keyword, $limit);
        return Result::success($data);
    }

    /**
     * 角色添加
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function store()
    {
        $params = request()->all();
        $result = $this->adminRoleService->create($params);
        return Result::choose($result);
    }


    /**
     * 角色更新
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function update($id)
    {
        $params = request()->all();
        $result = $this->adminRoleService->update($id, $params);
        return Result::choose($result);
    }

    /**
     * 角色删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function destroy($id)
    {
        $result = $this->adminRoleService->delete($id);

        return Result::choose($result);
    }

    /**
     * 获取所有角色
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     */
    public function getAll()
    {
        $data = $this->adminRoleService->all();
        return Result::success($data);
    }

}
