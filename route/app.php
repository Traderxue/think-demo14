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

})->middleware(app\middleware\JwtMiddleware::class);

//资金记录表
Route::group("/funds",function(){

    Route::get("/get/:uid","funds/getByUid");       //获取用户资金记录

    Route::post("/add","funds/add");                //添加记录

    Route::get("/page","funds/page");               

    Route::post("/verify/:id","funds/verify");          //审核用户资金更新余额
})->middleware(app\middleware\JwtMiddleware::class);

Route::group("/coin",function(){

    Route::post("/add","coin/add");         //添加种类

    Route::post("/edit","coin/edit");       //编辑

    Route::get("/get","coin/getAll");           //前端获取所有

    Route::get("/page","coin/page");

    Route::delete("/delete/:id","coin/deleteById");     //删除

})->middleware(app\middleware\JwtMiddleware::class);


Route::group("/usercoin",function(){

    Route::post("/add","userCoin/add");             //添加，同时需要启动定时任务自动结算

    Route::get("/get/:uid","userCoin/getByUid");        //获取用户的持仓信息

    Route::delete("/delete/:id","userCoin/deleteById");         //删除记录

    Route::page("/page","userCoin/page");           

})->middleware(app\middleware\JwtMiddleware::class);


//不需要 token
Route::group("/admin",function(){

    Route::post("/add","admin/add");

    Route::login("/login","admin/login");

});

Route::group("/admin",function(){

    Route::post("/edit","admin/edit");      //编辑 

    Route::delete("/delete/:id","admin/deleteById");    //删除

    Route::get("/page","amdin/page");

})->middleware(app\middleware\JwtMiddleware::class);