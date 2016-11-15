<?php
namespace app\admin\controller;

use think\Db;

class Index extends Base
{
    public function index()
    {
        $adminId = session('admin_id');
        $lastLoginTime = session('last_login_time');
        $adminInfo = Db::name('admin')
            ->where('admin_id',$adminId)
            ->find();
        $actList = session('act_list');
        $menuList = getMenuList($actList);
        return $this->fetch('index',[
            'menu_list' => $menuList,
            'admin_info' => $adminInfo,
            'last_login_time' => date('Y-m-d H:i:s',$lastLoginTime)
        ]);
    }
    public function welcome()
    {
        //获取系统信息
        $sysInfo = getSysInfo();
        return $this->fetch('welcome',[
            'sys_info' => $sysInfo
        ]);
    }
}
