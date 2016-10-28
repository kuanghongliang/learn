<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/10/28
 * Time: 11:26
 */

namespace app\common\utils;
use app\common\utils\SmsUtils;
use think\Db;
class Common {

    public static function sendMobileCode($mobile,$content,$code,$sessionId)
    {
        $client = new SmsUtils();
        $res = $client->realSendSms($mobile,$content);
        if($res > 0){
            $data = ['mobile'=>$mobile,'session_id'=>$sessionId,'add_time'=>time(),'code'=>$code];
            //记录发送的短信
            Db::name('sms_log')->insert($data);
            return true;
        }else{
            return false;
        }
    }
}