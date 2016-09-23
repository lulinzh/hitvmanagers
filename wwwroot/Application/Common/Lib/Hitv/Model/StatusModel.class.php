<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/16
 * Time: 13:49
 */

namespace Common\Lib\Hitv\Model;

class StatusModel {
    public $code;
    public $message;
    public function __construct($set_code = StatusModel::SUCCESS, $set_message = ''){
        $this->code = $set_code;
        $this->message = $set_message;
    }

    /**
     * @return bool
     */
    public function noError(){
        return $this->code == 0;
    }

    public function operationFailed(){
        $this->code = self::UNKNOWN;
        $this->message = '操作失败';
    }

    public function needUserId(){
        $this->code = self::NEED_USER_ID;
        $this->message = '缺少user_id参数';
    }

    public function openIdIsIllegal(){
        $this->code = self::OPEN_ID_IS_ILLEGAL;
        $this->message = 'openid非法';
    }

    public function needUserPhone(){
        $this->code = self::NEED_USER_PHONE;
        $this->message = '请检查您的手机号码';
    }

    public function needUserPassword(){
        $this->code = self::NEED_USER_PASSWORD;
        $this->message = '请检查您的密码';
    }

    public function needUserNickName(){
        $this->code = self::NEED_USER_NICKNAME;
        $this->message = '请检查您的昵称';
    }

    public function needVerifyId(){
        $this->code = self::NEED_VERIFY_ID;
        $this->message = '未提交验证码流水号';
    }

    public function needVerifyCode(){
        $this->code = self::NEED_VERIFY_CODE;
        $this->message = '未提交验证码';
    }

    public function bossVerifyFailed($string){
        $this->code = self::BOSS_VERIFY_FAILED;
        if($string){
            $this->message = $string;
        }else{
            $this->message = '验证码发送失败';
        }

    }

    public function needVerifyType(){
        $this->code = self::NEED_VERIFY_TYPE;
        $this->message = '未提交验证类型或提交格式错误';
    }

    public function bossLoginFailed($string){
        $this->code = self::BOSS_LOGIN_FAILED;
        if($string){
            $this->message = $string;
        }else{
            $this->message = '登录失败,服务器维护中';
        }
    }


    public function userPhoneIsExisted(){
        $this->code = self::USER_PHONE_IS_EXIST;
        $this->message = '此用户已注册';
    }

    public function userNotFound(){
        $this->code = self::USER_NOT_FOUND;
        $this->message = '找不到此用户的信息';
    }

    public function needUserToken(){
        $this->code = self::NEED_USER_TOKEN;
        $this->message = '缺少token参数';
    }

    /** * Token 超时*/
    public function userTokenExpired(){
        $this->code = self::NEED_USER_TOKEN;
        $this->message = 'token过期,请重新登录';
    }

    /** * Token 不合法*/
    public function userTokenIllegal(){
        $this->code = self::USER_TOKEN_ILLEGAL;
        $this->message = 'token非法,请重新登录';
    }

    public function sessionNotFound(){
        $this->code = self::SESSION_NOT_FOUND;
        $this->message = '无此会话,请重新进入扫码页面';
    }

    public function sessionTokenExpired(){
        $this->code = self::SESSION_TOKEN_EXPIRED;
        $this->message = '会话已经过期,请重新进入扫码页面';
    }

    public function sessionTokenIllegal(){
        $this->code = self::SESSION_TOKEN_ILLEGAL;
        $this->message = 'token非法,请重新进入扫码页面';
    }

    public function needSessionType(){
        $this->code = self::NEED_SESSION_TYPE;
        $this->message = '未提交会话类型';
    }

    public function sessionIsEnd(){
        $this->code = self::SESSION_IS_END;
        $this->message = '会话已经结束';
    }

    public function needDeviceType(){
        $this->code = self::NEED_DEVICE_TYPE;
        $this->message = '未提交设备类型';
    }

    public function needDeviceId(){
        $this->code = self::NEED_DEVICE_ID;
        $this->message = '未提交设备编号';
    }

    public function needOrderId(){
        $this->code = self::NEED_ORDER_ID;
        $this->message = '未提交订单编号';
    }

    public function orderNotFound(){
        $this->code = self::ORDER_NOT_FOUND;
        $this->message = '找不到此订单信息';
    }

    public function orderIsPay(){
        $this->code = self::ORDER_IS_PAY;
        $this->message = '此订单已经付过款,无需再次付款';
    }

    public function needOrderPrice(){
        $this->code = self::NEED_ORDER_PRICE;
        $this->message = '缺少价格参数';
    }

    public function needAppOrderId(){
        $this->code = self::NEED_APP_ORDER_ID;
        $this->message = '缺少应用内订单ID';
    }

    public function needOrderTitle(){
        $this->code = self::NEED_ORDER_TITLE;
        $this->message = '缺少标题参数';
    }

    public function needWebAppId(){
        $this->code = self::NEED_WEB_APP_ID;
        $this->message = '缺少APP KEY参数';
    }
    public function webAppNotFound(){
        $this->code = self::WEB_APP_NOT_FOUND;
        $this->message = '找不到应用信息';
    }

    public function webAppIdNotEqual(){
        $this->code = self::WEB_APP_ID_NOT_EQUAL;
        $this->message = 'APP ID 不匹配';
    }

    public function webAppSecretNotEqual(){
        $this->code = self::WEB_APP_SECRET_NOT_EQUAL;
        $this->message = 'APP SECRET 不匹配';
    }

    public function appSecretNotEqual(){
        $this->code = self::APP_SECRET_NOT_EQUAL;
        $this->message = 'APP SECRET 不匹配';
    }

    public function needAppKey(){
        $this->code = self::NEED_APP_KEY;
        $this->message = '缺少APP_KEY参数';
    }

    public function needAppId(){
        $this->code = self::NEED_APP_ID;
        $this->message = '缺少APP_ID参数';
    }

    public function appNotFound(){
        $this->code = self::APP_NOT_FOUND;
        $this->message = '找不到应用信息';
    }

    public function signVerifyFailed(){
        $this->code = self::SIGN_VERIFY_FAILED;
        $this->message = 'sign参数验证失败';
    }

    public function createTokenFailed(){
        $this->code = self::TOKEN_CREATE_FAILED;
        $this->message = '系统错误';
    }

    const UNKNOWN = -1;
    const SUCCESS = 0;

    const TOKEN_CREATE_FAILED = 100;

    //1 验证错误
    const SIGN_VERIFY_FAILED = 1000;

    //2 用户
    const NEED_USER_ID = 2000;
    const USER_NOT_FOUND = 2001;
    const USER_PHONE_IS_EXIST = 2002;
    const NEED_USER_PHONE = 2003;
    const NEED_USER_PASSWORD = 2004;
    const NEED_USER_NICKNAME = 2005;
    const NEED_VERIFY_ID = 2006;
    const NEED_VERIFY_CODE = 2007;
    const NEED_VERIFY_TYPE = 2008;

    const BOSS_LOGIN_FAILED = 2010;
    const BOSS_VERIFY_FAILED = 2011;

    const OPEN_ID_IS_ILLEGAL = 2020;
    //21 用户Token相关
    const NEED_USER_TOKEN = 2100;
    const USER_TOKEN_ILLEGAL = 2101;
    const USER_TOKEN_EXPIRED = 2102;

    //3 会话
    const NEED_SESSION_ID = 3000;
    const SESSION_NOT_FOUND = 3001;
    const NEED_SESSION_TYPE = 3002;
    const SESSION_IS_END = 3003;

    //31 会话Token相关
    const NEED_SESSION_TOKEN = 3100;
    const SESSION_TOKEN_ILLEGAL = 3101;
    const SESSION_TOKEN_EXPIRED = 3102;

    //4 设备
    const NEED_DEVICE_ID = 4000;
    const NEED_DEVICE_TYPE = 4001;

    //5 订单
    const NEED_ORDER_ID = 5000;
    const ORDER_NOT_FOUND = 5001;
    const ORDER_IS_PAY = 5100;
    const NEED_ORDER_PRICE = 5002;
    const NEED_ORDER_TITLE = 5003;
    const NEED_APP_ORDER_ID = 5004;

    //10 app
    const NEED_APP_ID = 10000;
    const APP_NOT_FOUND = 10001;
    const APP_ID_NOT_EQUAL = 10002;
    const NEED_APP_KEY = 10003;
    const APP_SECRET_NOT_EQUAL = 10004;

    //11 webApp
    const NEED_WEB_APP_ID = 11000;
    const WEB_APP_NOT_FOUND = 11001;
    const WEB_APP_ID_NOT_EQUAL = 11002;
    const WEB_APP_SECRET_NOT_EQUAL = 11003;

}