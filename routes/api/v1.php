<?php
Route::prefix('v1')->namespace('V1')->group(function () {
    # 不需要登录的路由
    Route::group([], function () {
        Route::get('ping', 'PingController@index');

        Route::any('callback', 'PingController@callback');
        # 验证码发送
        Route::post('send', 'SendController@send');
        # 获取配置
        Route::get('config', 'ConfigController@config');
    });

    # 用户
    Route::prefix('user')->group(function () {
        # 授权登录
        Route::get('login', 'UserController@login');
        # 获取token
        Route::post('token', 'UserController@token');

    });
    # 需要登录的
    Route::middleware('api.user')->group(function () {
        # 用户
        Route::prefix('user')->group(function () {
            # 获取我的联系方式
            Route::get('phone', 'UserController@phone');
            # 添加联系方式
            Route::post('phone', 'UserController@phoneCreate');
            # 修改联系方式
            Route::put('phone', 'UserController@phoneUpdate');

        });
        # 优惠券
        Route::prefix('coupon')->group(function () {
            # 用户可用优惠券
            Route::get('available', 'CouponController@available');
            # 我的优惠券
            Route::get('my', 'CouponController@my');
            # 赠送记录
            Route::get('give', 'CouponController@give');
            # 赠送给用户
            Route::post('give', 'CouponController@giveUser');
        });

        # 停车
        Route::prefix('parking')->group(function () {
            # 根据车牌查询信息
            Route::get('car-number', 'ParkingController@carNumber');
            # 选择优惠券后
            Route::get('choose-coupon', 'ParkingController@chooseCoupon');
            # 支付
            Route::post('pay', 'ParkingController@pay');
            # 缴费记录
            Route::get('list', 'ParkingController@list');
            # 详情
            Route::get('show/{id}', 'ParkingController@show');
        });
    });
});





