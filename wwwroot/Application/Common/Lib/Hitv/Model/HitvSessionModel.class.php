<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/16
 * Time: 14:52
 */
namespace Common\Lib\Hitv\Model;

use Common\Lib\Hitv\Auth\HitvSessionToken;
use Common\Lib\Hitv\Query\QueryUtils;
use Common\Lib\Hitv\Query\UmengNotification;
class HitvSessionModel
{

    const SESSION_TYPE_DEVICE_LOGIN = 1;
    const SESSION_TYPE_TEXT_INPUT = 2;
    const SESSION_TYPE_PAYMENT_AGENT = 3;
    const SESSION_TYPE_SNS_SHARE = 4;
    const SESSION_TYPE_SHARE_PAYABLE_LINK = 5;

    const DEVICE_TYPE_TV_BOX = 1;

    const SESSION_STATUS_WAITING_TARGET = 0;
    const SESSION_STATUS_WAITING_COMPLETE = 1;
    const SESSION_STATUS_SUCCEED = 2;
    const SESSION_STATUS_CANCEL_BY_SOURCE = 3;
    const SESSION_STATUS_CANCEL_BY_TARGET = 4;


    public $session_id;
    public $session_type;
    public $session_in_param;
    public $session_out_param;
    public $session_status;
    public $device_id;
    public $device_type;
    public $user_id;

    /**
     * @param $set_session_type
     * @param $set_device_type
     * @param $set_device_id
     * @param $set_in_param
     * @param $set_user_id
     * @param $set_status StatusModel
     * @return HitvSessionModel|null
     */
    public static function startSessionByDevice(
        $set_session_type,
        $set_device_type,
        $set_device_id,
        $set_in_param,
        $set_user_id,
        $set_status
    )
    {
        if (!$set_session_type) {
            $set_status->needSessionType();
        } else if (!$set_device_type) {
            $set_status->needDeviceType();
        } else if (!$set_device_id) {
            $set_status->needDeviceId();
        }
        //TODO:设备类型判定
        $result = new HitvSessionModel();
        $session_id = QueryUtils::addNewSession(
            $set_session_type,
            $set_device_type,
            $set_device_id,
            $set_in_param,
            $set_user_id
        );
        //添加会话成功
        if ($session_id) {
            $result->session_type = $set_session_type;
            $result->session_status = self::SESSION_STATUS_WAITING_TARGET;
            $result->session_id = $session_id;
            $result->device_type = $set_device_type;
            $result->device_id = $set_device_id;
            if ($set_in_param) {
                $result->session_in_param = $set_in_param;
            }
            return $result;
        } else {
            $set_status->operationFailed();
        }
        return null;
    }

    /**
     * @param $set_token string
     * @param $set_status StatusModel
     * @return HitvSessionModel|null
     */
    public static function createByToken($set_token, $set_status)
    {
        $decodeData = HitvSessionToken::createByToken($set_token, $set_status);
        if ($decodeData) {
//            $nowTime = new \DateTime();
            //超时判断
//            if($nowTime->getTimestamp() > $decodeData->exp_time){
            //超时
//                $set_status->sessionTokenExpired();
//            }else{
            //未超时,查询会话信息
            $session_data = QueryUtils::getSessionById($decodeData->session_id);
            if ($session_data) {
                //找到会话
                $result = new HitvSessionModel();
                $result->session_id = $session_data['id'];
                $result->session_type = $session_data['session_type'];
                $result->session_status = $session_data['session_status'];
                $result->session_in_param = $session_data['session_in_param'];
                $result->session_out_param = $session_data['session_out_param'];
                $result->device_id = $session_data['device_id'];
                $result->device_type = $session_data['device_type'];
                $result->user_id = $session_data['user_id'];
                return $result;
            } else {
                //无此会话
                $set_status->sessionNotFound();
            }
//            }
        }
        return null;
    }

    /**
     * @param StatusModel $set_status
     */
    public function save($set_status)
    {
        $result = QueryUtils::setSessionInfo(
            $this->session_id,
            $this->session_type,
            $this->session_status,
            $this->device_type,
            $this->device_id,
            $this->session_in_param,
            $this->user_id,
            $this->session_out_param
        );
        if ($result == -1) {
            $set_status->operationFailed();
        }
    }

    /**
     * 获取HiTV交互session，机顶盒已绑定HiTV返回session，未绑定返回操作二维码
     * @param $set_session_type int
     * @param $set_device_id string
     * @param $set_in_param
     * @param $set_user_id string
     * @param $set_notify_title string
     * @param $status StatusModel
     * @return HitvSessionToken
     */
    public static function startSession(
        $set_session_type,
        $set_device_id,
        $set_in_param,
        $set_user_id,
        $set_notify_title,
        $status
    ){
        $session = HitvSessionModel::startSessionByDevice(
            $set_session_type,
            HitvSessionModel::DEVICE_TYPE_TV_BOX,
            $set_device_id,
            json_encode($set_in_param),
            $set_user_id,
            $status
        );
        $result = new HitvSessionToken();
        if($session){
            $result->session_id = $session->session_id;
            $result->resetExpiredTime();
        }
        $token = $result->encode();
        if ($set_user_id) {
            //查找用户
            $user = HitvUserModel::createByUserId($set_user_id, $status);
            if ($user) {
                //设置推送
                $push_result = UmengNotification::pushSessionNotification(
                    $set_session_type,
                    $token,
                    $set_notify_title,
                    $set_user_id
                );
                $result->notify_result = $push_result;
            }
        }

        return $result;
    }

//
//    public function startSession($set_session_type, $set_device_id, $set_in_param, $set_user_id, $status)
//    {
//        $session = HitvSessionModel::startSessionByDevice(
//            $set_session_type,
//            HitvSessionModel::DEVICE_TYPE_TV_BOX,
//            $set_device_id,
//            $set_in_param,
//            $set_user_id,
//            ($set_user_id) ? true : false,
//            $status
//        );
//        $result = new HitvSessionToken();
//        if ($session) {
//            $result->session_id = $session->session_id;
//            $result->resetExpiredTime();
//        }
//        $token = $result->encode();
//        if(!$set_user_id){
//            $qrcode = C('QRCODE_URL') . urlencode(C('HITV_QRCODE') . $token);
//        }
//        $result->qrcode = $qrcode;
//        $result->token = $token;
//        return $result;
//    }
}