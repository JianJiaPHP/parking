<?php


namespace App\Utils;


class Time
{
    /**
     * 秒转小时
     * @param $seconds
     * @return string
     * author gzy
     */
    public static function secondsToHour($seconds)
    {
        return sprintf("%02d%s%02d%s%02d%s", floor($seconds / 3600), '小时', ($seconds / 60) % 60, '分钟', $seconds % 60, '秒');
    }

}
