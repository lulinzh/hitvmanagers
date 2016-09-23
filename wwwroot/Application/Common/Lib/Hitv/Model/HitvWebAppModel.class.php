<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/17
 * Time: 20:51
 */
namespace Common\Lib\Hitv\Model;

use Common\Lib\Hitv\Query\QueryUtils;

class HitvWebAppModel{
    public $app_id;
    public $app_name;
    public $app_info;
    public $app_thumb;
    public $is_auth;
    public $secret_domain;
    public $app_secret;

    public $app_index_url;
    /**
     * HitvWebAppModel constructor.
     * @deprecated
     */
    public function __construct()
    {
        $this->app_name = '测试应用';
        $this->app_thumb = 'http://projectd.in/web_app_icon.png';
        $this->app_index_url = 'http://www.baidu.com';
    }


    /**
     * @param $set_id string
     * @param $set_status StatusModel
     * @return HitvWebAppModel
     */
    public static function createByAppId($set_id, $set_status){
        if($set_id){
            $app_data = QueryUtils::getWebAppInfoById($set_id);
            if($app_data){
                $result = new HitvWebAppModel();
                $result->app_id = $app_data['id'];
                $result->app_name = $app_data['app_name'];
                $result->app_info = $app_data['app_info'];
                $result->app_thumb = $app_data['app_thumb'];
                $result->is_auth = $app_data['is_auth'];
                $result->secret_domain = $app_data['secret_domain'];
                $result->app_secret = $app_data['app_secret'];

                return $result;
            }else{
                $set_status->webAppNotFound();
            }
        }else{
            $set_status->needWebAppId();
        }
        return null;
    }



    /**
     * @deprecated 使用HitvAppModel getOpenIdById
     * StatusModel
     * @param $set_user_id
     * @param $set_status StatusModel
     * @return null|string
     */
    public function createOpenIdByUserId($set_user_id, $set_status){
        if($set_user_id){
            $obj = array('uid' => $set_user_id, 'app_id' => $this->app_id);
            $string = base64_encode(json_encode($obj));
            return $string;
        }
        $set_status->needUserId();
        return null;
    }

    /**
     * StatusModel
     * @param $set_open_id
     * @param $set_status StatusModel
     * @return null|string
     */
    public function createUserIdByOpenId($set_open_id, $set_status){
        if($set_open_id){
            $code = base64_decode($set_open_id);
            if($code){
                $result = json_decode($code);
                $user_id = $result->uid;
                $app_key = $result->app_id;
                if($this->app_id == $app_key){
                    if($user_id){
                        return $user_id;
                    }
                }
            }
            $set_status->openIdIsIllegal();
            return $code;
        }else{
            $set_status->needUserId();
        }
        return null;
    }
}