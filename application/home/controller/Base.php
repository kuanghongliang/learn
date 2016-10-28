<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/10/25
 * Time: 13:38
 */

namespace app\home\controller;

use think\Controller;

class Base extends Controller{

    public function __construct()
    {
        //1-10 保留
        $this->errors['1'] = '请求不合法';
        $this->errors['2'] = '请求参数错误';
        //11-30 登录模块提示语
        $this->errors['11'] = '验证码错误';
        $this->errors['12'] = '账号不存在';
        $this->errors['13'] = '账号或密码错误';
        $this->errors['14'] = '账号异常已被锁定';
        $this->errors['15'] = '用户名格式不正确,请用手机号或邮箱注册';
        $this->errors['16'] = '用户名或密码不能为空';
        $this->errors['17'] = '两次输入密码不一致';
        $this->errors['18'] = '账号已存在';
        $this->errors['19'] = '注册失败';
        $this->errors['20'] = '请输入短信验证码';
        $this->errors['21'] = '手机号码格式不正确';
        $this->errors['22'] = '发送失败';
        $this->errors['23'] = '秒内不允许重复发送';
        $this->errors['24'] = '手机验证码不匹配';
        $this->errors['25'] = '手机验证码超时';



        /*
         * 成功返回的信息
         */
        $this->success['11'] = '验证码已发送，请注意查收';
    }
    protected function returnJsonError($code,$param='')
    {
        $data = array('rs'=>false,'msg'=>$param ? $param.$this->errors[$code] : $this->errors[$code]);
        return json_encode($data);
    }

    protected function returnJsonSuccess($code=0)
    {
        $data = array('rs'=>true,'msg'=>$code ? $this->success['11'] : '成功');
        return json_encode($data);
    }
}