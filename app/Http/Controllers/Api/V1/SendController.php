<?php


namespace App\Http\Controllers\Api\V1;


use App\Http\Controllers\Controller;
use App\Http\Requests\Api\SendMsgRequest;
use App\Services\SendService;
use App\Utils\Result;

class SendController extends Controller
{
    private $sendService;

    /**
     * SendController constructor.
     */
    public function __construct(SendService $service)
    {
        $this->sendService = $service;
    }

    /**
     * 验证码发送
     * @param SendMsgRequest $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function send(SendMsgRequest $request)
    {
        $phone = $request->get('phone');
        $result = $this->sendService->send($phone);
        return Result::choose($result);
    }

}
