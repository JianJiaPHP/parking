<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
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
     * 获取配置
     * @return \Illuminate\Http\JsonResponse
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function config()
    {
        $data = [
            'charge' => $this->configService->getOne('mini_program.charge'),
            'coupon' => $this->configService->getOne('mini_program.coupon')
        ];
        return Result::success($data);
    }


}
