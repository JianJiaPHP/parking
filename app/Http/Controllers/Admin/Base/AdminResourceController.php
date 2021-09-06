<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Models\AdminResource;
use App\Models\AdminRoleResource;
use App\Services\AdminResourceService;
use App\Utils\Result;
use Illuminate\Support\Facades\DB;

class AdminResourceController extends Controller
{
    private $adminResourceService;

    /**
     * AdminResourceController constructor.
     */
    public function __construct(AdminResourceService $adminResourceService)
    {
        $this->adminResourceService = $adminResourceService;
    }


    /**
     * 列表
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function index()
    {
        $name = request()->query('name');
        $limit = request()->query('limit', 10);
        $data = $this->adminResourceService->list($name, $limit);

        return Result::success($data);
    }

    /**
     * 添加
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function store()
    {
        $params = request()->all();
        $this->adminResourceService->create($params);

        return Result::success();
    }

    /**
     * 更新
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function update($id)
    {
        $params = request()->all();

        $result = $this->adminResourceService->update($id, $params);

        return Result::choose($result);
    }

    /**
     * 删除
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function destroy($id)
    {
        $result = $this->adminResourceService->delete($id);

        return Result::choose($result);
    }

    /**
     * 获取所有的资源
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function all()
    {
        $data = $this->adminResourceService->all();

        return Result::success($data);
    }
}
