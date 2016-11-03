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
        $request = Request::instance();
        $keyword = $request->param('keywords');
        if($keyword){
            $link = Db::table('tp_friend_link')->where('link_name','like',"%$keyword%")->paginate(1);
        }else{
            $link = Db::table('tp_friend_link')->paginate(1);
        }
        $page = $link->render();
        return $this->fetch('linkList',[
            'list' => $link,
            'page' => $page
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
                $this->success('添加成功','system/linkList');
            }elseif($act == 'edit'){
                db('friend_link')->where('link_id',$data['link_id'])->update($data);
                $this->success('修改成功');
            }elseif($act == 'del'){
                $delRes = db('friend_link')->where('link_id',$data['link_id'])->delete();
                if($delRes){
                    exit($this->returnJsonSuccess());
                }else{
                    exit($this->returnJsonError(16));
                }
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