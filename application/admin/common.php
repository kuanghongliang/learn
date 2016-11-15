<?php
/*
 * 共用方法
 */
//获取系统信息
function getSysInfo(){
    $sys_info['os']             = PHP_OS;
    $sys_info['zlib']           = function_exists('gzclose') ? 'YES' : 'NO';//zlib
    $sys_info['safe_mode']      = (boolean) ini_get('safe_mode') ? 'YES' : 'NO';//safe_mode = Off
    $sys_info['timezone']       = function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone";
    $sys_info['curl']			= function_exists('curl_init') ? 'YES' : 'NO';
    $sys_info['web_server']     = $_SERVER['SERVER_SOFTWARE'];
    $sys_info['phpv']           = phpversion();
    $sys_info['ip'] 			= GetHostByName($_SERVER['SERVER_NAME']);
    $sys_info['fileupload']     = @ini_get('file_uploads') ? ini_get('upload_max_filesize') :'unknown';
    $sys_info['max_ex_time'] 	= @ini_get("max_execution_time").'s'; //脚本最大执行时间
    $sys_info['set_time_limit'] = function_exists("set_time_limit") ? true : false;
    $sys_info['domain'] 		= $_SERVER['HTTP_HOST'];
    $sys_info['memory_limit']   = ini_get('memory_limit');
    $sys_info['version']   	    = '1.1';//file_get_contents('./Application/Admin/Conf/version.txt');
    //$mysqlinfo = M()->query("SELECT VERSION() as version");
    $sys_info['mysql_version']  = "5.7.0";//$mysqlinfo['version'];
    if(function_exists("gd_info")){
        $gd = gd_info();
        $sys_info['gdinfo'] 	= $gd['GD Version'];
    }else {
        $sys_info['gdinfo'] 	= "未知";
    }
    return $sys_info;
}

function configMenu(){
    return array(
        'shop_info'=>'网站信息',
        'basic'=>'基本设置',
        'sms'=>'短信设置',
        'shopping'=>'购物流程设置',
        'smtp'=>'邮件设置',
        'water'=>'水印设置',
        'distribut'=>'分销设置'
    );

}
function getMenuList($actList)
{
    $menuList = getAllMenu();
    if($actList != 'all'){

    }
    return $menuList;
}
//定义全站所有菜单
function getAllMenu(){
    return	array(
        'system' => array('name'=>'系统设置','icon'=>'fa-cog','sub_menu'=>array(
            array('name'=>'网站设置','act'=>'index','control'=>'System'),
            array('name'=>'友情链接','act'=>'linkList','control'=>'System'),
            array('name'=>'自定义导航','act'=>'navigationList','control'=>'System'),
            array('name'=>'区域管理','act'=>'region','control'=>'Tools'),
            array('name'=>'权限资源列表','act'=>'right_list','control'=>'System'),
        )),
        'access' => array('name' => '权限管理', 'icon'=>'fa-gears', 'sub_menu' => array(
            array('name' => '管理员列表', 'act'=>'index', 'control'=>'Admin'),
            array('name' => '角色管理', 'act'=>'role', 'control'=>'Admin'),
            array('name' => '供应商管理', 'act'=>'supplier', 'control'=>'Admin'),
            array('name' => '管理员日志', 'act'=>'log', 'control'=>'Admin'),
        )),
        'member' => array('name'=>'会员管理','icon'=>'fa-user','sub_menu'=>array(
            array('name'=>'会员列表','act'=>'index','control'=>'User'),
            array('name'=>'会员等级','act'=>'levelList','control'=>'User'),
            array('name'=>'充值记录','act'=>'recharge','control'=>'User'),
            //array('name'=>'会员整合','act'=>'integrate','control'=>'User'),
        )),
        'goods' => array('name' => '商品管理', 'icon'=>'fa-book', 'sub_menu' => array(
            array('name' => '商品分类', 'act'=>'categoryList', 'control'=>'Goods'),
            array('name' => '商品列表', 'act'=>'goodsList', 'control'=>'Goods'),
            array('name' => '商品类型', 'act'=>'goodsTypeList', 'control'=>'Goods'),
            array('name' => '商品规格', 'act' =>'specList', 'control' => 'Goods'),
            array('name' => '商品属性', 'act'=>'goodsAttributeList', 'control'=>'Goods'),
            array('name' => '品牌列表', 'act'=>'brandList', 'control'=>'Goods'),
            array('name' => '商品评论','act'=>'index','control'=>'Comment'),
            array('name' => '商品咨询','act'=>'ask_list','control'=>'Comment'),
        )),
        'order' => array('name' => '订单管理', 'icon'=>'fa-money', 'sub_menu' => array(
            array('name' => '订单列表', 'act'=>'index', 'control'=>'Order'),
            array('name' => '发货单', 'act'=>'delivery_list', 'control'=>'Order'),
            //array('name' => '快递单', 'act'=>'express_list', 'control'=>'Order'),
            array('name' => '退货单', 'act'=>'return_list', 'control'=>'Order'),
            array('name' => '添加订单', 'act'=>'add_order', 'control'=>'Order'),
            array('name' => '订单日志', 'act'=>'order_log', 'control'=>'Order'),
        )),
        'promotion' => array('name' => '促销管理', 'icon'=>'fa-bell', 'sub_menu' => array(
            array('name' => '抢购管理', 'act'=>'flash_sale', 'control'=>'Promotion'),
            array('name' => '团购管理', 'act'=>'group_buy_list', 'control'=>'Promotion'),
            array('name' => '商品促销', 'act'=>'prom_goods_list', 'control'=>'Promotion'),
            array('name' => '订单促销', 'act'=>'prom_order_list', 'control'=>'Promotion'),
            array('name' => '代金券管理','act'=>'index', 'control'=>'Coupon'),
        )),
        'Ad' => array('name' => '广告管理', 'icon'=>'fa-flag', 'sub_menu' => array(
            array('name' => '广告列表', 'act'=>'adList', 'control'=>'Ad'),
            array('name' => '广告位置', 'act'=>'positionList', 'control'=>'Ad'),
        )),
        'content' => array('name' => '内容管理', 'icon'=>'fa-comments', 'sub_menu' => array(
            array('name' => '文章列表', 'act'=>'articleList', 'control'=>'Article'),
            array('name' => '文章分类', 'act'=>'categoryList', 'control'=>'Article'),
            //array('name' => '帮助管理', 'act'=>'help_list', 'control'=>'Article'),
            //array('name' => '公告管理', 'act'=>'notice_list', 'control'=>'Article'),
            array('name' => '专题列表', 'act'=>'topicList', 'control'=>'Topic'),
        )),
        'weixin' => array('name' => '微信管理', 'icon'=>'fa-weixin', 'sub_menu' => array(
            array('name' => '公众号管理', 'act'=>'index', 'control'=>'Wechat'),
            array('name' => '微信菜单管理', 'act'=>'menu', 'control'=>'Wechat'),
            array('name' => '文本回复', 'act'=>'text', 'control'=>'Wechat'),
            array('name' => '图文回复', 'act'=>'img', 'control'=>'Wechat'),
            array('name' => '组合回复', 'act'=>'nes', 'control'=>'Wechat'),
            //array('name' => '抽奖活动', 'act'=>'nes', 'control'=>'Wechat'),
        )),
        'theme' => array('name' => '模板管理', 'icon'=>'fa-adjust', 'sub_menu' => array(
            array('name' => 'PC端模板', 'act'=>'templateList?t=pc', 'control'=>'Template'),
            array('name' => '手机端模板', 'act'=>'templateList?t=mobile', 'control'=>'Template'),
        )),
        /*
                    'distribut' => array('name' => '分销管理', 'icon'=>'fa-cubes', 'sub_menu' => array(
                            array('name' => '分销商品列表', 'act'=>'goods_list', 'control'=>'Distribut'),
                            array('name' => '分销商列表', 'act'=>'distributor_list', 'control'=>'Distribut'),
                            array('name' => '分销关系', 'act'=>'tree', 'control'=>'Distribut'),
                            array('name' => '分销设置', 'act'=>'set', 'control'=>'Distribut'),
                            array('name' => '提现申请', 'act'=>'withdrawals', 'control'=>'Distribut'),
                            array('name' => '分成日志', 'act'=>'rebate_log', 'control'=>'Distribut'),
                            array('name' => '汇款记录', 'act'=>'remittance', 'control'=>'Distribut'),
                    )),
        */
        'tools' => array('name' => '插件工具', 'icon'=>'fa-plug', 'sub_menu' => array(
            array('name' => '插件列表', 'act'=>'index', 'control'=>'Plugin'),
            array('name' => '数据备份', 'act'=>'index', 'control'=>'Tools'),
            array('name' => '数据还原', 'act'=>'restore', 'control'=>'Tools'),
        )),
        'count' => array('name' => '统计报表', 'icon'=>'fa-signal', 'sub_menu' => array(
            array('name' => '销售概况', 'act'=>'index', 'control'=>'Report'),
            array('name' => '销售排行', 'act'=>'saleTop', 'control'=>'Report'),
            array('name' => '会员排行', 'act'=>'userTop', 'control'=>'Report'),
            array('name' => '销售明细', 'act'=>'saleList', 'control'=>'Report'),
            array('name' => '会员统计', 'act'=>'user', 'control'=>'Report'),
            array('name' => '财务统计', 'act'=>'finance', 'control'=>'Report'),
        )),
        'pickup' => array('name' => '自提点管理', 'icon'=>'fa-anchor', 'sub_menu' => array(
            array('name' => '自提点列表', 'act'=>'index', 'control'=>'Pickup'),
            array('name' => '添加自提点', 'act'=>'add', 'control'=>'Pickup'),
        ))
    );
}



