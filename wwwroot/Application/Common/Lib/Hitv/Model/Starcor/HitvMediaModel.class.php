<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/9/10
 * Time: 19:02
 */
namespace Common\Lib\Hitv\Model\Starcor;
class HitvMediaModel {
 //http://hitvout.starcor1.net:8098/nn_cms_app_out/nn_cms_view/gxcatv20/n3_a_a.php?
    //nns_tag=27&nns_begin=&nns_category_id=1000&
    //nns_day=&nns_func=apply_play_video&nns_media_assets_id=radio_live
    //&nns_output_type=json&nns_quality=std&nns_tag=27&nns_time_len=&
    //nns_token=D0AEC57D905E4A40E24029F7EC1009E9DDD10C6CB23A0E37329B713C71686F35&
    //nns_user_agent=nn_phone%2Fiphone%2F1.0.0&
    //nns_user_id=13977134194&nns_version=3.2.0.PHONE.GXCATV.SC02.RELEASE&
    //nns_video_id=21df6c89f0e138515815f9b74c426cc8&nns_video_index=0&nns_video_type=1

    /**
    状态码,具体描述如下: 0 正常可以播放
    1 没有授权
    2 数据异常
    3 内容未到可播放时间(一般为时间限制) 4 余额不足
    5 内容已过期 (一般为电视时移回看节目已过 了保存时间限制)
    6 内容正在录制 (可以正常播放,一般为时移 回看节目还未录制完整,但可以播放,没有总 时长)
     *
     */
    const STARCOR_LOCAL_NETWORK_API_ADDRESS = "http://hitvin.starcor1.net:8098/nn_cms_app_in/nn_cms_view/gxcatv20/n3_a_a.php?nns_day=&nns_func=apply_play_video&nns_tag=27&nns_begin=&nns_category_id=1000&nns_video_index=0&nns_video_type=1&nns_tag=27&nns_time_len=&nns_output_type=json&nns_quality=std";
    public $media_id;
    public $media_url;
    public $media_state;
    public $media_quality;

    public static function getMediaInfoByVideoId($set_video_id, $set_media_type = HitvChannelModel::CHANNEL_TYPE_TV){
        $result = json_decode(self::send_request(self::STARCOR_LOCAL_NETWORK_API_ADDRESS."&nns_media_assets_id={$set_media_type}&nns_video_id={$set_video_id}&nns_user_id=13977134194&nns_token=D0AEC57D905E4A40E24029F7EC1009E9DDD10C6CB23A0E37329B713C71686F35"));
        if($result){
//            if($result->count_num){
            $media = new HitvMediaModel();
            $media->media_id = $result->id;
            $media->media_url = $result->url;
            $media->media_quality = $result->quality;
            $media->media_state = $result->state;
            return $media;
//            }
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