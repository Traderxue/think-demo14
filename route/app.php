<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

Route::get('think', function () {
    return 'hello,ThinkPHP6!';
});

Route::get('hello/:name', 'index/hello');


//不需要token
Route::group("/user",function(){

    Route::post("/register","user/register");

    Route::post("/login","user/login");

});

Route::group("/user",function(){

    Route::post("/edit","user/edit");       //编辑

    Route::post("/disabled/:id","user/disabled");       //禁用

    Route::post("/enabled/:id","user/enabled");         //启用

    Route::get("/page","user/page");       

})->middleware(app\middleware\JwtMiddleware::class);

//邀请记录表
Route::group("/promoter",function(){

    Route::get("/get/:uid","promoter/getByUid");        //获取用户邀请信息

    Route::post("/verify/:id","promoter/verify");     //审核被邀请人是否有效

    Route::get("/page","promoter/page");        

})->middleware(app\middleware\JwtMiddleware::class);

//用户余额表
Route::group("/balance",function(){

    Route::get("/get/:uid","balance/get");          //获取用户余额信息

    Route::get("/page","balance/page");         

});

//资金记录表
Route::group("/funds",function(){

    Route::get("/get/:uid","funds/getByUid");       //获取用户资金记录

    Route::post("/add","funds/add");                //添加记录

    Route::get("/page","funds/page");               

    Route::post("/verify/:id","funds/verify");          //审核用户资金更新余额
});