<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/10/31
 * Time: 13:49
 */

namespace app\admin\controller;
use think\Controller;
use think\Request;

class Base extends Controller{
    public function __construct()
    {
        parent::__construct();
        //1-10 保留
        $this->errors['1'] = '请求不合法';
        $this->errors['2'] = '请求参数错误';
        //11-30 登录模块提示语
        $this->errors['11'] = '验证码错误';
        $this->errors['12'] = '用户名或密码错误';
        $this->errors['13'] = '用户名或密码不能为空';
    }

    public function _initialize()
    {
        $request = Request::instance();
        $url = $request->url();
        $act = str_replace('/','',strrchr($url,'/'));
        if(in_array($act,array('login','logout','login.html','logout.html'))){

        }else{
            if(session('admin_id') > 0){

            }else{
                $this->redirect('admin/login');
            }
        }

    }

    protected function returnJsonError($code,$param='')
    {
        $data = array('rs'=>false,'msg'=>$param ? $param.$this->errors[$code] : $this->errors[$code]);
        return json_encode($data);
    }

    protected function returnJsonSuccess()
    {
        $data = array('rs'=>true,'msg'=>'成功');
        return json_encode($data);
    }
}