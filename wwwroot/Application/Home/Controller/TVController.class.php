<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/7
 * Time: 16:13
 */

namespace Home\Controller;

use Common\Lib\Hitv\Model\HitvOrderModel;
use Common\Lib\Hitv\Model\HitvUserModel;
use Common\Lib\Hitv\Model\HitvWebAppModel;
use Common\Lib\Hitv\Model\StatusModel;
use Home\Controller\Base\HomeController;
use Common\Lib\Hitv\Model\HitvSessionModel;


use Common\Lib\Hitv\HitvUser;


class TVController extends HomeController
{
    function start_login_session(){
        $device_id = I('param.tv_box_id', '');
        $HitvUser = new HitvUser();
        $result = $HitvUser->getUserByTvcode($device_id);
        $status = new StatusModel();
        $session_result = HitvSessionModel::startSession(
            HitvSessionModel::SESSION_TYPE_DEVICE_LOGIN,
            $device_id,
            '',
            $result['data']['username'],
            "机顶盒{$device_id}" ,
            $status);
        $result['data']['qrcode'] = $session_result->qrCode();
        $result['data']['token'] = $session_result->encode();
        $this->ajaxReturn(array('status'=>$status, 'data'=>$result['data']), $this->_type);
    }

//    function start_payment_agent_session(){
//        $device_id = I('param.tv_box_id', '');
//        $order_id = I('param.order_id', '');
//        $HitvUser = new HitvUser();
//        $result = $HitvUser->getUserByTvcode($device_id);
//        $status = new StatusModel();
//        $in_param = array("order_id"=>$order_id);
//        $session_result = HitvSessionModel::startSession(HitvSessionModel::SESSION_TYPE_PAYMENT_AGENT, $device_id, $in_param, $result['data']['username'], $status);
//        $result['data']['qrcode'] = $session_result->qrCode();
//        $result['data']['token'] = $session_result->encode();
//        $this->ajaxReturn(array('status'=>$status, 'data'=>$result['data']), $this->_type);
//    }

    function start_share_session(){
        $device_id = I('param.tv_box_id', '');
        $share_title = I('param.share_title', '');
        $share_info = I('param.share_info', '');
        $share_image_url = I('param.share_image_url', '');
        $share_link_url = I('param.share_link_url', '');

        $HitvUser = new HitvUser();
        $result = $HitvUser->getUserByTvcode($device_id);
        $status = new StatusModel();
        $in_param = array(
            "share_title"=>$share_title,
            "share_info"=>$share_info,
            "share_image_url"=>$share_image_url,
            "share_link_url"=>$share_link_url
        );
        $session_result = HitvSessionModel::startSession(
            HitvSessionModel::SESSION_TYPE_SNS_SHARE,
            $device_id,
            $in_param,
            $result['data']['username'],
            "机顶盒{$device_id}" ,
            $status);
        $result['data']['qrcode'] = $session_result->qrCode();
        $result['data']['token'] = $session_result->encode();
        $result['data']['notifi'] = $session_result->notify_result;
        $this->ajaxReturn(array('status'=>$status, 'data'=>$result['data']), $this->_type);
    }


    function start_text_input_session()
    {
        $device_id = I('param.tv_box_id', '');
        $HitvUser = new HitvUser();
        $result = $HitvUser->getUserByTvcode($device_id);
        $status = new StatusModel();
        $session_result = HitvSessionModel::startSession(
            HitvSessionModel::SESSION_TYPE_TEXT_INPUT,
            $device_id,
            '',
            $result['data']['username'],
            "机顶盒{$device_id}" ,
            $status);
        $result['data']['qrcode'] = $session_result->qrCode();
        $result['data']['token'] = $session_result->encode();
        $result['data']['notifi'] = $session_result->notify_result;
        $this->ajaxReturn(array('status'=>$status, 'data'=>$result['data']), $this->_type);
    }

    function start_order(){
        $app_id = I('app_id', '');
        $app_secret = I('app_secret', '');
        $app_order_id = I('app_order_id', '');
        $price = I('price', '');
        $title = I('title', '');
        $info = I('info', '');
        $gen_user_id = I('gen_user_id', '');
        $device_id = I('tv_box_id', '');
        $status = new StatusModel();
        //下单
        $order = HitvOrderModel::start_order($app_id,$app_secret,$app_order_id,$price,$title,$info,$gen_user_id,$status);
        $HitvUser = new HitvUser();
        $result = $HitvUser->getUserByTvcode($device_id);
        //生成会话
        if($order){
            $in_param = array('order_id'=>$order->order_id);
            $session_result = HitvSessionModel::startSession(
                HitvSessionModel::SESSION_TYPE_PAYMENT_AGENT,
                $device_id,$in_param ,
                $result['data']['username'],
                "机顶盒{$device_id}" ,
                $status);
            $result['data']['qrcode'] = $session_result->qrCode();
            $result['data']['token'] = $session_result->encode();
            $result['data']['notifi'] = $session_result->notify_result;
        }
        $this->ajaxReturn(array('status' => $status, 'order' => $order, 'data'=>$result['data']), $this->_type);
    }

    function share_payable_link(){
        $device_id = I('param.tv_box_id', '');
        $source_user_id = I('param.source_user_id', '');
        $target_user_id = I('param.target_user_id', '');
        $share_title = I('param.share_title');
        $share_link = I('param.share_link', '');

        $status = new StatusModel();

        $target_user = HitvUserModel::createByUserId($target_user_id, $status);
        if($target_user){
            $title = "机顶盒{$device_id}";
            if($source_user_id){
                //提交了自己的用户id
                $source_user = HitvUserModel::createByUserId($source_user_id, $status);
                if($source_user){
                    $title ="{$source_user->user_nickname}({$source_user->user_phone})";
                }
            }
            $in_param = array(
                'share_link'=>$share_link,
                'source_user_info'=>$title,
                'share_title'=>$share_title
            );
            $session_result = HitvSessionModel::startSession(
                HitvSessionModel::SESSION_TYPE_SHARE_PAYABLE_LINK,
                $device_id,
                $in_param ,
                $target_user_id,
                $title,
                $status);
            $result['data']['qrcode'] = $session_result->qrCode();
            $result['data']['token'] = $session_result->encode();
            $result['data']['notifi'] = $session_result->notify_result;
            $this->ajaxReturn(array('status' => $status, 'data'=>$result['data']), $this->_type);
            return;
        }else{
            $status->userNotFound();
        }

        $this->ajaxReturn(array('status' => $status), $this->_type);
    }


    function get_order_info(){
//        $app_id = I('app_id', '');
//        $app_secret = I('app_secret', '');
        $order_id = I('order_id', '');
        $status = new StatusModel();
        $order = HitvOrderModel::createByOrderId($order_id, $status);
//        $app_info = HitvWebAppModel::createByAppId($app_id, $status);
//        if($app_info){
//            if($app_info->app_secret == $app_secret){
//                if($order){
//                    if($order->app_id != $app_id){
//                        $status->webAppIdNotEqual();
//                    }
//                }
//            }else{
//                $status->webAppSecretNotEqual();
//            }
//        }
        $this->ajaxReturn(array('status' => $status, 'result' => $order, 'is_pay' => $order->isPay()), $this->_type);
    }

    function get_session_info()
    {
        $token = I('token', '');
        $status = new StatusModel();
        $session = HitvSessionModel::createByToken($token, $status);
        if ($session->user_id) {
            $user = HitvUserModel::createByUserId($session->user_id, $status);
            $this->ajaxReturn(array('status' => $status, 'result' => $session, 'user' => $user), $this->_type);
            return;
        }
        $this->ajaxReturn(array('status' => $status, 'result' => $session), $this->_type);
    }

    function cancel_session()
    {
        $token = I('token', '');
        $status = new StatusModel();
        $session = HitvSessionModel::createByToken($token, $status);
        if ($session) {
            $session->session_status = HitvSessionModel::SESSION_STATUS_CANCEL_BY_SOURCE;
            $session->save($status);
        }
        $this->ajaxReturn(array('status' => $status, 'result' => $session), $this->_type);
    }




}