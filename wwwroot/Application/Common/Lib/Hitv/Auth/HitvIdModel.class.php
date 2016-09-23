<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/9/2
 * Time: 19:03
 */

namespace Common\Lib\Hitv\Auth;

/**
 * lx_hitv_user 数据库模型
 * 处理user_id/app_id/hitv_id关系的数据类
 * Class HitvIdModel
 * @package Common\Lib\Hitv\Auth
 */
class HitvIdModel
{
    const DB_HITV_USER = 'hitv_user';

    public $id;
    public $app_id;
    public $user_id;
    public $hitv_id;
    public $salt;

    /**
     * 创建OpenId并保存至数据库
     * @param $set_app_id
     * @param $set_user_id
     * @return HitvIdModel|null
     */
    private static function create($set_app_id, $set_user_id){
        $salt = random_string();
        $item['salt'] = $salt;
        $item['app_id'] = $set_app_id;
        $item['user_id'] = $set_user_id;
        $md5_string = json_encode($item);
        $hitv_id = md5($md5_string);
        $item['hitv_id'] = $hitv_id;
        if(M(self::DB_HITV_USER)->add($item)){
            $result = new HitvIdModel();
            $result->app_id = $set_app_id;
            $result->user_id = $set_user_id;
            $result->hitv_id = $hitv_id;
            $result->salt = $salt;
            return $result;
        }
        return null;
    }

    /**
     * 向数据库查询OpenId
     * @param $set_user_id
     * @param $set_app_id
     * @return mixed
     */
    private static function getHitvId($set_app_id, $set_user_id){
        $result = M(self::DB_HITV_USER)
            ->where("app_id={$set_app_id} and user_id={$set_user_id}")
            ->find();
        return $result;
    }


    /**
     * 查找OpenId,如果没有,创建一个并返回
     * @param $set_app_id
     * @param $set_user_id
     * @return HitvIdModel
     */
    public static function findHitvId($set_app_id, $set_user_id){
        //生成过,返回
        $item = self::getHitvId($set_app_id, $set_user_id);
        if($item){
            $result = new HitvIdModel();
            $result->id = $item['id'];
            $result->app_id = $item['app_id'];
            $result->user_id = $item['user_id'];
            $result->hitv_id = $item['hitv_id'];
            $result->salt = $item['salt'];
            return $result;
        }
        //生成新的
        $new_item = self::create($set_app_id, $set_user_id);
        return $new_item;
    }
}