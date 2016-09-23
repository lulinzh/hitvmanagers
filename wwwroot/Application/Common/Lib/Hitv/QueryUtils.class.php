<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/7
 * Time: 10:59
 */

namespace Common\Lib\Hitv;
use Think\Model;

class QueryUtils{
    const OPEN_ID_SALT = 'hitv_open_id';
    const AUTH_TOKEN_SALT = 'hitv_auth';
    const ACCESS_TOKEN_SALT = 'hitv_access';
    /*
     * 第一步 调起用户授权
     * */

    function getAuthTokenInfo($app_id, $token){
        $sqlStr = "app_id='{$app_id}' AND request_token='{$token}'";
        return M(DBString::AUTH_REQUEST)
            ->where($sqlStr)
            ->find();
    }

    function addNewAuthToken($app_id, $redirect_uri){
        $auth_request_item['app_id'] = $app_id;
        $auth_time = new \DateTime();
        $auth_time_stamp = $auth_time->getTimestamp();
        $auth_request_item['request_time'] = $auth_time_stamp;       //创建时间
        $auth_request_item['expired_time'] = $auth_time_stamp + 300; //超时时间
        $auth_request_item['redirect_uri'] = $redirect_uri;
        $random_salt = random_string();                              //随机盐
        //生成token
        $tokenString = QueryUtils::AUTH_TOKEN_SALT.$app_id.$auth_time_stamp.$random_salt;
        $token = md5($tokenString);
        $auth_request_item['request_token']= $token;
        $result = M(DBString::AUTH_REQUEST)->add($auth_request_item);
        if($result){
            return $token;
        }
        return null;
    }

    /*
     * 第二步 用户授权后返回写入授权信息返回code
     */
    function addNewAuthCode($app_id, $user_id){
        $auth_code_item['app_id'] = $app_id;
        $auth_time = new \DateTime();
        $auth_time_stamp = $auth_time->getTimestamp();
        $auth_code_item['user_id'] = $user_id;
        $auth_code_item['auth_time'] = $auth_time_stamp;            //创建时间
        $auth_code_item['expired_time'] = $auth_time_stamp + 600;   //超时时间
        $random_salt = random_string();                             //随机盐
        //生成authCode
        $authCodeString = QueryUtils::AUTH_TOKEN_SALT.$app_id.$auth_code_item.$auth_time_stamp.$random_salt;
        $auth_code = md5($authCodeString);
        $auth_code_item['auth_code']= $auth_code;
        $result = M(DBString::AUTH_CODE)->add($auth_code_item);
        if($result){
            return $auth_code;
        }
        return null;
    }

    /*
     * 第三步 网页拿access code换token
     */
    function getAccessCodeInfo($app_id, $access_code){
        $sqlStr = "app_id='{$app_id}' AND auth_code='{$access_code}'";
        return M(DBString::AUTH_CODE)
            ->where($sqlStr)
            ->find();
    }

    function addAccessTokenByItem($token_item){
        $access_token_item['app_id'] = $token_item->app_id;
        $access_token_item['user_id'] = $token_item->user_id;
        $access_token_item['access_token'] = $token_item->access_token;
        $access_token_item['refresh_token'] = $token_item->refresh_token;
        $access_token_item['create_time'] = $token_item->create_time;
        $access_token_item['expired_time'] = $token_item->expired_time;
        $access_token_item['open_id'] = $token_item->open_id;
        return M(DBString::ACCESS_TOKEN)->add($access_token_item);
    }

    function addAccessToken($app_id, $user_id, $access_token, $refresh_token,$create_time, $expired_time,$open_id){
        $access_token_item['app_id'] = $app_id;
        $access_token_item['user_id'] = $user_id;
        $access_token_item['access_token'] = $access_token;
        $access_token_item['refresh_token'] = $refresh_token;
        $access_token_item['create_time'] = $create_time;
        $access_token_item['expired_time'] = $expired_time;
        $access_token_item['open_id'] = $open_id;
        return M(DBString::ACCESS_TOKEN)->add($access_token_item);
    }

    /*
     * 第四步 拿token 换信息
     */
    function getUserInfoByAccessToken($access_token, $open_id){
        $sqlStr = "access_token='{$access_token}' AND open_id='{$open_id}'";
        return M(DBString::ACCESS_TOKEN)
            ->where($sqlStr)
            ->find();
    }
//
//    function getUser($set_id){
//        $sqlStr = "id='{$set_id}'";
//        return  M(DBString::USER)
//            ->field('id AS user_id, user_nickname, user_phone')
//            ->where($sqlStr)
//            ->find();
//    }
    function getUser($set_id){
        $sqlStr = "a.username='{$set_id}'";
        $uc_member = new Model();
        return  $uc_member->table("uc_members a")
            ->field('a.username AS user_id, c.nickname, a.username AS user_phone')
            ->where($sqlStr)
            ->join('left join lx_member c on c.uid=a.uid')
            ->find();
    }

    /**
     * @deprecated
     * @param $set_id
     * @return mixed
     */
    function getSessionInfo($set_id){
        return M(DBString::SESSION)
            ->where("id={$set_id}")
            ->find();
    }

     /**
      * @deprecated
      *
      */
    function getSessionUserInfo($set_id){

        return  M(DBString::SESSION)
            ->table(DBString::LX_SESSION.' a')
            ->join('uc_members'.' b')
            ->field('b.username, b.username AS user_phone')
            ->where("a.id={$set_id} AND a.".ParamsString::USER_ID."=b.username")
            ->find();
    }

    /**
     * @deprecated
     *
     */
    function addNewSession($session_info){
        return M(DBString::SESSION)->add($session_info);
    }

    function addNewPaymentOrder($app_id,$app_order_id, $app_order_price, $app_order_title){
        $order_item['app_id'] = $app_id;
        $order_item['app_order_id'] = $app_order_id;
        $order_item['price']= $app_order_price;
        $order_item['title'] = $app_order_title;
//        $order_item->create_time = new \DateTime();
        return M(DBString::ORDER)->add($order_item);
    }

    function getWebAppInfo($set_id){
        $sqlStr = "id='{$set_id}'";
        return  M(DBString::WEB_APP)
            ->field('id AS app_id, app_name, app_thumb, app_info, is_auth')
            ->where($sqlStr)
            ->find();
    }

    function getWebAppFullInfo($set_id){
        $sqlStr = "id='{$set_id}'";
        return  M(DBString::WEB_APP)
            ->field('id AS app_id, app_name, app_thumb, app_info, is_auth, app_secret')
            ->where($sqlStr)
            ->find();
    }


    function getOrderInfo($set_id){
        return  M(DBString::ORDER)
            ->table(DBString::LX_ORDER.' a')
            ->join(DBString::LX_WEB_APP.' b')
            ->field('a.id AS order_id,
                    a.price,
                    a.title,
                    a.info,
                    b.app_name as web_app_name,
                    b.app_thumb,
                    b.app_info,
                    b.is_auth'
            )
            ->where("a.id={$set_id} AND a.app_id=b.id")//AND c.id = a.gen_user_id
            ->find();
    }
}

