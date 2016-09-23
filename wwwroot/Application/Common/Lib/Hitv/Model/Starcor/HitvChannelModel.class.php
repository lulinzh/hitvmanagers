<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/9/10
 * Time: 17:44
 */
namespace Common\Lib\Hitv\Model\Starcor;

class HitvChannelModel {
    public $channel_name;
    public $channel_id;
    public $channel_thumb;
    public $channel_video_id;



    const STARCOR_LOCAL_NETWORK_API_ADDRESS = "http://hitvin.starcor1.net:8098/nn_cms_app_in/nn_cms_view/gxcatv20/n3_a_d.php?nns_category_id=1000&nns_func=get_media_assets_item_list&nns_include_category=0&nns_nxtype=d&nns_output_type=json&nns_page_index=0&nns_page_size=1000&nns_remove_duplicate_name=1&nns_tag=27";
    const CHANNEL_TYPE_TV = "mobile_live_v2";
    const CHANNEL_TYPE_RADIO = "radio_live";

    public static function getHotTVChannel(){
        $result = json_decode(self::send_request(self::STARCOR_LOCAL_NETWORK_API_ADDRESS."&nns_media_assets_id=mobile_live_v2"));
        if($result){
            if($result->count_num){
                $starcor_channels = $result->item;
                $channels = array();
                $i = 0;
                foreach ($starcor_channels as $starcor_channel) {
                    if($i == 4){
                        break;
                    }
                    $i += 1;
                    $channel = new HitvChannelModel();
                    $channel->channel_name = $starcor_channel->name;
                    if($starcor_channel->img_s){
                        $channel->channel_thumb = $starcor_channel->img_s;
                    }else{
                        $channel->channel_thumb = $starcor_channel->img_h;
                    }
                    $channel->channel_video_id = $starcor_channel->video_id;
                    $channel->channel_id = $starcor_channel->id;
                    array_push($channels, $channel);
                }
                return $channels;
            }
        }
        return null;
    }

    public static function getAllChannel($set_type = self::CHANNEL_TYPE_TV){
        $result = json_decode(self::send_request(self::STARCOR_LOCAL_NETWORK_API_ADDRESS."&nns_media_assets_id={$set_type}"));
        if($result){
            if($result->count_num){
                $starcor_channels = $result->item;
                $channels = array();
                foreach ($starcor_channels as $starcor_channel) {
                    $channel = new HitvChannelModel();
                    $channel->channel_name = $starcor_channel->name;
                    if($set_type == self::CHANNEL_TYPE_TV && $starcor_channel->img_s){
                        $channel->channel_thumb = $starcor_channel->img_s;
                    }else{
                        $channel->channel_thumb = $starcor_channel->img_h;
                    }
                    $channel->channel_video_id = $starcor_channel->video_id;
                    $channel->channel_id = $starcor_channel->id;
                    array_push($channels, $channel);
                }
                return $channels;
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