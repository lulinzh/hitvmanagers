<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/9/2
 * Time: 16:48
 */
namespace Common\Lib\Hitv\Model;

use Common\Lib\Hitv\Auth\HitvIdModel;
use Common\Lib\Hitv\Model\StatusModel;
use Common\Lib\Hitv\Query\QueryUtils;

/**
 * lx_hitv_app 数据库模型类
 * App信息数据库字段类
 * Class HitvAppModel
 * @package Common\Lib\Hitv\Model
 */
class HitvAppModel {

    const DB_HITV_APP = 'hitv_app';

    public $app_id;
    public $app_key;
    public $app_secret;
    public $app_name;
    public $app_info;
    public $app_state;
    public $key_salt;


    /**
     * 通过AppKey反查App信息
     * @param $set_app_key
     * @return mixed
     */
    private static function getAppByKey($set_app_key){
        return M(self::DB_HITV_APP)
            ->where("app_key='{$set_app_key}'")
            ->find();
    }

    /**
     * 通过AppId反查App信息
     * @param $set_app_id
     * @return mixed
     */
    private static function getAppById($set_app_id){
        return M(self::DB_HITV_APP)
            ->where("app_id={$set_app_id}")
            ->find();
    }


    /**
     * 根据AppKey获取App信息
     * @param $set_app_key
     * @param $status StatusModel
     * @return HitvAppModel|null
     */
    public static function findHitvAppByAppKey($set_app_key, $status){
        if($set_app_key) {
            $item = self::getAppByKey($set_app_key);
            if ($item) {
                $result = new HitvAppModel();
                $result->app_id = $item['app_id'];
                $result->app_key = $item['app_key'];
                $result->app_secret = $item['app_secret'];
                $result->app_info = $item['app_info'];
                $result->app_name = $item['app_name'];
                $result->app_state = $item['app_state'];
                $result->key_salt =  $item['salt'];
                return $result;
            }
            $status->appNotFound();
        }else{
            $status->needAppKey();
        }
        return null;
    }

    /**
     * @param $set_app_id
     * @param $status StatusModel
     * @return HitvAppModel|null
     */
    public static function findHitvAppByAppId($set_app_id, $status){
        if($set_app_id) {
            $item = self::getAppById($set_app_id);
            if ($item) {
                $result = new HitvAppModel();
                $result->app_id = $item['app_id'];
                $result->app_key = $item['app_key'];
                $result->app_secret = $item['app_secret'];
                $result->app_info = $item['app_info'];
                $result->app_name = $item['app_name'];
                $result->app_state = $item['app_state'];
                $result->key_salt =  $item['salt'];
                return $result;
            }
            $status->appNotFound();
        }else{
            $status->needAppId();
        }
        return null;
    }

    /**
     * 获取此应用下用户Id的HitvId
     * @param $set_user_id
     * @param $set_status StatusModel
     * @return HitvIdModel|null
     */
    public function getHitvIdByUserId($set_user_id, $set_status){
        if($set_user_id){
            $user = HitvUserModel::getByUserId($set_user_id, $set_status);
            //QueryUtils::getUserById_v2($set_user_id);
            if($set_status->noError() && $user != null){
                $result = HitvIdModel::findHitvId( $this->app_id ,$set_user_id);
                return $result;
            }
        }
        $set_status->needUserId();
        return null;
    }

}