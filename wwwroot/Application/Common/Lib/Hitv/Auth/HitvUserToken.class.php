<?php
/**
 * @deprecated
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/16
 * Time: 11:01
 */
namespace Common\Lib\Hitv\Auth;
use Common\Lib\Hitv\Model\StatusModel;
class HitvUserToken {
    const ACCESS_TOKEN_EXPIRED_TIME_LENGTH = 3600;
    const REFRESH_TOKEN_EXPIRED_TIME_LENGTH = 2592000;
    public $open_id;
    public $access_token;
    public $refresh_token;

    /**
     * @param $set_open_id
     * @param $status StatusModel
     * @return HitvUserToken|null
     */
    public static function create($set_open_id, $status){
        if($set_open_id){
            $token = new HitvUserToken();
            $token->open_id = $set_open_id;
            $now_time = new \DateTime();
            $access_exp_time = $now_time->getTimestamp() + self::ACCESS_TOKEN_EXPIRED_TIME_LENGTH;
            $refresh_exp_time = $now_time->getTimestamp() + self::REFRESH_TOKEN_EXPIRED_TIME_LENGTH;
            $token->access_token = md5("hitv{$access_exp_time}access");
            $token->refresh_token = md5("hitv{$refresh_exp_time}refresh");
            return $token;
        }
        $status->needUserId();
        return null;
    }





    /**
     * @deprecated
     * @var String 用户id
     */
    public $user_id;

    /**
     * @deprecated
     * @var int token超时时间
     */
    public $exp_time;

    /**
     * 从token创建实例
     * @deprecated
     * @param string $set_token
     * @param StatusModel $set_status
     * @return HitvUserToken|null
     */
    public static function createByToken($set_token, $set_status){
        //解码
        $decode_string = base64_decode($set_token);
        $json = json_decode($decode_string);
        if($json){
            $result = new HitvUserToken();
            //解码成功
            $result->user_id = $json->user_id;
            $result->exp_time = $json->exp_time;
            return $result;
        }else {
            //解码失败
            $set_status->userTokenIllegal();
            return null;
        }
    }

    /**
     * 重置超时时间
     * @deprecated
     */
    public function resetExpiredTime(){
        //重新生成
        $now_time = new \DateTime();
        $exp_time = $now_time->getTimestamp() + self::EXPIRED_TIME_LENGTH;
        $this->exp_time = $exp_time;
    }

    /**
     * 将实例编码成token
     * @deprecated
     * @return string 编码后的token
     */
    public function encode(){
        return base64_encode(json_encode($this));
    }
}