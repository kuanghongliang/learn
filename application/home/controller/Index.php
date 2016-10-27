<?php
namespace app\home\controller;

use think\Controller;
use think\Cookie;
use think\Request;

class Index extends Base
{
    public function index()
    {
        //return $this->fetch();
        $name = Cookie::get('user_name');
        return view('index',['name'=>$name]);
    }
    public function captcha()
    {
//        $username = Request::instance()->param('username');
//        $password = Request::instance()->param('password');
        $captcha = Request::instance()->param('captcha');
        $result = true;
       /* if(!captcha_check($captcha)){
            //验证失败
            $result=false;
        };*/
        $result = $this->validate(array('captcha'=>$captcha),[
            'captcha|验证码' => 'require|captcha'
        ]);
        return json_encode(array('rs'=>$result));
    }

    public function test()
    {
        $file = file_get_contents('http://dev.m.myantu.com/zz/order/push.do');
        return $file;
    }
}
