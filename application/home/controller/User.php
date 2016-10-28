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
use think\Session;
use think\View;
use app\common\utils\StringUtils;
use app\common\utils\Common;
class User extends Base
{

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
        $smsCodeExpire = 1;
        return view('reg',['sms_time_out' => $smsCodeExpire*60]);

    }

    public function regForm()
    {
        //$view = new View();
        //return $view->fetch('common/error');
        $request = Request::instance();
        if($request->isPost()) {
            $username = $request->param('username');
            $password = $request->param('password');
            $password2 = $request->param('password2');
            $captcha = $request->param('verify_code');
            $code = $request->param('code');
            if(StringUtils::checkMobile($username)){
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
            //跳转到首页，指向index控制器index方法
            $this->redirect('index/index');
        }
    }

    private function saveReg($username,$password,$password2,$captcha='')
    {
        $result = array('status'=>0,'msg'=>'','data'=>array());
        if($captcha && !captcha_check($captcha)){
            $result['msg'] = $this->errors[11];
            return $result;
        }
        if(!StringUtils::checkMobileOrEmail($username)){
            $result['msg'] = $this->errors[15];
            return $result;
        }
        if(StringUtils::checkMobile($username)){
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

    /*
     * 手机发送验证码
     * return json
     */
    public function sendMobileCode()
    {
        $request = Request::instance();
        $mobile = $request->param('mobile');
        if(!StringUtils::checkMobile($mobile)){
            exit($this->returnJsonError(21));
        }
        $sessionId = session_id('aaaa');
        $smsLog = Db::name('sms_log')
            ->where('mobile',$mobile)
            ->where('session_id',$sessionId)
            ->order('id','DESC')
            ->find();
        $smsCodeExpire = 1;
        if($smsLog && time() - $smsLog['add_time'] < $smsCodeExpire*60) {
            exit($this->returnJsonError(23,$smsCodeExpire*60));
        }
        $code = rand(100000,999999);
        $content = "你的验证码为：".$code."，有效时间为".$smsCodeExpire."分钟，请不要随意泄露！";
        $res = Common::sendMobileCode($mobile,$content,$code,$sessionId);
        if(!$res){
            exit($this->returnJsonError(22));
        }
        exit($this->returnJsonSuccess());
    }

}