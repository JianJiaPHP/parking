<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Services\AdminMenuService;
use App\Utils\Result;

class AdminMenuController extends Controller
{
    private $adminMenuService;

    /**
     * AdminMenuController constructor.
     */
    public function __construct(AdminMenuService $adminMenuService)
    {
        $this->adminMenuService = $adminMenuService;
    }


    /**
     * 菜单列表
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function index()
    {
        $data = $this->adminMenuService->listAll();

        return Result::success($data);
    }

    /**
     * 菜单添加
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function store()
    {
        $params = request()->all();

        $result = $this->adminMenuService->create($params);

        return Result::choose($result);
    }

    /**
     * 菜单更新
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function update($id)
    {
        $params = request()->all();

        $result = $this->adminMenuService->update($id,$params);

        return Result::choose($result);
    }

    /**
     * 删除菜单
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function destroy($id)
    {
        $result = $this->adminMenuService->delete($id);

        return Result::choose($result);
    }

    /**
     * 所有列表
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function listAll()
    {
        $data = $this->adminMenuService->listTop();

        return Result::success($data);
    }

}
