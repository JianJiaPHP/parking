<?php
use Illuminate\Support\Facades\Route;
//Route::prefix('tp-admin')->namespace('Admin\Base')->group(function () {
//    //后台登陆
//    Route::post('login', 'AuthController@login');
//    //获取配置
//    Route::get('get_config/{key}', 'ConfigController@getOne');
//});

# 支付回调
Route::any('/callback', 'Api\V1\PayCallbackController@wechat')->name("callback");




Route::get('/', function (\App\Services\ConfigService $service) {
    $name = $service->getOne('admin.admin_name');
    $record =  $service->getOne('admin.record');
    return view('index',compact('name','record'));
});
