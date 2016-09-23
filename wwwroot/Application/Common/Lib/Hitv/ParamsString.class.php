<?php
namespace Common\Lib\Hitv;
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 16/6/30
 * Time: 下午9:56
 */
class ParamsString
{
    //输入API版本
    const API_VERSION = 'api_ver';
    //输入参数名
    const ACTION = 'action';

    const USER_ID = 'user_id';
    const USER_NICKNAME = 'user_nickname';
    const USER_PHONE = 'user_phone';
    const USER_PASSWORD = 'user_password';
    const USER_TOKEN = 'user_token';

    const TV_QR_CODE_ID = 'tv_qr_code_id';
    const SMS_CODE = 'sms_code';

    const TV_BOX_ID = 'tv_box_id';
    const HITV_SESSION_ID = 'hitv_session_id';
    const HITV_SESSION_ACTION_TYPE = 'hitv_session_action_type';
    const HITV_SESSION_ACTION_PARAM = 'hitv_session_action_prarm';

    const DEVICE_ID = 'device_id';
    const DEVICE_TYPE_ID = 'device_type_id';
    const DEVICE_UNIQUE_ID = 'device_unique_id';

    const WEB_APP_ID = 'web_app_id';
    const WEB_APP_SECRET = 'web_app_secret';
    const WEB_APP_ORDER_ID = 'web_app_order_id';
    const WEB_APP_ORDER_TITLE = 'web_app_order_title';
    const WEB_APP_ORDER_PRICE = 'web_app_order_price';
}

class HitvDBString
{
    const SESSION = 'hitv_req_session';
    const LX_SESSION = 'lx_hitv_req_session';
    const USER = 'hitv_user';
    const LX_USER = 'lx_hitv_user';
    const DEVICE = 'hitv_device';
    const LX_DEVICE = 'lx_hitv_device';
    const DEVICE_TYPE = 'hitv_device_type';
    const LX_DEVICE_TYPE = 'lx_hitv_device_type';
}