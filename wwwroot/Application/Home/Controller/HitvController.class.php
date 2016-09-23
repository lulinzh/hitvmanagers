<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/7
 * Time: 10:16
 */

namespace Home\Controller;
use Common\Lib\Hitv\Model\HitvOrderModel;
use Common\Lib\Hitv\Model\HitvSessionModel;
use Common\Lib\Hitv\Model\HitvUserModel;
use Common\Lib\Hitv\Model\HitvWebAppModel;
use Common\Lib\Hitv\Model\Starcor\HitvChannelModel;
use Common\Lib\Hitv\Model\Starcor\HitvMediaModel;
use Common\Lib\Hitv\Model\Starcor\HitvPlaybillModel;
use Common\Lib\Hitv\Model\Starcor\HitvSearchItemModel;
use Home\Controller\Base\HomeController;
use Common\Lib\Hitv\Query\QueryUtils;
use Common\Lib\Hitv\Model\AppInfoModel;
use Common\Lib\Hitv\Model\StatusModel;
use Home\Model\ChannelModel;
use Common\Lib\Hitv\Model\HitvOperationItemModel;
use Org\Util\Date;

class HitvController extends HomeController
{

    /**
     * 获取app信息
     */
    function get_splash_info(){
        $app_type = I('device_type', '');
        $app_ver = I('app_ver', '');
        $result = array(
            'status' => new StatusModel(),
            'result' => new AppInfoModel($app_type, $app_ver)
        );
        $this->ajaxReturn($result, $this->_type);
    }

    /**
     * 用户反馈接口
     */
    function post_feedback(){
        $hitv_id = I('hitv_id', '');
        $access_token = I('access_token', '');
        $feedback = I('feedback_content', '');
        //todo 校验token
        //todo 检测每日上限
        $status = new StatusModel();
        $result = array(
            'status' => $status
        );
        $this->ajaxReturn($result, $this->_type);
    }

    function get_index_list(){
        $hitv_id = I('hitv_id', '');
        $access_token = I('access_token', '');
        $device_type = I('device_type', '');
        $app_version = I('app_version', '');
        $result = array(
            'status' => new StatusModel(),
            'category_list' => array(
                 array(
                    "title" => '热门推荐',
                    "info" => '齐舞大赛海选100000强投票' ,
                    'action'=>'web' ,
                    'data' => 'http://baidu.com',
                    'item_list' => array(
                        new HitvOperationItemModel(),new HitvOperationItemModel(),new HitvOperationItemModel(),new HitvOperationItemModel()
                    )
                ),
                array(
                    "title" => '编辑精选',
                    "info" => '上班为什么' ,
                    'action'=>'web' ,
                    'data' => 'http://baidu.com',
                    'item_list' => array(
                        new HitvOperationItemModel(),new HitvOperationItemModel(),new HitvOperationItemModel(),new HitvOperationItemModel()
                    )
                )
            ),//
            'banner_list' => array(new HitvOperationItemModel(),new HitvOperationItemModel()),
        );
        $this->ajaxReturn($result, $this->_type);
    }

    function get_channel_list(){
        $hitv_id = I('hitv_id', '');
        $access_token = I('access_token', '');
        $device_type = I('device_type', '');
        $app_version = I('app_version', '');
        $channel_type = I('channel_type', '');
        if($channel_type == 'tv'){
            $channel_list = HitvChannelModel::getAllChannel(HitvChannelModel::CHANNEL_TYPE_TV);
            $item = array(
                'status' => new StatusModel(),
                'channel_list' => $channel_list,
                'channel_hot' => HitvChannelModel::getHotTVChannel(),
                'banner_list' => array(new HitvOperationItemModel(),new HitvOperationItemModel()),
                'ad_list'=> array(new HitvOperationItemModel(),new HitvOperationItemModel())
            );
            $this->ajaxReturn($item, $this->_type);
        }else if($channel_type == 'radio'){
            $channel_list = HitvChannelModel::getAllChannel(HitvChannelModel::CHANNEL_TYPE_RADIO);
            $item = array(
                'status' => new StatusModel(),
                'channel_list' => $channel_list
            );
            $this->ajaxReturn($item, $this->_type);
        }
        $result = array(
            'status' => new StatusModel()
        );
        $this->ajaxReturn($result, $this->_type);
    }

    function get_discover_list(){
        $hitv_id = I('hitv_id', '');
        $access_token = I('access_token', '');
        $device_type = I('device_type', '');
        $app_version = I('app_version', '');
        $result = array(
            'status' => new StatusModel(),
            'category_list' => array(
                array(
                    "title" => '热点活动',
                    "info" => '' ,
                    'action'=>'' ,
                    'data' => '',
                    'item_list' => array(
                        new HitvOperationItemModel(),new HitvOperationItemModel(),new HitvOperationItemModel(),new HitvOperationItemModel()
                    )
                ),
                array(
                    "title" => '热点活动2',
                    "info" => '' ,
                    'action'=>'' ,
                    'data' => '',
                    'item_list' => array(
                        new HitvOperationItemModel(),new HitvOperationItemModel(),new HitvOperationItemModel(),new HitvOperationItemModel()
                    )
                )
            ),//
            'banner_list' => array(new HitvOperationItemModel(),new HitvOperationItemModel()),
            'webapp_list' => array(new HitvWebAppModel(),new HitvWebAppModel(),new HitvWebAppModel(),new HitvWebAppModel(),new HitvWebAppModel(),new HitvWebAppModel(),new HitvWebAppModel(),new HitvWebAppModel()),
        );
        $this->ajaxReturn($result, $this->_type);
    }

    /*
     * http://hitvout.starcor1.net:8098/nn_cms_app_out/nn_cms_view/gxcatv20/n3_a_a.php?nns_tag=27&nns_day_begin=20160907&nns_day_end=20160913&nns_func=get_playbill&nns_output_type=json&nns_tag=27&nns_time_zone=8&nns_token=D0AEC57D905E4A40E24029F7EC1009E9DDD10C6CB23A0E37291956609F50D8A7&nns_user_id=13977134194&nns_video_id=e117c77c51982fc6de5fbfefdbfb9592&nns_video_type=1
     * */

    function get_channel_playbill(){
        $hitv_id = I('hitv_id', '');
        $access_token = I('access_token', '');
        $device_type = I('device_type', '');
        $app_version = I('app_version', '');
        $video_id = I('video_id', '');
        $time_now = date_create();
        $end_day = date_format($time_now,"Ymd");

        $time_prev = date_sub($time_now,date_interval_create_from_date_string("7 days"));
        $start_day = date_format($time_prev,"Ymd");

        $result = array(
            'status' => new StatusModel(),
            'playbill_list' => HitvPlaybillModel::getPlaybill($video_id, $start_day, $end_day)
        );
        $this->ajaxReturn($result, $this->_type);
    }

    //http://hitvout.starcor1.net:8098/nn_cms_app_out/nn_cms_view/gxcatv20/n3_a_a.php?nns_tag=27&nns_begin=015900&nns_category_id=1000&nns_day=20160913&nns_func=apply_play_video&nns_media_assets_id=mobile_live_v2&nns_output_type=json&nns_quality=std&nns_tag=27&nns_time_len=3720&nns_token=D0AEC57D905E4A40E24029F7EC1009E9DDD10C6CB23A0E37291956609F50D8A7&nns_user_agent=nn_phone%2Fiphone%2F1.0.0&nns_user_id=13977134194&nns_version=3.2.0.PHONE.GXCATV.SC02.RELEASE&nns_video_id=e117c77c51982fc6de5fbfefdbfb9592&nns_video_index=0&nns_video_type=1

    function get_media_info(){
        $hitv_id = I('hitv_id', '');
        $access_token = I('access_token', '');
        $device_type = I('device_type', '');
        $app_version = I('app_version', '');
        $channel_type = I('channel_type', '');
        $media_video_id = I('video_id', '');
        $media = HitvMediaModel::getMediaInfoByVideoId($media_video_id, $channel_type);
        $item = array(
            'status' => new StatusModel(),
            'media' => $media,
        );
        $this->ajaxReturn($item, $this->_type);
    }

    function search_integration(){
        $hitv_id = I('hitv_id', '');
        $access_token = I('access_token', '');
        $device_type = I('device_type', '');
        $app_version = I('app_version', '');
        $keyword = I('search_keyword', '');
        $time_now = date_create();
        $end_day = date_format($time_now,"Ymd");

        $time_prev = date_sub($time_now,date_interval_create_from_date_string("7 days"));
        $start_day = date_format($time_prev,"Ymd");
        $result = HitvSearchItemModel::getVideoByKeyword($keyword, $start_day, $end_day);
        $item = array(
            'status' => new StatusModel(),
            'result' => $result
        );
        $this->ajaxReturn($item, $this->_type);
    }


    /**
     * 旧接口============================================
     */

    function get_user_info(){
        $status = new StatusModel();
        $user_token = I('user_token', '');
        $user = HitvUserModel::createByToken($user_token, $status);
        $result = array(
            'status' => $status,
            'result' => $user,
        );

        $this->ajaxReturn($result);
    }

    function get_session_info(){
        $status = new StatusModel();
        $session_token = I('session_token', '');
        $session = HitvSessionModel::createByToken($session_token, $status);
        $result = array(
            'status' => $status,
            'result' => $session
        );
        $this->ajaxReturn($result);
    }

    function set_session_user(){
        $status = new StatusModel();
        $session_token = I('session_token', '');
        $user_token = I('user_token', '');
        $session = HitvSessionModel::createByToken($session_token, $status);
        $user = HitvUserModel::createByToken($user_token, $status);
        if($session){
            if($user){
                $session->user_id = $user->user_id;
                $session->session_status = HitvSessionModel::SESSION_STATUS_WAITING_COMPLETE;
                $session->save($status);
            }
        }
        $result = array(
            'status' => $status,
            'result' => $session,
        );
        $this->ajaxReturn($result);
    }

    function get_session_list(){
        //todo 获取100条消息
    }

    function set_session_login(){
        $status = new StatusModel();
        $session_token = I('session_token', '');
        $user_token = I('user_token', '');
        $session = HitvSessionModel::createByToken($session_token, $status);
        $user = HitvUserModel::createByToken($user_token, $status);
        if($session){
//            if($session->session_status == HitvSessionModel::SESSION_STATUS_WAITING_COMPLETE){
                if($user){
                    $session->user_id = $user->user_id;
                    $session->session_out_param = $user_token;
                    $session->session_status = HitvSessionModel::SESSION_STATUS_SUCCEED;
                    $session->save($status);
                    //绑定设备
                    QueryUtils::setDeviceToUser($session->device_id, $user->user_id);
                }
//            }else{
//                $status->sessionIsEnd();
//            }
        }
        $result = array(
            'status' => $status,
            'result' => $session,
        );
        $this->ajaxReturn($result);
    }

    function set_session_input(){
        $status = new StatusModel();
        $session_token = I('session_token', '');
        $session_out_param = I('session_out_param', '');
        $user_token = I('user_token', '');
        $session = HitvSessionModel::createByToken($session_token, $status);
        $user = HitvUserModel::createByToken($user_token, $status);
        if($session){
            if($session->session_status == HitvSessionModel::SESSION_STATUS_WAITING_COMPLETE){
                if($user){
                    $session->user_id = $user->user_id;
                    $session->session_out_param = $session_out_param;
                    $session->session_status = HitvSessionModel::SESSION_STATUS_SUCCEED;
                    $session->save($status);
                    //绑定设备
                    QueryUtils::setDeviceToUser($session->device_id, $user->user_id);
                }
            }else{
                $status->sessionIsEnd();
            }
        }
        $result = array(
            'status' => $status,
            'result' => $session,
        );
        $this->ajaxReturn($result);
    }

    function set_session_share(){
        $status = new StatusModel();
        $session_token = I('session_token', '');
        $user_token = I('user_token', '');
        $session = HitvSessionModel::createByToken($session_token, $status);
        $user = HitvUserModel::createByToken($user_token, $status);
        if($session){
            if($session->session_status == HitvSessionModel::SESSION_STATUS_WAITING_COMPLETE){
                if($user){
                    $session->user_id = $user->user_id;
                    $session->session_status = HitvSessionModel::SESSION_STATUS_SUCCEED;
                    $session->save($status);
                    //绑定设备
                    QueryUtils::setDeviceToUser($session->device_id, $user->user_id);
                }
            }else{
                $status->sessionIsEnd();
            }
        }
        $result = array(
            'status' => $status,
            'result' => $session,
        );
        $this->ajaxReturn($result);
    }

    function set_session_payment(){
        $status = new StatusModel();
        $session_token = I('session_token', '');
        $user_token = I('user_token', '');
        $session = HitvSessionModel::createByToken($session_token, $status);
        $user = HitvUserModel::createByToken($user_token, $status);
        if($session){
            if($session->session_status == HitvSessionModel::SESSION_STATUS_WAITING_COMPLETE){
                if($user){
                    $session->user_id = $user->user_id;
                    $session->session_status = HitvSessionModel::SESSION_STATUS_SUCCEED;
                    $session->save($status);
                    //绑定设备
                    QueryUtils::setDeviceToUser($session->device_id, $user->user_id);
                }
            }else{
                $status->sessionIsEnd();
            }
        }
        $result = array(
            'status' => $status,
            'result' => $session,
        );
        $this->ajaxReturn($result);
    }

    function cancel_session(){
        $status = new StatusModel();
        $session_token = I('session_token', '');
        $user_token = I('user_token', '');
        $session = HitvSessionModel::createByToken($session_token, $status);
        $user = HitvUserModel::createByToken($user_token, $status);
        if($session){
            if($user){
                $session->session_status = HitvSessionModel::SESSION_STATUS_CANCEL_BY_TARGET;
                $session->save($status);
            }
        }
        $result = array(
            'status' => $status,
            'result' => $session,
        );
        $this->ajaxReturn($result);
    }


    function get_order_info(){
        $status = new StatusModel();
        $order_id = I('order_id', '');
        $order = HitvOrderModel::createByOrderId($order_id, $status);
        $user = null;
        if($order->gen_user_id){
            $user = HitvUserModel::createByUserId($order->gen_user_id, $status);
        }
        $web_app = HitvWebAppModel::createByAppId($order->app_id, $status);
        $result = array(
            'status' => $status,
            'result' => $order,
            'user' => $user,
            'web_app' => $web_app
        );
        $this->ajaxReturn($result);
    }

    function pay_order(){
        $status = new StatusModel();
        $order_id = I('order_id', '');
        $user_token = I('user_token', '');
        $session_token = I('session_token', '');
        $user = HitvUserModel::createByToken($user_token, $status);
        $order = HitvOrderModel::createByOrderId($order_id, $status);
        if($order){
            if($order->isPay()){
                $status->orderIsPay();
            }else{
                if($user){
                    //操作订单
                    $order->pay_user_id = $user->user_id;
                    $now_time = new \DateTime();
                    $order->pay_time = $now_time->getTimestamp();
                    $order->save($status);
                    if($status->noError()){
                        //输出会话
                        if($session_token){
                            $session = HitvSessionModel::createByToken($session_token, $status);
                            if($session){
                                if($session->session_status == HitvSessionModel::SESSION_STATUS_WAITING_COMPLETE){
                                    $session->user_id = $user->user_id;
                                    $session->session_status = HitvSessionModel::SESSION_STATUS_SUCCEED;
                                    $output = array('is_pay' => $order->isPay(), 'user_name'=> $user->user_nickname, 'user_phone' => $user->user_phone);
                                    $session->session_out_param = json_encode($output);
                                    $session->save($status);
                                    //绑定设备
                                    QueryUtils::setDeviceToUser($session->device_id, $user->user_id);
                                }else{
                                    $status->sessionIsEnd();
                                }
                            }
                        }
                    }
                }
            }
        }

        $result = array(
            'status' => $status
        );
        $this->ajaxReturn($result);
    }
}

