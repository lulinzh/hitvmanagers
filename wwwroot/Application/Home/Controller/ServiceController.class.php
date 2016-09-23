<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use Home\Controller\Base\HomeController;
use Common\Lib\Hitv\ParamsString;
use Common\Lib\Hitv\ActionsString;
use Common\Lib\Hitv\HitvActionsString;
use Common\Lib\Hitv\StatusCode;
use Common\Lib\Hitv\HitvDBString;
/**
 * 前台首页控制器
 * 主要获取首页聚合数据
 */
class ServiceController extends HomeController
{
    const IS_DEBUG = true;

    /**
     * 发送curl请求，post方式
     * @param $url 请求地址
     * @param $data 发送参数
     * @return mixed|string
     */
    function httpPost($url, $data)
    {
        if (!function_exists('curl_init')) {
            return '';
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        # curl_setopt( $ch, CURLOPT_HEADER, 1);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $data = curl_exec($ch);
        if (!$data) {
            error_log(curl_error($ch));
        }
        curl_close($ch);
        return $data;
    }

    function post_test(){
        $fuck->appkey = '';
    }

    public function web_api(){
        //初始化返回状态
        $result->status->code = StatusCode::SUCCESS;
        //抓取Action参数
        $action = I(ParamsString::ACTION, '');
        $api_secret = I('api_secret', '');
        //TODO:判断api_secret是否存在
        if($api_secret != 1){
            $this->setStatus($result,'api_secret_not_found', '', true);
        }else{
            if($action == ActionsString::USER_INFO_GET) {
                //TODO:授权表查询
                //获取用户信息by id
                $user_id =  I(ParamsString::USER_ID, '');
                $this->getUserInfo($result, $user_id);
            }else if($action == ActionsString::USER_FULL_INFO_GET){
                //TODO:授权表查询
                //获取用户信息by id
                $user_id =  I(ParamsString::USER_ID, '');
                $user_token =  I(ParamsString::USER_TOKEN, '');
                $this->getUserFullInfo($result, $user_id, $user_token);
            }
        }
        $this->ajaxReturn($result, 'JSON');
    }

    public function hitv(){
        //初始化返回状态
        $result->status->code = StatusCode::SUCCESS;
        //获取API版本号
        $result->api_ver = I(ParamsString::API_VERSION, 0);
        //抓取Action参数
        $action = I(ParamsString::ACTION, '');
        //TODO:接口验证
        //if($result->api_ver < 1){
           // $this->setStatus($result, StatusCode::ERROR_API_VERSION_TOO_LOW, '您的Hitv客户端版本太低，请重新下载。');
       // }else{
            //登录
            if($action == ActionsString::USER_LOGIN) {
                $user_phone =  I(ParamsString::USER_PHONE, '');
                $user_password =  I(ParamsString::USER_PASSWORD, '');
                $this->login($result, $user_phone, $user_password);
            }else if($action == ActionsString::USER_INFO_GET){
                //获取用户信息by id
                $user_id =  I(ParamsString::USER_ID, '');
                $this->getUserInfo($result, $user_id);
            }else if($action == ActionsString::USER_FULL_INFO_GET){
                //获取用户信息by id
                $user_id =  I(ParamsString::USER_ID, '');
                $user_token =  I(ParamsString::USER_TOKEN, '');
                $this->getUserFullInfo($result, $user_id, $user_token);
            }else if($action == ActionsString::USER_SIGN_UP){
                //注册
                $user_phone =  I(ParamsString::USER_PHONE, '');
                $user_password =  I(ParamsString::USER_PASSWORD, '');
                $nickname =  I(ParamsString::USER_NICKNAME, '');
                $sms_code = I(ParamsString::SMS_CODE, '');
                $this->signUp($result, $user_phone, $sms_code, $nickname, $user_password);
            }else if($action == ActionsString::TV_REQ_HITV) {
                //电视发起hitv请求
                $req_type =  I(ParamsString::HITV_SESSION_ACTION_TYPE, '');
                $tv_box_id =  I(ParamsString::TV_BOX_ID, '');
                $user_id = I(ParamsString::USER_ID, '');
                $set_request_param = I(ParamsString::HITV_SESSION_ACTION_PARAM, '');
                $this->startHitvSessionByQRCode($result, $tv_box_id, $req_type, $user_id, $set_request_param);
            }else if($action == ActionsString::T2H_GET_SESSION_STATE) {
                //电视轮询请求状态
                $session_id =  I(ParamsString::HITV_SESSION_ID, '');
                $tv_box_id =  I(ParamsString::TV_BOX_ID, '');
                $this->getHitvSessionByTv($result, $tv_box_id, $session_id);
            }else if($action == ActionsString::H2T_GET_SESSION_INFO) {
                //手机查询hitv请求会话信息
                $user_id =  I(ParamsString::USER_ID, '');
                $user_token =  I(ParamsString::USER_TOKEN, '');
                $hitv_session_id = I(ParamsString::HITV_SESSION_ID, '');
                $this->getHitvSessionByHitv($result, $user_id, $user_token, $hitv_session_id);
            }else if($action == ActionsString::H2T_POST_SESSION) {
                //手机提交会话
                $user_id =  I(ParamsString::USER_ID, '');
                $user_token =  I(ParamsString::USER_TOKEN, '');
                $hitv_session_id = I(ParamsString::HITV_SESSION_ID, '');
//                $hitv_action_type = I(ParamsString::HITV_SESSION_ACTION_TYPE, '');
                $hitv_action_param = I(ParamsString::HITV_SESSION_ACTION_PARAM, '');
                $this->postHitvSessionByHitv($result, $user_id, $user_token, $hitv_session_id, $hitv_action_param);
            }else if($action == ActionsString::USER_DEVICE_LIST_GET){
                $user_id =  I(ParamsString::USER_ID, '');
                $user_token =  I(ParamsString::USER_TOKEN, '');
                $this->getUserDevicesList($result, $user_id, $user_token);
            }else if($action == ActionsString::USER_DEVICE_LIST_GET){
                $user_id =  I(ParamsString::USER_ID, '');
                $user_token =  I(ParamsString::USER_TOKEN, '');
                $this->getUserDevicesList($result, $user_id, $user_token);
            }else if($action == ActionsString::PHONE_GET_SPLASH){
                $this->getPhoneSplash($result);
            }else if($action == 'test_login'){
                $user_id =  I('hitv_user_id', '');
                $user_token =  I('hitv_user_token', '');
                if($user_id && $user_token){

                    echo "hello!,user ".$user_id;
                    exit;
                }else{
                    $url = "hitv://hitv_need_login?web_app_id=1&url='www.baidu.com'";
                    //$url = 'http:\\\\baidu.com';
                    header("Location: $url");
                    exit;
                }
            }
            else{
                //缺少action参数
                $this->setStatus($result, StatusCode::ERROR_NEED_ACTION, '', true);
            }
        //}
//        if(self::IS_DEBUG){
//            $result->action = $action;
//        }
        $this->ajaxReturn($result, 'JSON');
    }

    function getPhoneSplash($result){
        $result->result->web_url = 'http://baidu.com';
        $result->result->splash_url = '';
    }

    /**
     * 设置返回状态
     * @param $result
     * @param int $set_status_code 设置的状态码
     * @param string $set_status_message 设置的状态描述
     * @param bool|false $set_critical 设置是否为严重错误
     */
    function setStatus($result, $set_status_code, $set_status_message = '', $set_critical = false){
        $result->status->code = $set_status_code;
        $result->status->message = $set_status_message;
        $result->status->is_critical = $set_critical;
    }

    /**
     * 检测参数是否为空
     * @param $result
     * @param $param
     * @param int $set_if_null_code 如果为空时返回的代码
     * @param string $set_if_null_word 如果为空时返回的信息
     * @param bool|false  $set_critical 设置是否为严重错误
     * @return bool 检测通过
     */
    function paramsCheck($result, $param, $set_if_null_code, $set_if_null_word, $set_critical = false){
        if(!$param){
            $result->status->code = $set_if_null_code;
            $result->status->message = $set_if_null_word;
            return false;
        }
        return true;
    }

    /**
     * 用户注册
     * @param $result
     * @param string $set_phone 手机号
     * @param string $set_sms_code 验证码
     * @param string $set_nickname 昵称
     * @param string $set_password 密码
     */
    function signUp($result, $set_phone, $set_sms_code , $set_nickname , $set_password){
        if( $this->paramsCheck($result, $set_phone, StatusCode::ERROR_NEED_USER_PHONE, '请输入手机号') &&
           $this->paramsCheck($result, $set_sms_code, StatusCode::ERROR_NEED_SMS, '请输入验证码') &&
           $this->paramsCheck($result, $set_nickname, StatusCode::ERROR_NEED_USER_NAME, '请输入昵称') &&
           $this->paramsCheck($result, $set_password, StatusCode::ERROR_NEED_USER_PASSWORD, '请输入手机号')
        ) {
            //TODO:手机号未注册过
            if(true){
                $result->result->user_id = 1000;
                $result->result->user_token = 'fuck';
            }else{
                $this->setStatus($result, StatusCode::ERROR_PHONE_IS_EXIST, '手机号已注册');
            }
        }
    }

    /**
     * 登录
     * @param $result
     * @param string $set_name 输入用户名
     * @param string $set_password 输入密码
     */
    function  login($result, $set_name = '', $set_password = ''){
        if(!$set_name){
            $this->setStatus($result, StatusCode::ERROR_NEED_USER_PHONE,  '请先输入Hitv账号' );
            return;
        }
        if(!$set_password){
            $this->setStatus($result, StatusCode::ERROR_NEED_USER_PASSWORD,  '请先输入密码' );
            return;
        }
        //查询用户
        $sqlStr = ParamsString::USER_PHONE . "='{$set_name}' AND " . ParamsString::USER_PASSWORD . "='{$set_password}'";
        $user = M(HitvDBString::USER)
            ->field('id AS user_id, user_nickname')
            ->where($sqlStr)
            ->find();
        if($user){
            //查到对应用户
            $result->result = $user;
            //TODO:添加token
        }else{
            //查不到用户
            $this->setStatus($result, StatusCode::ERROR_USER_NOT_SIGN_UP,  '用户未注册或密码错误' );
        }
    }



    /** 根据id获取用户信息
     * @param $result
     * @param string $set_id 用户uid
     */
    function getUserInfo($result, $set_id = ''){
        if(!$set_id){
            $this->setStatus($result, StatusCode::ERROR_NEED_USER_ID,  '', true );
            return;
        }
        $sqlStr = "id='{$set_id}'";// AND " . ParamsString::USER_TOKEN . "='{$set_token}'";
        $user = M(HitvDBString::USER)
            ->field('id AS user_id, user_nickname, user_phone')
            ->where($sqlStr)
            ->find();

        for($i = 3; $i < 7; $i++){
            $user[ParamsString::USER_PHONE][$i] = '*';
        }
        if($user){
            $result->user = $user;
        }
    }

    /**
     * 根据id 和 token 获取详细信息
     * @param $result
     * @param string $set_id
     * @param string $set_token
     * @param bool|false $set_hide_phone
     * @return mixed|null
     */
    function getUserFullInfo($result, $set_id = '', $set_token = '', $set_hide_phone = false){
        if($this->paramsCheck($result, $set_id, StatusCode::ERROR_NEED_USER_ID,  '', true) &&
            $this->paramsCheck($result, $set_token, StatusCode::ERROR_NEED_USER_TOKEN,  '', true)
        ){
            //TODO: 检测token过期
            $sqlStr = "id='{$set_id}'";// AND " . ParamsString::USER_TOKEN . "='{$set_token}'";
            $user = M(HitvDBString::USER)
                ->field('id AS user_id, user_nickname, user_phone')
                ->where($sqlStr)
                ->find();
            if($set_hide_phone){
                for($i = 3; $i < 7; $i++){
                    $user[ParamsString::USER_PHONE][$i] = '*';
                }
            }
            if($user){
                $result->user = $user;
            }
            return $user;
        }
        return null;
    }

//    function tvInputAuth($result, $set_id, $set_token, $set_code_id){
//        if($this->paramsCheck($result, $set_id, StatusCode::ERROR_NEED_USER_ID,  '') &&
//            $this->paramsCheck($result, $set_token, StatusCode::ERROR_NEED_USER_TOKEN,  '') &&
//            $this->paramsCheck($result, $set_code_id, StatusCode::ERROR_NEED_QR_CODE,  '扫码失败，请重新扫码。')
//        ){
//            //TODO:通过code找到对应机顶盒
//            if(true){
//                $result->result->tv_box_name = '123456';
//                $result->result->tv_box_id = '1000';
//                $result->result->token = 'asdasd';
//            }else{
//                $this->setStatus($result, StatusCode::ERROR_TV_BOX_NOT_ONLINE, '机顶盒不在输入状态');
//            }
//        }
//    }

    function startHitvSessionByQRCode($result, $set_tv_box_id, $set_request_type, $set_user_id, $set_request_param){
        if($this->paramsCheck($result, $set_tv_box_id, StatusCode::ERROR_NEED_TV_BOX_ID,  '', true) &&
            $this->paramsCheck($result, $set_request_type, StatusCode::ERROR_NEED_TV_REQUEST_TYPE,  '' , true)
        ){
            $session_info[ParamsString::TV_BOX_ID] = $set_tv_box_id;
            $action_type = 0;
            switch($set_request_type){
                case HitvActionsString::T2H_DEVICE_BINDING:
                    //请求输入
                    $action_type = 0;
                    break;
                case HitvActionsString::T2H_TEXT_INPUT:
                    //请求输入
                    $action_type = 1;
                    break;
                case HitvActionsString::T2H_PAYMENT_AGENT:
                    //请求付款
                    $action_type = 2;
                    break;
            }
            //指定了用户
            if($set_user_id){
                //TODO:推送
                $session_info[ParamsString::USER_ID] = $set_user_id;
                $session_info['status'] = StatusCode::STATUS_HITV_SESSION_WAITING_INPUT;
            }
            if($set_request_param){
                $session_info['action_param'] = $set_request_param;
            }
            $session_info['action_type'] = $action_type;
            $session_id = M(HitvDBString::SESSION)->add($session_info);
            if($session_id){
                $result->result->session_id = $session_id;
            }else{
                $this->setStatus($result, StatusCode::ERROR_UNKNOWN, true);
            }
        }
    }

    function getHitvSessionByHitv($result, $set_user_id, $set_user_token, $set_session_id){
        if($this->paramsCheck($result, $set_user_id, StatusCode::ERROR_NEED_USER_ID,  '请先登录Hitv客户端') &&
            $this->paramsCheck($result, $set_user_token, StatusCode::ERROR_NEED_USER_TOKEN,  '请先登录Hitv客户端') &&
            $this->paramsCheck($result, $set_session_id, StatusCode::ERROR_NEED_HITV_SESSION_ID,  '' , true)
        ){
            //TODO:改掉
            $set_user_id = 1;
            $user = $this->getUserFullInfo($result, $set_user_id, $set_user_token, true);
            if($user){
                $session_info = M(HitvDBString::SESSION)
                                ->where("id={$set_session_id}")
                                ->find();
                //通过session_id找到对应机顶盒和会话信息
                if($session_info){
                    $status = $session_info['status'];
                    if($status == StatusCode::STATUS_HITV_SESSION_WAITING_TARGET){
                        //等待用户扫码
                        $result->result->tv_box_id = $session_info[ParamsString::TV_BOX_ID];
                        if($session_info['action_type'] == 0){
                            //文本输入
                            $result->result->hitv_session_action_type = HitvActionsString::T2H_TEXT_INPUT;
                        }
                        $result->result->session_status = StatusCode::STATUS_HITV_SESSION_WAITING_INPUT;
                        //将用户信息写进session
                        $session_info[ParamsString::USER_ID] = $set_user_id;//[ParamsString::USER_ID];
                        $session_info['status'] = StatusCode::STATUS_HITV_SESSION_WAITING_INPUT;
                        $session_info_post_result = M(HitvDBString::SESSION)->save($session_info);
                        if(!$session_info_post_result){
                            //写入失败
                            $this->setStatus($result, StatusCode::ERROR_UNKNOWN, '', true);
                        }
                    }else if($status == StatusCode::STATUS_HITV_SESSION_WAITING_INPUT){
                        //等待用户输入
                        $this->setStatus($result, StatusCode::ERROR_TV_BOX_BUSY, '机顶盒已经和其他客户端建立连接,下次再来吧');
                    }else if($status == StatusCode::STATUS_HITV_SESSION_SUCCESS){
                        //用户输入完成
                        $this->setStatus($result, StatusCode::ERROR_TV_BOX_BUSY, '此次操作已完成,下次再来吧');
                    }else if($status == StatusCode::STATUS_HITV_SESSION_CANCEL){
                        //用户输入取消
                        $this->setStatus($result, StatusCode::ERROR_TV_BOX_BUSY, '此次操作已取消,下次再来吧');
                    }else if($status == StatusCode::STATUS_HITV_SESSION_EXPIRED){
                        //过期
                        $this->setStatus($result, StatusCode::ERROR_TV_BOX_BUSY, '此次操作已过期,下次再来吧');
                    }
                }else{
                    $result->result->session_status = StatusCode::STATUS_HITV_SESSION_NOT_FOUND;
                }

            }

        }
    }

    /**
     * TV通过动作session_id获取动作session信息
     * @param $result
     * @param $set_tv_box_id
     * @param $set_session_id
     */
    function getHitvSessionByTv($result, $set_tv_box_id, $set_session_id){
        if($this->paramsCheck($result, $set_tv_box_id, StatusCode::ERROR_NEED_TV_BOX_ID,  '', true) &&
            $this->paramsCheck($result, $set_session_id, StatusCode::ERROR_NEED_HITV_SESSION_ID,  '' , true)
        ){
            $session_info = M(HitvDBString::SESSION)->where("id={$set_session_id}")->find();
            if($session_info){
                //有此session信息
                $session_status = $session_info['status'];
                if($session_status == StatusCode::STATUS_HITV_SESSION_WAITING_TARGET){
                    //用户还没扫码,什么都没有
                }else if($session_status == StatusCode::STATUS_HITV_SESSION_WAITING_INPUT){
                    //用户已经正在输入,返回用户信息给机顶盒
                    $query_result = M(HitvDBString::SESSION)
                        ->table(HitvDBString::LX_SESSION.' a')
                        ->join(HitvDBString::LX_USER.' b')
                        ->field('b.'.ParamsString::USER_NICKNAME.', b.'.ParamsString::USER_PHONE)
                        ->where("a.id={$set_session_id} AND a.".ParamsString::USER_ID."=b.id")
                        ->find();
                    $result->result->user_name = $query_result[ParamsString::USER_NICKNAME];
                    $phone = $query_result[ParamsString::USER_PHONE];
                    for($i = 3; $i < 7; $i++){
                        $phone[$i] = '*';
                    }
                    $result->result->user_phone = $phone;// $query_result[ParamsString::USER_PHONE];
                }else if($session_status == StatusCode::STATUS_HITV_SESSION_SUCCESS){
                    //输入完成
                    $result->result->action_result = $session_info['action_param'];
                }else if($session_status == StatusCode::STATUS_HITV_SESSION_CANCEL){
                    //输入取消
                }else if($session_status == StatusCode::STATUS_HITV_SESSION_EXPIRED){
                    //输入取消
                }
                $result->result->session_status = $session_status;
            }

        }
    }

    /**
     * Hitv提交动作
     * @param $result
     * @param $set_user_id
     * @param $set_user_token
     * @param $set_hitv_session_id
     * @param $set_hitv_action_param
     */
    function postHitvSessionByHitv($result , $set_user_id, $set_user_token, $set_hitv_session_id, $set_hitv_action_param){
        if($this->paramsCheck($result, $set_user_id, StatusCode::ERROR_NEED_USER_ID,  '请先登录Hitv客户端') &&
            $this->paramsCheck($result, $set_user_token, StatusCode::ERROR_NEED_USER_TOKEN,  '请先登录Hitv客户端') &&
            $this->paramsCheck($result, $set_hitv_session_id, StatusCode::ERROR_NEED_HITV_SESSION_ID,  '' , true) &&
            $this->paramsCheck($result, $set_hitv_action_param, StatusCode::ERROR_NEED_HITV_ACTION_PARAM,  '' , true)
        ){
            //检测用户
            $user = $this->getUserFullInfo($result, $set_user_id, $set_user_token, true);
            if($user){
                //检测session
                $session_info = M(HitvDBString::SESSION)
                    ->where("id={$set_hitv_session_id}")
                    ->find();
                //通过session_id找到对应机顶盒和会话信息
                if($session_info){
                    $status = $session_info['status'];
                    if($status == StatusCode::STATUS_HITV_SESSION_WAITING_TARGET){
                        //还没开始
                        $this->setStatus($result, StatusCode::ERROR_TV_BOX_BUSY, '搜寻机顶盒失败,请重新扫码');
                    }else if($status == StatusCode::STATUS_HITV_SESSION_WAITING_INPUT){
                        //提交action param
                        $session_info[ParamsString::HITV_SESSION_ACTION_PARAM] = $set_hitv_action_param;
                        $session_info['status'] = StatusCode::STATUS_HITV_SESSION_SUCCESS;
                        $session_info['action_param'] = $set_hitv_action_param;
                        $session_info_post_result = M(HitvDBString::SESSION)->save($session_info);
                        if(!$session_info_post_result){
                            //写入失败
                            $this->setStatus($result, StatusCode::ERROR_UNKNOWN, '操作失败了,请重试', true);
                        }
                    }else if($status == StatusCode::STATUS_HITV_SESSION_SUCCESS){
                        //用户输入完成
                        $this->setStatus($result, StatusCode::ERROR_TV_BOX_BUSY, '此次操作已完成,下次再来吧');
                    }else if($status == StatusCode::STATUS_HITV_SESSION_CANCEL){
                        //用户输入取消
                        $this->setStatus($result, StatusCode::ERROR_TV_BOX_BUSY, '此次操作已取消,下次再来吧');
                    }else if($status == StatusCode::STATUS_HITV_SESSION_EXPIRED){
                        //过期
                        $this->setStatus($result, StatusCode::ERROR_TV_BOX_BUSY, '此次操作已过期,下次再来吧');
                    }
                }else{
                    $result->result->session_status = StatusCode::STATUS_HITV_SESSION_NOT_FOUND;
                }
            }
        }
    }

    function setUserDevice($result, $set_user_id, $set_user_token, $set_device_type, $set_device_unique_id){
        if($this->paramsCheck($result, $set_user_id, StatusCode::ERROR_NEED_USER_ID,  '请先登录Hitv客户端') &&
            $this->paramsCheck($result, $set_user_token, StatusCode::ERROR_NEED_USER_TOKEN,  '请先登录Hitv客户端') &&
            $this->paramsCheck($result, $set_device_type, StatusCode::ERROR_NEED_DEVICE_TYPE,  '', true) &&
            $this->paramsCheck($result, $set_device_unique_id, StatusCode::ERROR_NEED_DEVICE_UNIQUE_ID,  '', true)
        ){
            $registered_user_list = M(HitvDBString::DEVICE)
                                    ->where(ParamsString::DEVICE_TYPE_ID."={$set_device_type} AND "
                                        .ParamsString::DEVICE_UNIQUE_ID."={$set_device_unique_id} AND"
                                        .ParamsString::USER_ID."={$set_user_id}"
                                    )
                                    ->select();

        }
    }

    function getUserDevicesList($result, $set_user_id, $set_user_token){
        if($this->paramsCheck($result, $set_user_id, StatusCode::ERROR_NEED_USER_ID,  '请先登录Hitv客户端') &&
            $this->paramsCheck($result, $set_user_token, StatusCode::ERROR_NEED_USER_TOKEN,  '请先登录Hitv客户端')
        ){
            $device_list = M(HitvDBString::DEVICE)
                ->table(HitvDBString::LX_DEVICE.' a')
                ->join(HitvDBString::LX_DEVICE_TYPE.' b')
                ->field('
                    a.id AS device_id,
                    a.'.ParamsString::DEVICE_TYPE_ID.',
                    a.'.ParamsString::DEVICE_UNIQUE_ID.',
                    a.registered_time,
                    b.icon_url AS device_icon_url ,b.name AS device_name, b.info AS device_info')
                //{$set_user_id}
                    //TODO:debug 强制设用户id为1
                ->where("a.user_id=1 AND a.".ParamsString::DEVICE_TYPE_ID."=b.id")
                ->select();
            $result->result = $device_list;
        }
    }
/*
    function test(){

        $doc=M('document');
        $result=$doc->table('lx_document a')->field('a.id,a.title,a.cover_id,b.path')->where("a.id=5 or a.id=4")
            ->join('left join lx_picture b on b.id=a.cover_id')
            ->select();
        $pic=M('picture');
        $add_data['path']='yyyyy';
        $add_data['status']=0;
        $add_result=$pic->where("id=14")->delete();


        if($result){
            $this->ajaxReturn($add_result);
        }
    }*/
}