<?php
//配置文件
return [
    // 默认模块名
    'default_module'         => 'admin',
    // 禁止访问模块
    'deny_module_list'       => ['common'],
    // 默认控制器名
    'default_controller'     => 'Admin',
    // 默认操作名
    'default_action'         => 'login',

    // 视图输出字符串内容替换
    'view_replace_str'       => [
        '__PUBLIC__' => '/static/common'
    ],
];