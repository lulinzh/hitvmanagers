<?php
namespace Common\Lib\Hitv;
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 16/6/30
 * Time: 下午9:56
 */
class ActionsString
{
    //Action常量
    const USER_LOGIN = 'user_login';  //登录
    const USER_INFO_GET = 'get_user_info';  //获取用户信息
    const USER_FULL_INFO_GET = 'get_user_full_info';  //获取用户完整信息
    const USER_SIGN_UP = 'user_sign_up'; //注册
    const USER_DEVICE_LIST_GET = 'get_user_device_list'; //获取用户绑定设备

    const PHONE_GET_SPLASH = 'phone_get_splash'; //获取首屏广告

    const AUTH_WEB_APP = 'get_web_app_info'; //获取网页应用信息
    const CONFIRM_AUTH = 'confirm_web_app';  //确认授权

    //const AUTH_TV_LOGIN = 'tv_login'; //电视扫码授权登录

    const TV_REQ_HITV = 'tv_req_hitv'; //电视请求hitv客户端

    //TV请求Hitv类型
    const T2H_GET_SESSION_STATE = 't2h_get_session_status'; //电视机获取输入状态
    const H2T_GET_SESSION_INFO = 'h2t_get_session_info'; //hitv客户端获取机顶盒信息
    const H2T_POST_SESSION = 'h2t_post_session'; //hitv客户端提交操作

    //const T2H_TEXT_INPUT = 't2h_text_input'; //请求文字输入


}
