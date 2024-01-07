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

    Route::post("/edit","user/edit");

    Route::post("/disabled/:id","user/disabled");

    Route::post("/enabled/:id","user/enabled");

    Route::get("/page","user/page");

})->middleware(app\middleware\JwtMiddleware::class);


