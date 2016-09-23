<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/9/17
 * Time: 21:59
 */

namespace Common\Lib\Hitv\Model\Starcor;


class HitvSearchItemModel{
    public $title;
    public $info;
    public $action;
    public $data;


    const STARCOR_LOCAL_NETWORK_API_ADDRESS =
        'http://hitvin.starcor1.net:8098/nn_cms_app_in/nn_cms_view/gxcatv20/n3_a_a.php?nns_tag=27&nns_category_id=1000&nns_func=search_playbill&nns_media_assets_id=mobile_live_v2&nns_output_type=json&nns_page_index=0&nns_page_size=100&nns_search_type=name_likechar&nns_tag=27&nns_time_zone=8&nns_video_type=1';


    public static function getVideoByKeyword($set_keyword, $set_start_day, $set_end_day){
        $starcor_result = json_decode(self::send_request(self::STARCOR_LOCAL_NETWORK_API_ADDRESS."&nns_day_begin={$set_start_day}&nns_day_end={$set_end_day}&nns_search_key={$set_keyword}"));
        if($starcor_result){
            if($starcor_result->count_num){
                $starcor_search_result = $starcor_result->item;
                $result_list = array();
                foreach ($starcor_search_result as $search_item) {
                    $result_item = new HitvSearchItemModel();
                    $result_item->title = $search_item->text;
                    $result_item->info = "";
                    $result_item->action = "video";
                    $result_item_data = new HitvSearchVideoItemModel();
                    $result_item_data->channel_name = $search_item->video_name;
                    $result_item_data->video_time_begin = $search_item->begin;
                    $result_item_data->video_id = $search_item->video_id;
                    $result_item_data->video_date = $search_item->day;
                    $result_item_data->video_time_length = $search_item->time_len;

                    $result_item->data = $result_item_data;

                    array_push($result_list, $result_item);
                }
                return $result_list;
            }
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


