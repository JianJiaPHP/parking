<?php


namespace App\Services;


use App\Exceptions\ApiException;
use App\Models\Administrator;
use App\Models\AdminLoginLog;
use App\Models\AdminMenu;
use App\Models\AdminResource;
use App\Models\AdminRoleAdministrator;
use App\Utils\Ip;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * 管理员服务
 * Class AdministratorServiceImpl
 * @package App\Services\impl
 */
class AdministratorService
{

    /**
     * 列表
     * @param $account string 账号
     * @param $limit //条数
     * author hy
     */
    public function list($account, $limit)
    {
        return Administrator::list($account, $limit);
    }

    /**
     * 添加
     * @param $params
     * author hy
     * @throws ApiException
     */
    public function add($params)
    {
        $exist = Administrator::getByWhereOne(['account' => $params['account']]);
        if ($exist) {
            throw new ApiException("该账号已存在");
        }
        $now = Carbon::now()->toDateTimeString();
        $md5Password = md5($params['password']);
        DB::beginTransaction();
        try {
            $result = Administrator::create([
                'account' => $params['account'],
                'nickname' => $params['nickname'],
                'avatar' => 'http://placeimg.com/300/200',
                'password' => Hash::make($md5Password)
            ]);
            // 添加角色管理员
            if (!empty($params['roleIds'])) {
                $rolesData = [];
                $roles = explode(',', $params['roleIds']);
                foreach ($roles as $v) {
                    $rolesData[] = [
                        'role_id' => $v,
                        'administrator_id' => $result->id,
                        'created_at' => $now
                    ];
                }
                AdminRoleAdministrator::insert($rolesData);
            }

            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 更新
     * @param $id
     * @param $params
     * @return bool
     * @throws \Exception
     * author hy
     */
    public function update($id, $params)
    {
        $now = Carbon::now()->toDateTimeString();
        $oldData = Administrator::getByWhereOne(['id' => $id]);
        if (!$oldData) {
            throw new ApiException();
        }

        if ($oldData->account != $params['account']) {
            $exist = Administrator::getByWhereOne(['account' => $params['account']]);
            if ($exist) {
                throw new ApiException("该账号已存在");
            }
        }

        $updateData = [
            'account' => $params['account'],
            'nickname' => $params['nickname'],
            'avatar' => 'http://placeimg.com/300/200',
        ];
        if (!empty($params['password'])) {
            $md5Password = md5($params['password']);
            $updateData ['password'] = Hash::make($md5Password);
        }
        DB::beginTransaction();
        try {
            Administrator::updateById($id, $updateData);

            AdminRoleAdministrator::deleteByWhere(['administrator_id' => $id]);

            // 添加角色管理员
            if (!empty($params['roleIds'])) {
                $rolesData = [];
                $roles = explode(',', $params['roleIds']);
                foreach ($roles as $v) {
                    $rolesData[] = [
                        'role_id' => $v,
                        'administrator_id' => $id,
                        'created_at' => $now
                    ];
                }
                AdminRoleAdministrator::insert($rolesData);

            }
            // 删除菜单缓存
            AdminMenu::delAdminByAdministratorId($id);
            // 删除资源缓存
            AdminResource::delAdminResourceByAdministratorId($id);
            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 删除
     * @param $id
     * @return bool
     * @throws \Exception
     * author hy
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            Administrator::destroy($id);

            AdminRoleAdministrator::deleteByWhere(['administrator_id' => $id]);
            // 删除菜单缓存
            AdminMenu::delAdminByAdministratorId($id);
            // 删除资源缓存
            AdminResource::delAdminResourceByAdministratorId($id);

            DB::commit();
            return true;

        } catch (\Exception $exception) {
            DB::rollback();
            return false;
        }
    }

    /**
     * 登录
     * @param $account
     * @param $password
     * @return mixed
     * author hy
     * @throws ApiException
     */
    public function login($account, $password, $ip)
    {
        $exist = Administrator::getByWhereOne(['account' => $account]);
        if (!$exist) {
            throw new ApiException("账号不存在");
        }
        if (!Hash::check($password, $exist->password)) {
            throw new ApiException("密码错误");
        }

        $token = auth('admin')->login($exist);

        if ($ip != '127.0.0.1') {
            $result = Ip::getIpInfo($ip);
            if ($result) {
                $log = [
                    'uid' => $exist->id,
                    'ip' => $ip,
                    'country' => !empty($result['country']) ? $result['country'] : '',
                    'city' => !empty($result['regionName']) ? $result['regionName'] : '',
                ];
            } else {
                $log = [
                    'uid' => $exist->id,
                    'ip' => $ip,
                ];
            }
            AdminLoginLog::create($log);
        }

        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('admin')->factory()->getTTL() * 60
        ];
    }

    /**
     * 修改密码
     * @param $params
     * @return mixed
     * author hy
     * @throws ApiException
     */
    public function updatePwd($params)
    {
        $user = auth('admin')->user();

        if (!Hash::check(md5($params['old_password']), $user['password'])) {
            throw new ApiException("原密码错误");
        }
        $password = Hash::make(md5($params['password']));

        return Administrator::updateById($user['id'], [
            'password' => $password
        ]);

    }

    /**
     * 更新个人信息
     * @param $params
     * @return int
     * author hy
     * @throws ApiException
     */
    public function updateInfo($params)
    {
        $user = auth('admin')->user();

        $oldData = Administrator::getByWhereOne(['id' => $user['id']]);
        if (!$oldData) {
            throw new ApiException();
        }

        if ($oldData->account != $params['account']) {
            $exist = Administrator::getByWhereOne(['account' => $params['account']]);
            if ($exist) {
                throw new ApiException("该账号已存在");
            }
        }
        return Administrator::updateById($user['id'], [
            'nickname' => $params['nickname'],
            'account' => $params['account'],
        ]);
    }

    /**
     * 获取个人信息
     * @return mixed
     * author hy
     */
    public function getInfo()
    {
        $id = auth('admin')->id();
        return Administrator::getAdministratorById($id);
    }
}
