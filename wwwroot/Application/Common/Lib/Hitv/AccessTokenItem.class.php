<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/14
 * Time: 19:39
 */
namespace Common\Lib\Hitv;

class AccessTokenItem{
    const OPEN_ID_SALT = 'hitv_open_id';
    const AUTH_TOKEN_SALT = 'hitv_auth';
    const ACCESS_TOKEN_SALT = 'hitv_access';
    //public $random_salt;
    public $refresh_token;
    public $access_token;
    public $open_id;
    public $app_id;
    public $user_id;
    public $create_time;
    public $expired_time;
    public static function create($set_app_id, $set_user_id){
        $result = new AccessTokenItem();
        $result->app_id = $set_app_id;
        $result->user_id = $set_user_id;
        $random_salt = random_string();
        $access_time = new \DateTime();
        $result->create_time =  $access_time->getTimestamp();
        $result->expired_time = $result->create_time + 2592000;
        $access_token_string = AccessTokenItem::ACCESS_TOKEN_SALT.$set_app_id.$set_user_id.$result->create_time.$random_salt;
        $refresh_token_string = AccessTokenItem::ACCESS_TOKEN_SALT.$set_app_id.$set_user_id.$result->expired_time.$random_salt;
        $result->access_token = md5($access_token_string);
        $result->refresh_token = md5($refresh_token_string);
        $result->open_id = md5(AccessTokenItem::OPEN_ID_SALT.$set_user_id.$set_app_id);
        return $result;
    }
}