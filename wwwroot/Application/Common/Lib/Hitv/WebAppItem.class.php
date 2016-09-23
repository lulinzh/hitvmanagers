<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/14
 * Time: 17:24
 */

namespace Common\Lib\Hitv;

/**
 * Class WebAppItem
 * @package Common\Lib\Hitv
 * @deprecated
 */
class WebAppItem
{
    const APP_ID = 'app_id';
    const APP_NAME = 'app_name';
    const APP_THUMB = 'app_thumb';
    const APP_SECRET = 'app_secret';
    const APP_INFO = 'app_info';
    const IS_AUTH = 'is_auth';

    public $app_id;
    public $app_name;
    public $app_thumb;
    public $app_secret;
    public $app_info;
    public $is_auth;

    /**
     * 通过数据库模型创建类
     * @param $db_item
     * @return WebAppItem
     */
    static function initFromDataBase($db_item){
        $result = new WebAppItem();
        $result->app_name = $db_item[WebAppItem::APP_NAME];
        $result->app_thumb = $db_item[WebAppItem::APP_THUMB];
        $result->app_info = $db_item[WebAppItem::APP_INFO];
        $result->is_auth = $db_item[WebAppItem::IS_AUTH];
        $result->app_id = $db_item[WebAppItem::APP_ID];
        $result->app_secret = $db_item[WebAppItem::APP_SECRET];
        return $result;
    }
}