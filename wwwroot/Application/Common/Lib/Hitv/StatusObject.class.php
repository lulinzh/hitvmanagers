<?php
/**
 * Created by PhpStorm.
 * @deprecated 使用StatusModel
 * User: Zapper
 * Date: 2016/7/7
 * Time: 10:27
 */
namespace Common\Lib\Hitv;
/**
 * Class StatusObject
 * @deprecated 使用StatusModel
 * @package Common\Lib\Hitv
 */
class StatusObject{
    //未知错误
    const ERROR_UNKNOWN = -1;
    //成功
    const SUCCESS = 0;
    /**
     * @var int 状态码
     */
    public $code = StatusObject::SUCCESS;
    /**
     * @var string 状态返回信息
     */
    public $message = '';
    /**
     * @var bool 是否为严重错误
     */
    public $is_critical = false;

    public function setErrorUnknown(){
        $this->code = StatusObject::ERROR_UNKNOWN;
        $this->message = '未知错误';
    }

    public function setNeedUserId(){
        $this->code = ErrorCode::NEED_USER_ID;
    }

    public function setBossLoginFailed(){
        $this->code = ErrorCode::BOSS_LOGIN_FAILED;
        //$this->message = '未知错误';
    }

    public function setBossVerifyFailed(){
        $this->code = ErrorCode::BOSS_VERIFY_FAILED;
        $this->message = '验证码发送失败';
    }


    public function setUserPhoneIsExisted(){
        $this->code = ErrorCode::USER_PHONE_IS_EXIST;
        $this->message = '此用户已注册';
    }

    public function setUserNotFound(){
        $this->code = ErrorCode::USER_NOT_FOUND;
        $this->message = '找不到该用户的信息';
    }

    public function setUserTokenExpired(){
        $this->code = ErrorCode::USER_TOKEN_EXPIRED;
        $this->message = '验证过期,请重新登录';
    }

    public function setPhoneNumberError(){
        $this->code = ErrorCode::USER_PHONE_ERROR;
        $this->message = '请检查您的手机号';
    }

    public function setNeedSessionId(){
        $this->code = ErrorCode::NEED_HITV_SESSION_ID;
    }

    public function setSessionIsBusy(){
        $this->code = ErrorCode::SESSION_IS_BUSY;
    }

    public function setSessionOperationFailed(){
        $this->code = ErrorCode::SESSION_OPERATION_FAILED;
    }

    public function setNeedTvBoxId(){
        $this->code = ErrorCode::SESSION_OPERATION_FAILED;
    }

    public function setNeedRequestType(){
        $this->code = ErrorCode::NEED_TV_REQUEST_TYPE;
    }

    public function setNeedWebAppId(){
        $this->code = ErrorCode::NEED_WEB_APP_ID;
    }

    public function setWebAppNotFound(){
        $this->code = ErrorCode::WEB_APP_ID_NOT_FOUND;
    }

    public function setNeedAppSecret(){
        $this->code = ErrorCode::NEED_WEB_APP_SECRET;
    }

    public function setAppSecretNotEqual(){
        $this->code = ErrorCode::WEB_APP_SECRET_NOT_EQUAL;
    }

    public function setNeedAppOrderId(){
        $this->code = ErrorCode::NEED_WEB_APP_ORDER_ID;
    }

    public function setNeedOrderTitle(){
        $this->code = ErrorCode::NEED_WEB_APP_ORDER_TITLE;
    }

    public function setNeedOrderPrice(){
        $this->code = ErrorCode::NEED_WEB_APP_ORDER_PRICE;
    }


    public function setNeedOrderId(){
        $this->code = ErrorCode::NEED_ORDER_ID;
    }

    public function setOrderNotFound(){
        $this->code = ErrorCode::ORDER_NOT_FOUND;
    }

    public function setOrderOperationFailed(){
        $this->code = ErrorCode::ORDER_OPERATION_FAILED;
    }

    public function setNeedRedirectURI(){
        $this->code = ErrorCode::NEED_REDIRECT_URI;
    }

    public function setNeedInfoLevel(){
        $this->code = ErrorCode::NEED_INFO_LEVEL;
    }

    public function setNeedAuthToken(){
        $this->code = ErrorCode::NEED_AUTH_TOKEN;
    }

    public function setAuthTokenNotFound(){
        $this->code = ErrorCode::AUTH_TOKEN_NOT_FOUND;
    }

    public function setNeedAccessCode(){
        $this->code = ErrorCode::NEED_ACCESS_CODE;
    }

    public function setAccessCodeNotFound(){
        $this->code = ErrorCode::ACCESS_CODE_NOT_FOUND;
    }
}

class ErrorCode
{
    const NEED_USER_ID = 2000;
    const BOSS_LOGIN_FAILED = 2100;

    const USER_PHONE_ERROR = 2101;
    const USER_NOT_FOUND = 2102;
    const USER_TOKEN_EXPIRED = 2103;

    const USER_PHONE_IS_EXIST = 2200;
    const BOSS_VERIFY_FAILED = 2201;

    const SESSION_NOT_START = 3000;
    const SESSION_IS_BUSY = 3001;
    const SESSION_OPERATION_FAILED = 3002;
    const NEED_TV_BOX_ID = 3003;
    const NEED_HITV_SESSION_ID = 3004;
    const NEED_TV_REQUEST_TYPE = 3005;

    const NEED_WEB_APP_ID = 4000;
    const WEB_APP_ID_NOT_FOUND = 4001;
    const NEED_WEB_APP_SECRET = 4002;
    const WEB_APP_SECRET_NOT_EQUAL = 4003;



    const NEED_WEB_APP_ORDER_ID = 4100;
    const NEED_WEB_APP_ORDER_TITLE = 4101;
    const NEED_WEB_APP_ORDER_PRICE = 4102;
    const ORDER_OPERATION_FAILED = 4200;


    //Oauth 验证错误
    const NEED_REDIRECT_URI = 4300;
    const NEED_INFO_LEVEL = 4301;
    const NEED_AUTH_TOKEN = 4302;
    const AUTH_TOKEN_NOT_FOUND = 4303;
    const NEED_ACCESS_CODE = 4304;
    const ACCESS_CODE_NOT_FOUND = 4305;

    const NEED_ORDER_ID = 5000;
    const ORDER_NOT_FOUND = 5001;
}

class HitvSessionStatusCode {
    const NOT_FOUND = -1;

    const WAITING_TARGET_JOIN = 0;          //等待用户扫码
    const WAITING_TARGET_COMPLETE = 1;      //等待用户输入
    const SUCCESS = 2;                      //用户输入完成
    const CANCEL_BY_TARGET = 3;             //用户输入取消
    const CANCEL_BY_SELF = 4;               //用户输入取消
    const EXPIRED = 5;                      //会话过期
}