<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/9/13
 * Time: 17:04
 */

namespace Common\Lib\Hitv\Model\Starcor;


class HitvPlaybillModel
{
    public $day;
    public $playbill;

    const STARCOR_LOCAL_NETWORK_API_ADDRESS = "http://hitvin.starcor1.net:8098/nn_cms_app_in/nn_cms_view/gxcatv20/n3_a_a.php?nns_tag=27&nns_func=get_playbill&nns_output_type=json&nns_tag=27&nns_time_zone=8&nns_video_type=1";


    public static function getPlaybill($set_video_id ,$set_start_day, $set_end_day)
    {
        $result = json_decode(self::send_request(self::STARCOR_LOCAL_NETWORK_API_ADDRESS."&nns_video_id={$set_video_id}&nns_day_begin={$set_start_day}&nns_day_end={$set_end_day}"));
        if($result){
            $playbills = array();
            foreach ($result->day as $day_item) {
                $playbill = new HitvPlaybillModel();
                $playbill->day = $day_item->day;
                $media = array();
                foreach ($day_item->item as $media_info_item) {
                    $item = new HitvMediaInfoModel();
                    $item->media_title = $media_info_item->text;
                    $item->can_play = $media_info_item->can_play;
                    $item->media_begin_time = $media_info_item->begin;
                    $item->media_time_length = $media_info_item->time_len;
                    array_push($media, $item);
                }
                $playbill->playbill = $media;
                array_push($playbills, $playbill);
            }
            return $playbills;
        }
        return null;
    }


    static function send_request($url, $data, $refererUrl = '', $method = 'GET', $contentType = 'application/json', $timeout = 30, $proxy = false) {
        $ch = null;
        if('POST' === strtoupper($method)) {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_HEADER,0 );
            curl_setopt($ch, CURLOPT_FRESH_CONNECT, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FORBID_REUSE, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            if ($refererUrl) {
                curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
            }
            if($contentType) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));
            }
            if(is_string($data)){
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
        } else if('GET' === strtoupper($method)) {
            if(is_string($data)) {
                $real_url = $url. (strpos($url, '?') === false ? '?' : ''). $data;
            } else {
                $real_url = $url. (strpos($url, '?') === false ? '?' : ''). http_build_query($data);
            }

            $ch = curl_init($real_url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:'.$contentType));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            if ($refererUrl) {
                curl_setopt($ch, CURLOPT_REFERER, $refererUrl);
            }
        } else {
            $args = func_get_args();
            return false;
        }

        if($proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
        }
        $ret = curl_exec($ch);
        $info = curl_getinfo($ch);
        $contents = array(
            'httpInfo' => array(
                'send' => $data,
                'url' => $url,
                'ret' => $ret,
                'http' => $info,
            )
        );

        curl_close($ch);
        return $ret;
    }
}