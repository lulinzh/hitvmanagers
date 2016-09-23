<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/16
 * Time: 14:42
 */
namespace Common\Lib\Hitv\Auth;
use Common\Lib\Hitv\Model\StatusModel;

class HitvSessionToken{
    const EXPIRED_TIME_LENGTH = 600;
    public $notify_result;
    public $session_id;
    public $exp_time;

    /**
     * 从token创建实例
     * @param string $set_token
     * @param StatusModel $set_status
     * @return HitvSessionToken|null
     */
    public static function createByToken($set_token, $set_status){
        //解码
        $decode_string = base64_decode($set_token);
        $json = json_decode($decode_string);
        if($json){
            $result = new HitvSessionToken();
            //解码成功
            $result->session_id = $json->session_id;
            $result->exp_time = $json->exp_time;
            return $result;
        }else {
            //解码失败
            $set_status->sessionTokenIllegal();
            return null;
        }
    }

    /**
     * 重置超时时间
     */
    public function resetExpiredTime(){
        //重新生成
        $now_time = new \DateTime();
        $exp_time = $now_time->getTimestamp() + self::EXPIRED_TIME_LENGTH;
        $this->exp_time = $exp_time;
    }

    /**
     * 将实例编码成token
     * @return string 编码后的token
     */
    public function encode(){
        return base64_encode(json_encode($this));
    }

    public function qrCode(){
        return C('QRCODE_URL') . urlencode(C('HITV_QRCODE') . $this->encode());
    }
}