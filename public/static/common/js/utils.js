/**
 * Created by hongliang on 2016/10/25.
 */

function ChkUtil() {

}
//手机号验证
ChkUtil.checkMobile = function(tel){
    var reg = /(^1[3|4|5|7|8][0-9]{9}$)/;
    if (reg.test(tel)) {
        return true;
    }else{
        return false;
    };
};
//邮箱验证
ChkUtil.checkEmail = function (str){
    var reg = /^[a-z0-9]([a-z0-9\\.]*[-_]{0,4}?[a-z0-9-_\\.]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+([\.][\w_-]+){1,5}$/i;
    if(reg.test(str)){
        return true;
    }else{
        return false;
    }
};
//错误提示
ChkUtil.showErrorMsg = function(msg){
    alert(msg);
};