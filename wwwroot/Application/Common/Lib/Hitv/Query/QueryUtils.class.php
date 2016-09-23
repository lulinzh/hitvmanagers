<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/16
 * Time: 11:52
 */

namespace Common\Lib\Hitv\Query;
use Common\Lib\Hitv\Auth\HitvIdModel;
use Think\Model;

class QueryUtils{
    /**
     * 通过HitvId反查用户
     * @param $set_hitv_id
     * @param $set_app_id
     * @return mixed
     */
    static function getUserByHitvId($set_hitv_id, $set_app_id){
        return M(DBString::HITV_USER)
            ->where("hitv_id={$set_hitv_id} and app_id={$set_app_id}")
            ->find();
    }






    /**
     * 旧接口=============================================================================
     */


    /**
     * 通过电话号码查询
     * @deprecated
     * @param $set_id
     * @return mixed
     */
    static function getUserById($set_id){
        $uc_member = new Model();
        return  $uc_member->table("uc_members a")
            ->field('a.username AS user_id, c.nickname AS user_nickname, a.username AS user_phone')
            ->where("a.username='{$set_id}'")
            ->join('left join lx_member c on c.uid=a.uid')
            ->find();
    }

    static function getUserIdByPhone($set_id){
        $uc_member = new Model();
        return  $uc_member->table("uc_members")
            ->field('uid')
            ->where("username='{$set_id}'")
            ->find();
    }

    static function getSessionById($set_id){
        return M(DBString::SESSION)
            ->where("id={$set_id}")
            ->find();
    }


    static function addNewSession(
        $session_type,
        $device_type,
        $device_id,
        $in_param = '',
        $user_id = ''
    ){
        $session_info['device_id'] = $device_id;
        $session_info['session_type'] = $session_type;
        $session_info['device_type'] = $device_type;
        if($in_param){
            $session_info['session_in_param'] = $in_param;
        }
        if($user_id){
            $session_info['user_id'] = $user_id;
        }
        return M(DBString::SESSION)->add($session_info);
    }

    static function setSessionInfo(
        $session_id,
        $session_type,
        $session_status,
        $device_type,
        $device_id,
        $in_param = '',
        $user_id = '',
        $out_param = ''
    ){
        if($out_param){
            $session_info['session_out_param'] = $out_param;
        }
        $session_info['id'] = $session_id;
        $session_info['session_status'] = $session_status;
        $session_info['session_type'] = $session_type;

        $session_info['device_type'] = $device_type;
        $session_info['device_id'] = $device_id;
        if($in_param){
            $session_info['session_in_param'] = $in_param;
        }
        if($user_id){
            $session_info['user_id'] = $user_id;
        }
        if($out_param){
            $session_info['session_out_param'] = $out_param;
        }
        return M(DBString::SESSION)->save($session_info);
    }




    static function setDeviceToUser($set_device_id, $set_user_id ){
        $user = QueryUtils::getUserIdByPhone($set_user_id);
        if($user){
            $tvcode_member = M("tvcode_member");
            $tvcode_member_result = $tvcode_member->where("tv_code=" . $set_device_id . " and uid=" . $user['uid'])->find();
            if ($tvcode_member_result) {
                $tvcode_member_result['update_time'] = time();
                $tvcode_member_result['status'] = 1;
                return $tvcode_member->save($tvcode_member_result);
            }
            $tvcode_member_data = array('uid' => $user['uid'], 'tv_code' => $set_device_id, 'update_time' => time());
            return $tvcode_member->add($tvcode_member_data);
        }
        return null;
    }

    static function getOrderById($set_id){
        return  M(DBString::ORDER)
//            ->field('id AS order_id,
//                    title,
//                    info,
//                    price,
//                    app_order_id,
//                    app_id,
//                    create_time,
//                    pay_time,
//                    order_id,
//                    gen_user_id,
//                    pay_user_id'
//            )
            ->where("id={$set_id}")
            ->find();
    }

     static function addNewOrder(
        $set_title,
        $set_info,
        $set_price,
        $set_app_order_id,
        $set_app_id,
        $set_gen_user_id
        ){
        $order_info['title'] = $set_title;
        $order_info['info'] = $set_info;
        $order_info['price'] = $set_price;
        $order_info['app_order_id'] = $set_app_order_id;
        $order_info['app_id'] = $set_app_id;
        if($set_gen_user_id){
            $order_info['gen_user_id'] = $set_gen_user_id;
        }
        $now_time = new \DateTime();
        $order_info['create_time'] = $now_time->getTimestamp();
        $order_info['title'] = $set_title;
        return M(DBString::ORDER)->add($order_info);
    }

    static function setOrderInfo(
        $order_id,
        $title,
        $info,
        $app_order_id,
        $app_id,
        $create_time,
        $pay_time,
        $price,
        $gen_user_id,
        $pay_user_id
    ){
        $session_info['id'] = $order_id;
        $session_info['title'] = $title;
        $session_info['info'] = $info;

        $session_info['app_order_id'] = $app_order_id;
        $session_info['app_id'] = $app_id;
        $session_info['create_time'] = $create_time;
        $session_info['price'] = $price;

        if($pay_time){
            $session_info['pay_time'] = $pay_time;
        }
        if($gen_user_id){
            $session_info['gen_user_id'] = $gen_user_id;
        }
        if($pay_user_id){
            $session_info['pay_user_id'] = $pay_user_id;
        }
        return M(DBString::ORDER)->save($session_info);
    }

    static function getWebAppInfoById($set_id){
        return  M(DBString::WEB_APP)
            ->where("id={$set_id}")
            ->find();
    }
}