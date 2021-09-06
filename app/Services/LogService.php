<?php


namespace App\Services;


use App\Models\AdminLoginLog;
use App\Models\AdminOperatingLogs;

class LogService
{

    /**
     * 操作日志
     * @param $keyword
     * @param $limit
     * @return mixed
     * author hy
     */
    public function operatingLog($keyword, $limit)
    {
        return AdminOperatingLogs::list($keyword, $limit);
    }

    /**
     * 登陆日志列表
     * @param $keyword
     * @param $limit
     * @return mixed
     * author hy
     */
    public function loginLog($keyword, $limit)
    {
        return AdminLoginLog::list($keyword, $limit);
    }
}
