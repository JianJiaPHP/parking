<?php


namespace App\Services;


use App\Exceptions\ApiException;
use App\Models\User;
use App\Utils\OfficialAccount;
use App\Utils\SendSms;

class UserService
{

    /**
     * 后台列表
     * @param $phone //手机号码
     * @param $minParking //最小停车时间
     * @param $maxParking //最大停车时间
     * @param $lastParkingStart //开始停车时间
     * @param $lastParkingEnd //结束停车时间
     * @param $limit //每页显示
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * author hy
     */
    public function adminList($phone, $minParking, $maxParking, $lastParkingStart, $lastParkingEnd, $limit)
    {
        return User::adminList($phone, $minParking, $maxParking, $lastParkingStart, $lastParkingEnd, $limit);
    }


    /**
     * 登录
     * @return string
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function login()
    {
        return OfficialAccount::redirectUrl();
    }

    /**
     * 获取用户token
     * @return array
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function token()
    {
        if (empty(request()->get('code'))) {
            new ApiException("");
        }
        $userInfo = OfficialAccount::getUser();
        $openId = $userInfo['id'];
        $user = User::getOneByWhere(['openid' => $openId]);
        // 不存在就创建
        if (!$user) {
            $user = User::create([
                'openid' => $openId,
                'nickname' => $userInfo['nickname'],
                'avatar' => $userInfo['avatar']
            ]);
        }

        $token = auth('api')->login($user);
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ];
    }

    /**
     * 获取我的联系方式
     * @return array
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function phone()
    {
        $userId = auth('api')->id();
        $user = User::getOneByWhere(['id' => $userId]);

        return ['phone' => $user->phone];
    }


    /**
     * 添加手机号
     * @param $phone
     * @param $code
     * @return int
     * @throws ApiException
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function phoneCreate($phone, $code)
    {
        $userId = auth('api')->id();

        list($status, $message) = SendSms::checkCode($phone, $code);
        if (!$status) {
            throw new ApiException($message);
        }
        $has = User::getOneByWhere(['phone' => $phone]);
        if ($has) {
            throw new ApiException("手机号已被绑定");
        }

        return User::updateById($userId, ['phone' => $phone]);
    }

    /**
     * 手机号更改
     * @param $phone
     * @param $code
     * @param $newPhone
     * @param $newCode
     * @return bool|int
     * @throws ApiException
     * @author hy
     * @description 屏幕面前的你一定很温柔吧
     */
    public function phoneUpdate($phone, $code, $newPhone, $newCode)
    {
        if ($phone == $newPhone) {
            return true;
        }
        $userId = auth('api')->id();
        // 验证码判断
        list($status, $message) = SendSms::checkCode($phone, $code);
        if (!$status) {
            throw new ApiException($message);
        }
        // 验证码判断
        list($status, $message) = SendSms::checkCode($newPhone, $newCode);
        if (!$status) {
            throw new ApiException($message);
        }
        $has = User::getOneByWhere(['phone' => $newPhone]);
        if ($has) {
            throw new ApiException("手机号已被绑定");
        }

        return User::updateById($userId, ['phone' => $newPhone]);
    }


}
