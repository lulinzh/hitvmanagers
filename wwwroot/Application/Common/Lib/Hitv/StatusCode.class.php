<?php
namespace Common\Lib\Hitv;
/**
 * @Deprecated
 * Created by PhpStorm.
 * User: Zapper
 * Date: 16/6/30
 * Time: 下午9:56
 */
class StatusCode
{

    const ERROR_UNKNOWN = -1;

    //成功
    const SUCCESS = 0;
    //1 系统级
    //API版本过低
    const ERROR_API_VERSION_TOO_LOW = 1000;
    //缺少action参数
    const ERROR_NEED_ACTION = 1010;

    //2 用户
    //缺少用户ID 严重错误
    const ERROR_NEED_USER_ID = 2000;
    //缺少用户名
    const ERROR_NEED_USER_NAME = 2001;
    //缺少密码
    const ERROR_NEED_USER_PASSWORD = 2002;
    //缺少手机号
    const ERROR_NEED_USER_PHONE = 2003;
    //缺少验证码
    const ERROR_NEED_SMS = 2004;
    //缺少Token
    const ERROR_NEED_USER_TOKEN = 2005;
    //二维码信息不合法
    const ERROR_NEED_QR_CODE = 2006;
    //用户未注册或密码错误
    const ERROR_USER_NOT_SIGN_UP = 2100;
    //手机号已注册
    const ERROR_PHONE_IS_EXIST = 2101;

    const USER_NOT_FOUND = 2102;

    const USER_TOKEN_EXPIRED = 2103;

    //3 机顶盒
    const ERROR_TV_BOX_NOT_ONLINE = 3000;
    const ERROR_TV_BOX_BUSY = 3001;

    const ERROR_NEED_TV_BOX_ID = 3002;
    const ERROR_NEED_TV_REQUEST_TYPE = 3003;
    const ERROR_NEED_HITV_SESSION_ID = 3004;

    const ERROR_NEED_HITV_ACTION = 3100;
    const ERROR_NEED_HITV_ACTION_PARAM = 3101;

    //4 设备
    const ERROR_NEED_DEVICE_TYPE = 4000;
    const ERROR_NEED_DEVICE_UNIQUE_ID = 4001;


    const STATUS_HITV_SESSION_NOT_FOUND = -1;
    const STATUS_HITV_SESSION_WAITING_TARGET = 0; //等待用户扫码
    const STATUS_HITV_SESSION_WAITING_INPUT = 1; //等待用户输入
    const STATUS_HITV_SESSION_SUCCESS = 2; //用户输入完成
    const STATUS_HITV_SESSION_CANCEL = 3; //用户输入取消
    const STATUS_HITV_SESSION_EXPIRED = 4; //会话过期

}