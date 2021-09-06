<?php


namespace App\Utils;


use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class SendSms
{
    private static $KEY = "wfc:phone:";

    /**
     * 短信发送
     * @param $phone
     * @param $content
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * author II
     */
    private static function send($phone, $content)
    {
        try {
            //账号
            $account = 'wfc';
            //密码
            $password = 'wfc321654';

            $url = "http://cf.51welink.com/submitdata/Service.asmx/g_Submit";
            $data = "sname=$account&spwd=$password&scorpid=&sprdid=1012818&sdst=" . $phone . "&smsg=" . rawurlencode($content);
            $client = new Client(['timeout' => 5.0]);
            $response = $client->request('GET',
                $url . '?' . $data
            );
            if ($response->getStatusCode() === 200) {
                $result = json_decode(json_encode(simplexml_load_string($response->getBody()->getContents())), true);
                if ($result['State'] == 0) {
                    return true;
                }
                Log::error($result);
            }
            return false;
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());
            return false;
        }
    }

    /**
     * 验证验证码
     * @param $phone string 手机号码
     * @param $code string 验证码
     * @return array
     * author gzy
     */
    public static function checkCode($phone, $code)
    {
        $key = self::$KEY . 'code:' . $code . '-' . $phone;
        $redisCode = Redis::get($key);
        if (!$redisCode) {
            return [false, '验证码错误'];
        }
        if ($redisCode != $code) {
            return [false, '验证码错误'];
        }
        return [true, ''];
    }

    /**
     * 发送验证码
     * @param string $phone
     * @return bool
     * @throws \GuzzleHttp\Exception\GuzzleException
     * author II
     */
    public static function sendCode(string $phone)
    {
        //验证码
        $randCode = rand(100000, 999999);
        $code = str_shuffle($randCode);
        $expireTime = 60 * 5;//过期时间
        //redis key
        $key = self::$KEY . 'code:' . $code . '-' . $phone;

        Redis::setex($key, $expireTime, $code);

        return self::send($phone, "您的验证码为$code ,该验证码5分钟有效，请勿泄露与于他人【WFC】");
    }


}
