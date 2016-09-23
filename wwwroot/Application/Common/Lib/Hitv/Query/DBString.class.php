<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/7
 * Time: 16:53
 */
namespace Common\Lib\Hitv\Query;

class DBString
{
    /**
     * @deprecated
     * HitvId 映射表
     */
    const HITV_USER = 'hitv_user';

    /**
     * @deprecated
     * 自有APP 资料表
     */
    const HITV_APP = 'hitv_app';


    const SESSION = 'hitv_req_session';
    const LX_SESSION = 'lx_hitv_req_session';
//    const USER = 'hitv_user';
//    const LX_USER = 'lx_hitv_user';
    const DEVICE = 'hitv_device';
    const LX_DEVICE = 'lx_hitv_device';
    const DEVICE_TYPE = 'hitv_device_type';
    const LX_DEVICE_TYPE = 'lx_hitv_device_type';
    const WEB_APP = 'hitv_webapp';
    const LX_WEB_APP = 'lx_hitv_webapp';
    const ORDER = 'hitv_order';
    const LX_ORDER = 'lx_hitv_order';
    const AUTH_REQUEST = 'hitv_auth_request';
    const AUTH_CODE = 'hitv_auth_code';
    const ACCESS_TOKEN = 'hitv_access_token';
}