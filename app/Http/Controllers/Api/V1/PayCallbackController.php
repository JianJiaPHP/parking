<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Utils\WecahtPay;

class PayCallbackController extends Controller
{

    /**
     * 支付回调
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function wechat()
    {
        return WecahtPay::callback();
    }

}
