<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use Common\Lib\Hitv\Auth\HitvUserToken;
use Common\Lib\Hitv\Model\HitvUserModel;
use Common\Lib\Hitv\Model\HitvWebAppModel;
use Home\Controller\Base\HomeController;
use Common\Lib\Hitv\HitvUser;
use Common\Lib\Hitv\Model\HitvSessionModel;
use Common\Lib\Hitv\Model\StatusModel;

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class UserController extends HomeController
{
    /**
     * 重置密码
     */
    public function password_reset(){
        $account = I('param.account');
        $password = I('param.new_password');
        $verify_id = I('param.verify_id');
        $verify_code = I('param.verify_code');
        $app_key = I('param.app_key');
        $app_secret = I('param.app_secret');
        $status = new StatusModel();
        //获取app信息
        $web_app_item = HitvWebAppModel::createByAppId($app_key, $status);
        if($status->noError()){
            //app_secret匹配
            if($web_app_item->app_secret == $app_secret){
                $HitvUser = new HitvUser();
                $HitvUser->doPasswordReset(
                    $account,
                    $password,
                    $verify_id,
                    $verify_code,
                    $status
                );
            }else{
                $status->webAppSecretNotEqual();
            }
        }
        $result = array('status'=> $status);
        $this->ajaxReturn($result, $this->_type);
    }

    /**
     * 获取验证码
     */
    public function get_verify_code_v2(){
        $account = I('param.phone_number');
        $app_key = I('param.app_key');
        $app_secret = I('param.app_secret');
        $type = I('param.verify_type');
        $status = new StatusModel();
        $verify_object = null;
        //获取app信息
        $web_app_item = HitvWebAppModel::createByAppId($app_key, $status);
        if($status->noError()){
            //app_secret匹配
            if($web_app_item->app_secret == $app_secret){
                $HitvUser = new HitvUser();
                $verify_object = $HitvUser->getVerifyCode_v2($account, $type, $status);
            }else{
                $status->webAppSecretNotEqual();
            }
        }

        $result = array('status'=> $status, 'verify' => $verify_object);
        $this->ajaxReturn($result, $this->_type);
    }


    /**
     * 登录
     */
    public function login_v2()
    {
        $account = I('param.account');
        $password = I('param.password');
        $app_key = I('param.app_key');
        $app_secret = I('param.app_secret');
        $status = new StatusModel();
        //获取app信息
        $web_app_item = HitvWebAppModel::createByAppId($app_key, $status);
        if($status->noError()){
            //app_secret匹配
            if($web_app_item->app_secret == $app_secret){
                $HitvUser = new HitvUser();
                $token = $HitvUser->doLogin_v2($account, $password,$web_app_item, $status);
                if($status->noError()){
                    //查询用户
                    $user_id = $web_app_item->createUserIdByOpenId($token->open_id, $status);
                    if($status->noError()){
                        $user = HitvUserModel::getByUserId($user_id, $status);
                        if($status->noError()){
                            $result = array('status'=> $status, 'token' => $token, "user" => $user);
                            $this->ajaxReturn($result, $this->_type);
                            return;
                        }

                    }
                }
            }else{
                $status->webAppSecretNotEqual();
            }
        }
        $result = array('status'=> $status);
        $this->ajaxReturn($result, $this->_type);
    }

    /**
     * 获取用户信息
     */
    function get_user_info_v2(){
        $open_id = I('param.open_id');
        $app_key = I('param.app_key');
        $status = new StatusModel();
        $web_app_item = HitvWebAppModel::createByAppId($app_key, $status);
        if($status->noError()){
            if($web_app_item){
                $user_id = $web_app_item->createUserIdByOpenId($open_id, $status);
                if($status->noError()){
                    $user = HitvUserModel::getByUserId($user_id, $status);
                    $result = array('status'=> $status, 'user' => $user);
                    $this->ajaxReturn($result, $this->_type);
                    return;
                }
            }
        }
        $result = array('status'=> $status);
        $this->ajaxReturn($result, $this->_type);
    }

    function register_v2(){
        $account = I('param.account');
        $password = I('param.password');
        $nickname = I('param.nickname');
        $verify_id = I('param.verify_id');
        $verify_code = I('param.verify_code');
        $app_key = I('param.app_key');
        $app_secret = I('param.app_secret');
        $status = new StatusModel();
        $web_app_item = HitvWebAppModel::createByAppId($app_key, $status);
        if($status->noError()){
            if($web_app_item){
                //app_secret匹配
                if($web_app_item->app_secret == $app_secret){
                    $user = new HitvUser();
                    $user_token = $user->doRegister_v2(
                        $account,
                        $password,
                        $nickname,
                        $verify_id,
                        $verify_code,
                        $web_app_item,
                        $status
                        );
                    if($status->noError()){
                        $user_id = $web_app_item->createUserIdByOpenId($user_token->open_id, $status);
                        $user = HitvUserModel::getByUserId($user_id, $status);
                        if($status->noError()){
                            $result = array('status'=> $status, 'token' => $user_token, "user" => $user);
                            $this->ajaxReturn($result, $this->_type);
                        }
                    }
                }else{
                    $status->webAppSecretNotEqual();
                }
            }
        }
        $result = array('status'=> $status);
        $this->ajaxReturn($result, $this->_type);
    }

    /**
     * @deprecated
     * 获取验证码 废弃接口
     */
    function get_verify_code()
    {
        $account = I('param.account');
        $HitvUser = new HitvUser();
        $result = $HitvUser->getVerifyCode($account);
        $this->ajaxReturn($result, $this->_type);
    }



    /**
     * @deprecated
     * 登录 废弃接口
     */
    function login()
    {
        $account = I('param.account');
        $password = I('param.password');
        $phone_imei = I('param.phone_imei');
        $phone_mac = I('param.phone_mac');
        $tv_code = I('param.tv_code');
        $HitvUser = new HitvUser();
        $result = $HitvUser->doLogin(array(
            'account' => $account,
            'password' => $password,
            'phone_imei' => $phone_imei,
            'phone_mac' => $phone_mac,
            'tv_code' => $tv_code));
        $this->ajaxReturn($result, $this->_type);
    }


    /**
     * @deprecated
     * 注册
     */
    function register()
    {
        $account = I('param.account');
        $password = I('param.password');
        $nickname = I('param.nickname');
        $vcode = I('param.vcode');
        $codeid = I('param.codeid');
        $phone_imei = I('param.phone_imei');
        $phone_mac = I('param.phone_mac');
        $tv_code = I('param.tv_code');
        $HitvUser = new HitvUser();
        $result = $HitvUser->doRegister(array(
            'account' => $account,
            'password' => $password,
            'nickname' => $nickname,
            'vcode' => $vcode,
            'codeid' => $codeid,
            'phone_imei' => $phone_imei,
            'phone_mac' => $phone_mac,
            'tv_code' => $tv_code));
        $this->ajaxReturn($result, $this->_type);
    }

    /**
     * 获取机顶盒绑定用户
     */
    function get_tv_user()
    {
        $tv_code = I('param.tv_code');
        $HitvUser = new HitvUser();
        $result = $HitvUser->getUserByTvcode($tv_code);

        $this->ajaxReturn($result, $this->_type);
    }

    /**
     * 开始HiTV登录模式，推送/扫码
     */
    function start_login()
    {
        $tv_code = I('param.tv_code');
        $HitvUser = new HitvUser();
        $result = $HitvUser->getUserByTvcode($tv_code);

        if ($result['status'] == 1) {
            $status = new StatusModel();
            $session_result = HitvSessionModel::startSession(HitvSessionModel::SESSION_TYPE_DEVICE_LOGIN, $tv_code, '', $result['data']['username'], $status);
            $result['data']['qrcode'] = $session_result->qrCode();
            $result['data']['token'] = $session_result->encode();
        }

        $this->ajaxReturn($result, $this->_type);
    }

    /**
     * 用户登出，取消绑定
     */
    function loginout()
    {
        $account = I('param.account');
        $tv_code = I('param.tv_code');
        $HitvUser = new HitvUser();
        $result = $HitvUser->loginOut(array('account' => $account, 'tv_code' => $tv_code));
        $this->ajaxReturn($result, $this->_type);
    }

}
