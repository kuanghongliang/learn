<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/10/28
 * Time: 11:03
 */

namespace app\common\utils;


class SmsUtils {
    public static $DEBUG = false;
    public static $USERNAME = '';
    public static $PASSWORD = '';
    public function realSendSms($mobiles, $content,$end = '【屋奇良宿】')
    {
        $password = md5($this::$USERNAME."".md5($this::$PASSWORD));
        $url = "http://www.jc-chn.cn/smsSend.do?";
        $content = str_replace('【安途】','',$content);
        $sign = ($end == '【安途短租】') ? $end : '【屋奇良宿】';
        $param = http_build_query(
            array(
                'username'=>$this::$USERNAME,
                'password'=>$password,
                'mobile'=>$mobiles,
                'content'=>$sign.$content,
            )
        );

        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$param);
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }
}