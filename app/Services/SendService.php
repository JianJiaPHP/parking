<?php


namespace App\Services;


use App\Utils\SendSms;

class SendService
{

    /**
     * 验证码发送
     * @param $phone
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function send($phone)
    {
        return SendSms::sendCode($phone);
    }

}
