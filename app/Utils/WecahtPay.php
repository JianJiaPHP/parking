<?php


namespace App\Utils;


use App\Models\UserCoupon;
use App\Models\UserParking;
use EasyWeChat\Factory;
use Illuminate\Support\Facades\DB;

class WecahtPay
{


    /**
     * 初始化微信支付
     * @return \EasyWeChat\Payment\Application
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    private static function init()
    {
        return Factory::payment(config('wechat.payment.default'));
    }


    /**
     * 支付
     * @param $body //内容
     * @param $no // 订单编号
     * @param $totalFee //金额（元）
     * @param $notifyUrl //回调地址
     * @param $openid //用户openid
     * @param string $tradeType
     * @return array|\EasyWeChat\Kernel\Support\Collection|false|object|\Psr\Http\Message\ResponseInterface|string
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidArgumentException
     * @throws \EasyWeChat\Kernel\Exceptions\InvalidConfigException
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function pay($body, $no, $totalFee, $notifyUrl, $openid, $tradeType = 'JSAPI')
    {
        $app = self::init();
        $result = $app->order->unify([
            'body' => $body,
            'out_trade_no' => $no,
//            'total_fee' => bcmul($totalFee,100),
            'total_fee' => 1,
            'notify_url' => $notifyUrl, // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            'trade_type' => $tradeType, // 请对应换成你的支付方式对应的值类型
            'openid' => $openid,
        ]);
        if ($result['result_code'] === 'SUCCESS') {
            if ($tradeType = 'JSAPI') {
                return $app->jssdk->bridgeConfig($result['prepay_id'], false);
            }
            return $result;
        }
        return false;
    }

    /**
     * 支付回调
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \EasyWeChat\Kernel\Exceptions\Exception
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function callback()
    {
        $app = self::init();
        $response = $app->handlePaidNotify(function ($message, $fail) {
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
            $order = UserParking::getOneByWhere(['no' => $message['out_trade_no']]);
            if (!$order || $order->status == 1) { // 如果订单不存在 或者 订单已经支付过了
                return true; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }
            $status = 0;
            if ($message['return_code'] === 'SUCCESS') { // return_code 表示通信状态，不代表支付状态
                // 用户是否支付成功
                if (\Arr::get($message, 'result_code') === 'SUCCESS') {
                    $status = 1;
                    // 用户支付失败
                } elseif (\Arr::get($message, 'result_code') === 'FAIL') {
                    $status = -1;
                }
            } else {
                return $fail('通信失败，请稍后再通知我');
            }


            DB::beginTransaction();
            try {
                UserParking::updateByWehre(['no' => $message['out_trade_no']], [
                    'status' => $status
                ]);
                // 更新优惠券状态
                UserCoupon::updateById($order->user_coupon_id, ['status' => 1]);
                DB::commit();
                return true;
            } catch (\Exception $exception) {
                DB::rollback();
                return $fail('通信失败，请稍后再通知我');
            }

        });

        return $response->send(); // return $response;
    }

}
