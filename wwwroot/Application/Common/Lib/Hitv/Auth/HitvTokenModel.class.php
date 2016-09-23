<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/9/2
 * Time: 21:20
 */

namespace Common\Lib\Hitv\Auth;


use Common\Lib\Hitv\Model\StatusModel;

class HitvTokenModel{
    const DB_USER_TOKEN = 'hitv_user_token';
    const ACCESS_TOKEN_EXPIRED_TIME = 3600;
    const REFRESH_TOKEN_EXPIRED_TIME = 2592000;


    public $id; //自增id
    public $hitv_id;	//	hitvid
    public $app_id;	//	应用.id
    public $access_token;	//	访问token
    public $access_expired_time;	//	访问超时时间
    public $refresh_token;	//	刷新token
    public $refresh_expired_time;	//	刷新超时时间


    private static function getToken($hitv_id, $app_id){
        return M(self::DB_USER_TOKEN)
            ->where("hitv_id='{$hitv_id}' and app_id={$app_id}")
            ->find();
    }

    /**
     * 新建token并保存至数据库
     * @param $hitv_id
     * @param $app_id
     * @return mixed
     */
    private static function createToken($hitv_id, $app_id){
        $item['hitv_id'] = $hitv_id;
        $item['app_id'] = $app_id;
        $time = new \DateTime();
        $time_stamp = $time->getTimestamp();
        $item['access_expired_time'] = $time_stamp + self::ACCESS_TOKEN_EXPIRED_TIME;
        $item['refresh_expired_time'] = $time_stamp + self::REFRESH_TOKEN_EXPIRED_TIME;
        $access_token = md5("access_token_".json_encode($item));
        $refresh_token = md5("refresh_token_".json_encode($item));
        $item['access_token'] = $access_token;
        $item['refresh_token'] = $refresh_token;
        if( M(self::DB_USER_TOKEN)->add($item)){
            $result = new HitvTokenModel();
            $result->app_id = $item['app_id'];
            $result->hitv_id = $item['hitv_id'];
            $result->access_token = $item['access_token'];
            $result->access_expired_time = $item['access_expired_time'];
            $result->refresh_token = $item['refresh_token'];
            $result->refresh_expired_time = $item['refresh_expired_time'];
            return $result;
        }
        return null;
    }

    /**
     * 刷新当前整个token
     */
    private function refreshToken(){
        $item['id'] = $this->id;
        $item['hitv_id'] = $this->hitv_id;
        $item['app_id'] = $this->app_id;
        $time = new \DateTime();
        $time_stamp = $time->getTimestamp();
        $item['access_expired_time'] = $time_stamp + self::ACCESS_TOKEN_EXPIRED_TIME;
        $item['refresh_expired_time'] = $time_stamp + self::REFRESH_TOKEN_EXPIRED_TIME;
        $access_token = md5("access_token_".json_encode($item));
        $refresh_token = md5("refresh_token_".json_encode($item));
        $item['access_token'] = $access_token;
        $item['refresh_token'] = $refresh_token;
        if( M(self::DB_USER_TOKEN)->save($item)){
            $this->refresh_token = $item['refresh_token'];
            $this->access_token = $item['access_token'];
        }
    }


    /**
     * 登录返回token
     * @param $hitv_id
     * @param $app_id
     * @param $status StatusModel
     * @return HitvTokenModel|null
     */
    public static function refreshTokenByLogin($hitv_id, $app_id, $status){
        $item = self::getToken($hitv_id, $app_id);
        //有此token 刷新
        if($item){
            $result = new HitvTokenModel();
            $result->id = $item['id'];
            $result->app_id = $item['app_id'];
            $result->hitv_id = $item['hitv_id'];
            $result->access_token = $item['access_token'];
            $result->access_expired_time = $item['access_expired_time'];
            $result->refresh_token = $item['refresh_token'];
            $result->refresh_expired_time = $item['refresh_expired_time'];
            $result->refreshToken();
            return $result;
        }else{
            //无此token 创建
            $result = self::createToken($hitv_id, $app_id);
            if($result){
                return $result;
            }else{
               $status->createTokenFailed();
            }
        }
        return null;
    }
}