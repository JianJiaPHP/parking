<?php
// 用户管理
Route::middleware(['admin.user', 'admin.permission'])->prefix("user")->group(function () {
    # 用户列表
    Route::get('list', 'UserController@list');
    # 优惠券赠送
    Route::post('give', 'UserController@give');



});
