<?php
// 优惠券
Route::middleware(['admin.user', 'admin.permission'])->group(function () {
    # 配置管理
    Route::get('setting', 'SettingController@index');
    Route::put('setting/{id}', 'SettingController@update');

});
