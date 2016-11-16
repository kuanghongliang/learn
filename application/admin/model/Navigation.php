<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/11/16
 * Time: 11:39
 */

namespace app\admin\model;


use think\Model;

class Navigation extends Model{
    // 设置当前模型对应的完整数据表名称
    protected $table = 'tp_navigation';

    /*
     * 添加或更新表单数据过滤非数据表字段数据
     */
    public static function saveForm($data,$condition=array())
    {
        if(empty($data) || !is_array($data)) return false;
        if($condition){
            $navigation = new Navigation();
            $res = $navigation->allowField(true)->save($data,$condition);
        }else{
            $navigation = new Navigation($data);
            $res = $navigation->allowField(true)->save();
        }
        if($res){
            return true;
        } else {
            return false;
        }
    }
}