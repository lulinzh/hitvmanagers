<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/16
 * Time: 15:46
 */
namespace Common\Lib\Hitv\Query;

class UmengNotification{

    public static function pushSessionNotification(
        $set_session_type,
        $set_session_token,
        $set_source_name,
        $set_user_id
    ){
        $push_param = array("token" => $set_session_token);

        $push_string_list = array('登录','输入','代付','分享','亲情代付');
        $push = $push_string_list[$set_session_type - 1];
        //TODO:设备类型推送判定
        $title = "{$set_source_name}发起了{$push}请求";
        $param = array(
            "session_type" => $set_session_type,
            "session_param" => $push_param
        );
        $result = UmengNotification::push_notification(
            $set_user_id,
            $title,
            "点击进入界面",
            "hitv_session",
            json_encode($param)
        );
        return $result;
    }

    /**
     * @param $alias_id string user id
     * @param $title string 推送title
     * @param $info string 推送信息
     * @param $action_type string 动作类型
     * @param $action_param string 动作参数
     * @return mixed|string
     */
    public static function push_notification($alias_id,$title,$info,$action_type,$action_param){
        $time =  new \DateTime();
        $timestamp = $time->getTimestamp();
        $method = 'POST';
        $url = 'http://msg.umeng.com/api/send';
        $secret = 'iexpp1jkmjy2qdqu34uwmj8jwsssobrv';
        $app_key = '577b649167e58edb16002725';

        $params->appkey = $app_key;
        $params->timestamp = $timestamp;
        $params->type = 'customizedcast';
        $params->alias_type = 'hitv';
        $params->alias = $alias_id;
        $params->payload->aps->alert = $title;
        if($action_type){
            $params->payload->notification_type = $action_type;
            if($action_param){
                $params->payload->notification_param = $action_param;
            }
        }

        $params->production_mode = true;
        $params->description = $info;
//        $params->payload->body->ticker = 'asd';
//        $params->payload->body->title = 'asd';
//        $params->payload->body->text = 'asd';
//        $params->payload->body->after_open = 'go_app';
//        $params->payload->display_type = 'notification';

        $body = json_encode($params);

        $str = $method.$url.$body.$secret;

        $sign = md5($str);
        $result = self::httpPost($url.'?sign='.$sign, $body);


        return $result;
    }

    /**
     * 发送curl请求，post方式
     * @param $url string 请求地址
     * @param $data string 发送参数
     * @return mixed|string
     */
    private static function httpPost($url, $data)
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
}