<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/16
 * Time: 10:53
 */

namespace Common\Lib\Hitv\Auth;
class TokenGenUtils{
    static function encodeUserToken($user_id){
        $now_time = new \DateTime();
        $exp_time = $now_time->getTimestamp() + 3600;
        $encode_data = new HitvUserToken();
        $encode_data->exp_time = $exp_time;
        $encode_data->user_id = $user_id;
        return base64_encode(json_encode($encode_data));
    }

    static function decodeUserToken($user_token){
        $decode_string = base64_decode($user_token);
        $json = json_decode($decode_string);
        $user = new HitvUserToken();
        $user->user_id = $json->user_id;
        $user->exp_time = $json->exp_time;
        return $user;
    }
}