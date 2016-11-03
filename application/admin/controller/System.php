<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/11/1
 * Time: 16:09
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class System extends Base{
    public function index()
    {
        $request = Request::instance();
        $act = $request->param('type','index');
        if($act == 'index' || $act == 'shop_info'){
            $type = 'shop_info';
            $act = 'index';
        }else{
            $type = $act;
        }
        $shopConfig = Db::name('config')
            ->where('inc_type',$type)
            ->select();
        $config = array();
        foreach($shopConfig as $k => $v){
            $config[$v['name']] = $v['value'];
        }
        $configMenu = configMenu();
        return $this->fetch($act,[
            'group_list' => $configMenu,
            'config' => $config,
            'inc_type' => $type
        ]);
    }

    public function saveConfig()
    {
        $request = Request::instance();
        $params = $request->param();
        $type = $params['inc_type'];
        unset($params['inc_type']);
        $shopConfig = Db::name('config')
            ->where('inc_type',$type)
            ->select();
        $temp = array();
        foreach($shopConfig as $k => $v){
            $temp[$v['name']] = $v['value'];
        }
        foreach($params as $kk => $vv){
            $arr = array('name'=>$kk,'value'=>trim($vv),'inc_type' => $type);
            if(!isset($temp[$kk])){
                Db::name('config')->insert($arr);
            }else{
                if($temp[$kk] != $vv){
                    Db::name('config')
                        ->where('name',$kk)
                        ->where('inc_type',$type)
                        ->update(['value'=>$vv]);
                }
            }
        }
        $this->success('操作成功');
    }

    public function linkList()
    {
        $link = Db::table('tp_friend_link')->page(1,10)->select();
        return $this->fetch('linkList',[
            'list' => $link,
            'page' => 1
        ]);
    }

    public function addLink()
    {
        $request = Request::instance();
        $data = $request->param();
        $act = $data['act'];
        unset($data['act']);
        if($request->isPost()){
            if($act == 'add'){
                db('friend_link')->insert($data);
                $this->success('添加成功');
            }elseif($act == 'edit'){

            }elseif($act == 'del'){

            }

        }else{
            if($act == 'add'){
                $info = array('link_id'=>'','link_name'=>'','link_url'=>'','link_logo'=>'','is_show'=>'','target'=>'','orderby'=>'');
                return $this->fetch('addLink',[
                    'info' => $info,
                    'act' => $act
                ]);
            }elseif($act == 'edit'){
                $info = db('friend_link')->where('link_id',$data['link_id'])->find();
                return $this->fetch('addLink',[
                    'info' => $info,
                    'act' => $act
                ]);
            }

        }

    }
}