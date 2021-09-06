<?php


namespace App\Http\Controllers\Admin\Base;


use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Services\ConfigService;
use App\Utils\Result;

class ConfigController extends Controller
{

    private $configService;

    /**
     * ConfigController constructor.
     */
    public function __construct(ConfigService $configService)
    {
        $this->configService = $configService;
    }


    /**
     * 获取配置信息
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/13 下午3:22
     */
    public function index()
    {
        $data = $this->configService->list('admin');

        return Result::success($data);
    }


    /**
     * 修改配置信息
     * @param $id int 配置id
     * @return \Illuminate\Http\JsonResponse
     * @author Aii
     * @date 2019/12/13 下午3:24
     */
    public function update($id)
    {
        $params = request()->all();

        $this->configService->update($id, $params['value']);

        return Result::success();
    }


    /**
     * 根据key值获取
     * @param $key
     * @return \Illuminate\Http\JsonResponse
     * author gzy
     */
    public function getOne($key)
    {
        $data = $this->configService->getOne($key);

        return Result::success($data);
    }

}
