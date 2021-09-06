<?php


namespace App\Utils;

// 公众号
use App\Helpers\HttpHelper;
use App\Models\User;
use EasyWeChat\Factory;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

/**
 * 公众号相关
 * Class OfficialAccount
 * @package App\Utils
 */
class OfficialAccount
{

    /**
     * 初始化
     * @return \EasyWeChat\OfficialAccount\Application
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    private static function init()
    {
        return Factory::officialAccount(config('wechat.official_account.default'));
    }

    /**
     * 授权url
     * @return string
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function redirectUrl()
    {
        $app = self::init();
        return $app->oauth->redirect()->getTargetUrl();
    }


    /**
     * 根据code获取用户信息
     * @return mixed
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getUser()
    {
        $app = self::init();

        return $app->oauth->user();
    }
}
