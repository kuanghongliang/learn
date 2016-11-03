<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/10/31
 * Time: 11:52
 */

namespace app\admin\controller;
use think\Db;
use think\Request;
use think\Session;

class Admin extends Base{
    public function login()
    {
        //判断是否已登录，登录跳到首页
        if(session('?admin_id') && session('admin_id') > 0){
            $this->redirect('index/index');
        }
        $request = Request::instance();
        //登录验证
        if($request->isPost()){
            $username = $request->param('username');
            $password = $request->param('password');
            $vertify = $request->param('vertify');
            if(empty($username) || empty($password)){
                exit($this->returnJsonError(13));
            }
            if(!captcha_check($vertify)){
                exit($this->returnJsonError(11));
            }
            $join = [
                ['tp_admin_role r','a.role_id = r.role_id']
            ];
            $adminInfo = Db::name('admin')
                ->alias('a')
                ->join($join)
                ->where('a.user_name',$username)
                ->where('a.password',md5($password))
                ->find();

            if(!empty($adminInfo) && is_array($adminInfo)){
                Session::set('admin_id',$adminInfo['admin_id']);
                Session::set('act_list',$adminInfo['act_list']);
                //上一次登录的时间
                $lastLogTime = Db::name('admin_log')
                    ->field('log_time')
                    ->where('admin_id',$adminInfo['admin_id'])
                    ->where('log_info','后台登录')
                    ->order('log_id desc')
                    ->limit(1)
                    ->value('log_time');
                Session::set('last_login_time',$lastLogTime);
                //更新此次登录时间与ip
                db('admin')->where('admin_id',$adminInfo['admin_id'])->update(['last_login' => time(),'last_ip' => $request->ip()]);
                //保存此次登陆的记录
                $data = ['admin_id' => $adminInfo['admin_id'],'log_info' => '后台登录', 'log_ip'=> $request->ip(),'log_url' => $request->url(),'log_time' => time()];
                db('admin_log')->insert($data);
                exit($this->returnJsonSuccess());
            }else{
                exit($this->returnJsonError(12));
            }
        }
        return $this->fetch();
    }

    public function logout()
    {
        Session::delete('admin_id');
        Session::delete('act_list');
        Session::delete('last_login_time');
        $this->redirect('admin/login');
    }

    public function adminInfo()
    {
        $request = Request::instance();
        $adminId = $request->param('admin_id',1);
        $adminInfo = [];
        if($adminId){
            $adminInfo = Db::name('admin')
                ->where('admin_id',$adminId)
                ->find();
            $adminInfo['password'] = '';
        }
        $act = empty($adminId) ? 'add' : 'edit';
        $role = "";
        return $this->fetch('info',[
            'info' => $adminInfo,
            'act' => $act,
            'role' => $role
        ]);
    }

    public function saveAdminInfo()
    {
        $request = Request::instance();
        $act = $request->param('act');
        $username = $request->param('user_name');
        if($act == 'add'){
            $email =$request->param('email');
            $password = $request->param('password');
        }else if($act == 'edit'){
            $orgPassword = $request->param('org_password');
            $newPassword = $request->param('new_password');
            $confirmPassword = $request->param('confirm_password');
            $adminInfo = Db::name('admin')
                ->where('user_name',$username)
                ->find();
            if($adminInfo['password'] != md5($orgPassword)){
                $this->error($this->errors['14']);
            }
            if($newPassword != $confirmPassword){
                $this->error($this->errors['15']);
            }
            Db::table('tp_admin')
                ->where('admin_id',$adminInfo['admin_id'])
                ->setField('password',md5($newPassword));
            $this->success('操作成功');
        }else if($act == 'del'){

        }



    }
}