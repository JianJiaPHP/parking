<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Models\Administrator;
use App\Models\AdminMenu;
use App\Models\AdminResource;
use App\Models\AdminRoleAdministrator;
use App\Services\AdministratorService;
use App\Utils\Result;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdministratorController extends Controller
{
    private $administratorService;

    /**
     * AdministratorController constructor.
     */
    public function __construct(AdministratorService $administratorService)
    {
        $this->administratorService = $administratorService;
    }


    /**
     * 管理员列表
     * @param Request $request
     * @return JsonResponse
     * author gzy
     */
    public function index(Request $request): JsonResponse
    {
        $account = $request->query('keyword', '');
        $limit = $request->query('limit', 10);
        $list = $this->administratorService->list($account, $limit);
        return Result::success($list);
    }

    /**
     * 添加管理员
     * @return JsonResponse
     * @author Aii
     */
    public function store()
    {
        $params = request()->all();

        $result = $this->administratorService->add($params);

        return Result::choose($result);

    }

    /**
     * 管理更新
     * @param $id
     * @return JsonResponse
     * @author Aii
     */
    public function update($id)
    {
        $params = request()->all();
        $result = $this->administratorService->update($id, $params);

        return Result::choose($result);
    }

    /**
     * 管理员删除
     * @param $id
     * @return JsonResponse
     * @author Aii
     */
    public function destroy($id)
    {
        $result = $this->administratorService->destroy($id);

        return Result::choose($result);
    }


}
