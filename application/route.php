<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;
// 注册路由到home模块的user控制器的reg操作
//Route::rule('user/reg','home/User/reg');
//Route::rule('user/login','home/User/login');
// 注册路由到home模块的user控制器的reg操作,并且为get请求，不指定默认为任何请求
//Route::rule('user/reg','home/User/reg','GET');
//支持get post请求
//Route::rule('user/login','home/User/login','GET|POST');
//简化写法
//Route::get('user/reg','home/User/reg');
//批量注册,注册多个路由规则后，系统会依次遍历注册过的满足请求类型的路由规则，一旦匹配到正确的路由规则后则开始调用控制器的操作方法，后续规则就不再检测
//Route::rule(['user/reg' => 'home/User/reg','user/login' =>'home/User/login']);
//Route::post(['user/reg' => 'home/User/reg','user/login' =>'home/User/login']);
return [
    '__pattern__' => [
        'name' => '\w+',
    ],
    '[hello]'     => [
        ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
        ':name' => ['index/hello', ['method' => 'post']],
    ],

];
