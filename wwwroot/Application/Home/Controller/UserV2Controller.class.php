<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/9/2
 * Time: 20:39
 */

namespace Home\Controller;


use Common\Lib\Hitv\Auth\HitvTokenModel;
use Common\Lib\Hitv\HitvUser;
use Common\Lib\Hitv\Model\HitvAppModel;
use Common\Lib\Hitv\Model\HitvUserModel;
use Common\Lib\Hitv\Model\StatusModel;
use Home\Controller\Base\HomeController;

class UserV2Controller extends HomeController
{
    /**
     * 登录
     */
    public function login()
    {
        $account = I('param.account');
        $app_key = I('param.app_key');
        $password = I('param.password');

        $sign = I('param.sign');
        $status = new StatusModel();
        //获取app信息
        $app_item = HitvAppModel::findHitvAppByAppKey($app_key, $status);
        if($status->noError()){
            //sign匹配
            $auth_sign = md5("{$account}{$password}{$app_key}{$app_item->app_secret}");
            if($auth_sign == $sign){
                //查找用户
                $user_id = HitvUserModel::login($account, $password, $status);
                if($user_id){
                    //查找对应hitv_id
                    $hitv_user = $app_item->getHitvIdByUserId($user_id, $status);
                    //todo $hitv_user = null
                    if($hitv_user){
                        $hitv_id = $hitv_user->hitv_id;
                        //生成token
                        $token = HitvTokenModel::refreshTokenByLogin($hitv_id, $app_item->app_id, $status);
                        //用户信息
                        $user_info = HitvUserModel::getByUserId($user_id, $status);
                        if($user_info && $token){
                            $result = array('status'=> $status, 'token' => $token, "user" => $user_info);
                            $this->ajaxReturn($result, $this->_type);
                        }
                    }else{
                        $status->userNotFound();
                    }
                }else{
                    $status->userNotFound();
                }
            }else{
                $status->signVerifyFailed();
            }
        }
        $result = array('status'=> $status);
        $this->ajaxReturn($result, $this->_type);
    }
}