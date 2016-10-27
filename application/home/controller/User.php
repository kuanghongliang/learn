<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/10/19
 * Time: 16:27
 */

namespace app\home\controller;
use think\Cookie;
use think\Request;
use think\Db;
use think\View;
use app\common\utils\Validate;
class User extends Base{

    public function login()
    {
        if(Cookie::has('user_name')){
            //重定向到index控制器index
            $this->redirect('index/index');
            //重定向到index控制器index
            //$this->success('ok','index/index');
            //$this->error('错误提示');
        }
        //return $this->fetch();
        return view();
    }

    public function reg()
    {
        //return $this->fetch();
        return view('reg');

    }

    public function regForm()
    {
        //$view = new View();
        //return $view->fetch('common/error');
        $request = Request::instance();
        if($request->isPost() == 'POST') {
            $username = $request->param('username');
            $password = $request->param('password');
            $password2 = $request->param('password2');
            $captcha = $request->param('verify_code');
            $code = $request->param('code');
            if(Validate::checkMobile($username)){
                if(!$code){
                    $this->error($this->errors[20]);
                }
            }
            $res = $this->saveReg($username,$password,$password2,$captcha);
            if($res['status'] != 1){//验证失败
                $errorMsg = $res['msg'];
                $this->error($errorMsg);
                //return $view->fetch('common/error');
            }
            $user = $res['data'];
            Cookie::set('user_id',$user['user_id'],['path' => '/']);
            $nickname = empty($user['nickname']) ? $username : $user['nickname'];
            Cookie::set('user_name',$nickname,['path' => '/']);
            return view('index');
        }
    }

    private function saveReg($username,$password,$password2,$captcha='')
    {
        $result = array('status'=>0,'msg'=>'','data'=>array());
        if($captcha && !captcha_check($captcha)){
            $result['msg'] = $this->errors[11];
            return $result;
        }
        if(!Validate::checkMobileOrEmail($username)){
            $result['msg'] = $this->errors[15];
            return $result;
        }
        if(Validate::checkMobile($username)){
            $map['mobile_validated'] = 1;
            $map['nickname'] = $map['mobile'] = $username;
        }else{
            $map['email_validated'] = 1;
            $map['nickname'] = $map['email'] = $username;
        }
        if(empty($username) || empty($password)){
            $result['msg'] = $this->errors[16];
            return $result;
        }
        if($password != $password2){
            $result['msg'] = $this->errors[17];
            return $result;
        }
        if($this->checkUsername($username)){
            $result['msg'] = $this->errors[18];
            return $result;
        }
        $map['password'] = md5($password);
        $map['reg_time'] = time();
        $userId = Db::table('tp_users')->insert($map);
        if(!$userId){
            $result['msg'] = $this->errors[19];
            return $result;
        }
        $user = Db::table('tp_users')->where('user_id',$userId)->find();
        $result['status'] = 1;
        $result['data'] = $user;
        return $result;
    }

    public function verifyCaptcha()
    {
        //验证码
        $captcha = Request::instance()->param('captcha');
        /*if(!captcha_check($captcha)){
            //验证失败
            $result=false;
            exit($this->returnJsonError(11));
        };*/
        $result = $this->validate(array('captcha'=>$captcha),[
            'captcha|验证码' => 'require|captcha'
        ]);
        if($result == true){
            exit($this->returnJsonSuccess());
        }else{
            exit($this->returnJsonError(11));
        }
    }

    /*
     * 登录信息验证
     * return json
     */
    public function loginVerify()
    {
        $request = Request::instance();
        $method = $request->method();
        if($method == 'POST') {
            $username = $request->param('username');
            $password = $request->param('password');
            $code = $request->param('verify_code');
            if (!captcha_check($code)) {
                //验证失败
                exit($this->returnJsonError(11));
            };
            $user = Db::table('tp_users')
                ->where('mobile', $username)
                ->whereOr('email', $username)
                ->find();
            if (!$user) {
                exit($this->returnJsonError(12));
            } elseif (md5($password) != $user['password']) {
                exit($this->returnJsonError(13));
            } elseif ($user['is_lock'] == 1) {
                exit($this->returnJsonError(14));
            } else {
                Cookie::set('user_id', $user['user_id'], ['path' => '/']);
                $nickname = empty($user['nickname']) ? $username : $user['nickname'];
                Cookie::set('user_name',$nickname,['path' => '/']);
                exit($this->returnJsonSuccess());
            }
        }else{
            exit($this->returnJsonError(1));
        }

    }

    private function checkUsername($username)
    {
        $user = Db::name('users')
            ->where('mobile', $username)
            ->whereOr('email', $username)
            ->find();
        if($user){
            return true;
        }else{
            return false;
        }
    }

    /*
     * 验证用户名是否被注册（手机号或邮箱）
     * return json
     */
    public function checkUsernameOrMobile()
    {
        $request = Request::instance();
        $method = $request->method();
        if($method == 'POST') {
            $username = $request->param('username');
            $user = Db::name('users')
                ->where('mobile', $username)
                ->whereOr('email', $username)
                ->find();
            if ($user) {
                exit($this->returnJsonSuccess());
            } else {
                exit($this->returnJsonError(12));
            }
        }else{
            exit($this->returnJsonError(1));
        }
    }

}