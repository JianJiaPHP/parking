<?php

use Illuminate\Support\Facades\Route;


# 不需要登录的路由
Route::group([], function () {
    # 后台登陆
    Route::post('login', 'Base\AuthController@login');
    # 获取配置
    Route::get('getConfig/{key}', 'Base\ConfigController@getOne');

});


Route::namespace('Base')->group(function () {
    // 需要登录的
    Route::middleware(['admin.user'])->group(function () {
        // 个人信息
        Route::prefix('me')->group(function () {
            # 个人信息
            Route::get('/', 'MeController@me');
            # 退出登陆
            Route::get('logout', 'MeController@logout');
            # 修改个人密码
            Route::put('updatePassword', 'MeController@updatePwd');
            # 更新个人信息
            Route::post('updateInfo', 'MeController@updateInfo');
            # 获取登陆者该有的导航栏
            Route::get('getNav', 'MeController@getNav');
            # 上传文件
            Route::post('upload', 'UploadController@upload');

        });
        # 需要验证权限的
        Route::middleware(['admin.permission'])->group(function () {
            # 配置管理
            Route::get('config', 'ConfigController@index');
            Route::put('config/{id}', 'ConfigController@update');
           # 资源列表
            Route::apiResource('adminResource', 'AdminResourceController')->except(['show']);
            # 所有资源列表
            Route::get('adminResourceAll', 'AdminResourceController@all');
            # 菜单管理
            Route::apiResource('adminMenu', 'AdminMenuController')->except(['show']);
            # 所有菜单
            Route::get('adminMenuListAll', 'AdminMenuController@listAll');

            # 操作日志
            Route::get('operating_log', 'LogController@operatingLog');
            Route::get('login_log', 'LogController@loginLog');

            # 角色管理
            Route::apiResource('role', 'RoleController')->except(['show']);
            # 所有角色
            Route::get('rolesAll', 'RoleController@getAll');
            # 管理员管理
            Route::apiResource('administrators', 'AdministratorController')->except(['show']);

        });
    });


});
