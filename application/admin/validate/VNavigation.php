<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/11/16
 * Time: 10:06
 */

namespace app\admin\validate;
use think\Validate;

class VNavigation extends Validate{
    protected $rule = [
        'name' => 'require|max:30',
        'url' => 'require',
        'sort' => 'number|between:0,200'
    ];
    protected $message = [
        'name.require' => '导航名称必填',
        'name.max' => '导航名称最多不能超过30个字符',
        'url.require' => '链接必填',
        'sort.number' => '排序必须是数字',
        'sort.bettwen' => '排序只能在0-200之间'
    ];
    protected $scene = [
        'add' => ['name','url','sort'],
        'edit' => ['name','url','sort']
    ];
}