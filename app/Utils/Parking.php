<?php


namespace App\Utils;


use App\Exceptions\ApiException;
use Carbon\Carbon;

class Parking
{

    /**
     * 根据车牌号获取金额
     * @param $carNumber
     * @return array
     * @throws ApiException
     * @author gzy
     * @description 屏幕面前的你一定很温柔吧
     */
    public static function getCarNumberPrice($carNumber)
    {
        $carNumberInfos = [
            [
                'car_number' => "渝AS6778",
                'price' => 100,
                'start_time' => '2021-06-22 12:12:12',
                'end_time' => '2021-06-22 13:12:12'
            ],
            [
                'car_number' => "渝A951AV",
                'price' => 200,
                'start_time' => '2021-06-22 12:12:12',
                'end_time' => '2021-06-22 15:12:12'
            ],
            [
                'car_number' => "渝A77777",
                'price' => 300,
                'start_time' => '2021-06-22 02:12:12',
                'end_time' => '2021-06-22 08:12:12'
            ]
        ];


        foreach ($carNumberInfos as $info) {
            if ($info['car_number'] == $carNumber) {
                $seconds = Carbon::parse($info['end_time'])->diffInSeconds(Carbon::parse($info['start_time']));
                $info['time'] = Time::secondsToHour($seconds);
                $info['pay_price'] = $info['price'];
                return $info;
            }
        }
        throw new ApiException('暂无该车牌');
    }


}
