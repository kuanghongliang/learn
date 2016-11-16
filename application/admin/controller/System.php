<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/11/1
 * Time: 16:09
 */

namespace app\admin\controller;


use app\admin\validate\VNavigation;
use think\Db;
use think\Request;
use app\admin\model\Navigation;
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
            $link = Db::table('tp_friend_link')->where('link_name','like',"%$keyword%")->paginate(2);
        }else{
            $link = Db::table('tp_friend_link')->paginate(2);
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

    public function navigationList()
    {
        $navigationList = Db::name('navigation')->order('id desc')->paginate(2);
        $page = $navigationList->render();
        return $this->fetch('navigationList',[
            'navigationList' => $navigationList,
            'page' => $page
        ]);
    }

    public function navigationOper()
    {
        $request = Request::instance();
        $act = $request->param('act');
        $data = $request->param();
        if($request->isPost()){
            if($act == 'add'){
                $result = $this->validate($data,'VNavigation');
                if(true !== $result){
                    $this->error($result);
                }
                $res = Navigation::saveForm($data);
                if(!$res){
                    $this->error('插入数据失败');
                }
                $this->success('添加成功','system/navigationList');
            }else if($act == 'edit'){
                $result = $this->validate($data,'VNavigation');
                if(true !== $result){
                    $this->error($result);
                }
                $res = Navigation::saveForm($data,['id'=>$data['id']]);
                if(!$res){
                    $this->error('更新数据失败');
                }
                $this->success('编辑成功','system/navigationList');
            }
        }else{
            if($act == 'add'){
                $navigation = array('id'=>'','name'=>'','sort'=>'','url'=>'');
                return $this->fetch('navigationOper',[
                    'navigation' => $navigation
                ]);
            }else if($act == 'edit'){
                $navigation = db('navigation')->where('id',$data['id'])->find();
                return $this->fetch('navigationOper',[
                    'navigation' => $navigation,
                ]);
            }else if($act == 'del'){
                db('navigation')->where('id',$data['id'])->delete();
                $this->success('操作成功','system/navigationList');
            }
        }
    }

    public function changeTableVal()
    {
        $request = Request::instance();
        $table = $request->param('table');
        $idName = $request->param('id_name');
        $idValue = $request->param('id_value');
        $field = $request->param('field');
        $value = $request->param('value');
        Db::name($table)->where($idName,$idValue)->update([$field=>$value]);
    }
}