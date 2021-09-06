<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\MePwdRequests;
use App\Http\Requests\Admin\MeUpdateRequests;
use App\Models\Administrator;
use App\Models\AdminMenu;
use App\Services\AdministratorService;
use App\Services\AdminMenuService;
use App\Utils\Result;
use Illuminate\Support\Facades\Hash;

class MeController extends Controller
{
    private $administratorService;
    private $adminMenuService;

    /**
     * MeController constructor.
     */
    public function __construct(
        AdministratorService $administratorService,
        AdminMenuService $adminMenuService
    )
    {
        $this->administratorService = $administratorService;
        $this->adminMenuService = $adminMenuService;
    }

    /**
     * 修改密码
     * @param MePwdRequests $mePwdRequests
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/11 下午2:54
     */
    public function updatePwd(MePwdRequests $mePwdRequests)
    {
        $params = $mePwdRequests->validated();
        $result = $this->administratorService->updatePwd($params);

        return Result::choose($result);

    }

    /**
     * 更新个人信息
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function updateInfo()
    {
        $params = request()->all();
        $result = $this->administratorService->updateInfo($params);
        return Result::choose($result);
    }

    /**
     * 获取菜单
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function getNav()
    {
        $data = $this->adminMenuService->getMenu();

        return Result::success($data);
    }

    /**
     * 查看个人信息
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/11 下午12:06
     */
    public function me()
    {
        $data = $this->administratorService->getInfo();
        return Result::success($data);
    }

    /**
     * 退出登录
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/11 下午12:07
     */
    public function logout()
    {
        auth('admin')->logout();

        return Result::success();
    }
}
