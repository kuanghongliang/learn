<?php
/**
 * Created by PhpStorm.
 * User: hongliang
 * Date: 2016/10/26
 * Time: 14:02
 */
namespace app\common\utils;

class StringUtils {

    /*
     * 手机号或固话验证
     */
    public  static function  checkTelOrMobile($str)
    {
        return preg_match("/(1[35]\d{9}$|^0?((10)|(2\d{1})|([3-9]\d{2}))-)?[1-9]\d{6,7}(-\d{3,4})?$/", $str);
    }

    /*
     * 手机号验证
     */
    public static function checkMobile($str)
    {
       return  preg_match('/1[34578]\d{9}$/',$str);
    }

    /*
     * 手机号或邮箱验证
     */
    public static function checkMobileOrEmail($str)
    {
        return preg_match("/^(13[0-9]{9})|(15[^4,\\D][0-9]{8})|(18[0-9]{9})|(17[0-9]{9})|((\w{1,20}@\w{1,20}.[a-z]{2,5}))$/",$str);
    }
}