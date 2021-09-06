<?php
// 优惠券
Route::middleware(['admin.user', 'admin.permission'])->group(function () {
    # 优惠券列表
    Route::get('coupon', 'CouponController@list');
    # 优惠券添加
    Route::post('coupon', 'CouponController@create');
    # 优惠券更新
    Route::put('coupon/{id}', 'CouponController@update');
    # 优惠券修改状态
    Route::post('coupon-status', 'CouponController@status');
    # 赠送优惠券列表
    Route::get('coupon-give', 'CouponController@give');
    # 优惠券互赠记录
    Route::get('coupon-mutual-gift', 'CouponController@mutualGift');
    # 优惠券使用记录
    Route::get('coupon-use', 'CouponController@use');

});
