<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/16
 * Time: 11:55
 */

namespace Common\Lib\Hitv\Model;
use Common\Lib\Hitv\Auth\HitvUserToken;
use Common\Lib\Hitv\GDUcenterSoap;
use Common\Lib\Hitv\Query\QueryUtils;
use Think\Model;

/**
 * uc_members 数据模型类
 * Class HitvUserModel
 * @package Common\Lib\Hitv\Model
 */
class HitvUserModel {
    public $user_id;
    public $user_nickname;
    public $user_phone;
    public $user_avatar_url;

    public static function testAllUser(){
        $uc_member = new Model();
        return  $uc_member->table("uc_members a")
            ->field('a.username AS user_id, c.nickname AS user_nickname, a.username AS user_phone')
            ->join('left join lx_member c on c.uid=a.uid')
            ->select();
    }

    /**
     * 通过uid查询
     * @param $set_id
     * @return mixed
     */
    private static function getUserById($set_id){
        $uc_member = new Model();
        return  $uc_member->table("uc_members a")
            ->field('c.nickname AS user_nickname, a.username AS user_phone')
            ->where("a.uid='{$set_id}'")
            ->join('left join lx_member c on c.uid=a.uid')
            ->find();
    }

    /**
     * @param $set_user_id string
     * @param $set_status StatusModel
     * @return HitvUserModel|null
     */
    public static function getByUserId($set_user_id, $set_status){
        if($set_user_id){
            $user_data = self::getUserById($set_user_id);
            if($user_data){
                //找到用户
                $result = new HitvUserModel();
                $result->user_phone = $user_data['user_phone'];
                $result->user_nickname = $user_data['user_nickname'];
                $result->user_avatar_url = $user_data['user_avatar_url'];
                return $result;
            }else{
                //无此用户
                $set_status->userNotFound();
            }
        }else{
            //未提交参数
            $set_status->needUserId();
        }
        return null;
    }

    /**
     * @param $user_account
     * @param $user_password
     * @param $status StatusModel
     * @return String user id
     */
    public static function login($user_account,$user_password, $status){
        $xmlFields["AccountID"] = $user_account;
        $xmlFields["Password"] = $user_password;
        return '16';
//        $soap = new GDUcenterSoap();
//        $xml_result = $soap->sendLoginData($xmlFields); //发送数据，以数组方式传入参数，返回回来的是XML字符串
//        if ($xml_result) {   //调用接口成功
//            $xmlArrTmp = $soap->xmlToArray($xml_result);
//            $operResponseXML = $xmlArrTmp['soap:Envelope']['soap:Body']['ns2:operReqResponse']['operResponseXML']['value'];
//            $xmlArr = $soap->xmlToArray($operResponseXML);
//            $RspCode = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspCode']['value']; //应答错误代码
//            if ($RspCode == '0000') { //登录成功
//                $user['account'] = $user_account;
//                $user['password'] = $user_password;
//                $user['phone_imei'] = '';
//                $user['phone_mac'] = '';
//                $user['tv_code'] = '';
//                $user['nickname'] = $xmlArr['UAPRoot']['SessionBody']['AuthResp']['CustInfo']['CustName']['value']; //用户昵称
//                $user_result = self::_oldUserLogin($user);
//                if($user_result){
//                    action_log('member_login', 'member', $user_result['uid'], $user_result['uid']); //写入日志
//                    return $user_result['uid'];
//                }
//            } else { //登录失败
//                $RspDesc = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspDesc']['value']; //应答错误描述
//                $status->bossLoginFailed($RspDesc);
//            }
//        } else {
//            $status->bossLoginFailed(null);
//        }
//        return null;
    }


    /**
     * 老用户登录更新本系统用户表
     * @param $user
     * @return mixed
     */
    private static function _oldUserLogin($user)
    {
        $uc_member = new Model();
        $user_result = $uc_member->table('uc_members')->where(array('username' => $user['account']))->find();
        if (!$user_result) { //BOSS老用户，注册本系统用户
            $user_id = self::_registerUser($user);
            $user_result['uid'] = $user_id;
            $user_result['username'] = $user['account'];//Hitv使用手机号作为账号
        }
        if ($user['tv_code'] != '') { //机顶盒号存在，更新机顶盒绑定关系
            $tvcode_member = M("tvcode_member");
            $tvcode_member_result = $tvcode_member->where("tv_code=‘" . $user['tv_code'] . "’ and uid=" . $user_result['uid'])->find();
            if ($tvcode_member_result) {
                $tvcode_member_result['update_time'] = time();
                $tvcode_member_result['status'] = 1;
                $tvcode_member->save($tvcode_member_result);
            } else {
                $tvcode_member_data = array('uid' => $user_result['uid'], 'tv_code' => $user['tv_code'], 'update_time' => time());
                $tvcode_member->add($tvcode_member_data);
            }

        }
        return $user_result;
    }

    /**
     * 注册当前系统用户
     * @param $user
     * @return mixed
     */
    private static function _registerUser($user)
    {
        $salt = substr(uniqid(rand()), -6);
        $password = md5(md5($user['password']) . $salt);
        $uc_data = array(
            'username' => $user['account'],
            'password' => $password,
            'regdate' => time(),
            'salt' => $salt,
            'regip' => get_client_ip(),
            'phone_imei' => $user['phone_imei'],
            'phone_mac' => $user['phone_mac'],
            'tv_code' => $user['tv_code']);
        $uc_member = new Model();
        $user_exit = $uc_member->table("uc_members")->where(array('username' => $user['account']))->find();
        if (!$user_exit) {
            $uc_uid = $uc_member->table("uc_members")->add($uc_data);
            if ($uc_uid) {
                //插入机顶盒用户绑定表
                $lx_tvcode_member = M("tvcode_member");
                $lx_tvcode_member_data = array('uid' => $uc_uid, 'tv_code' => $user['tv_code'], 'update_time' => time(), 'status' => 1);
                $lx_tvcode_member->add($lx_tvcode_member_data);

                //插入用户资料表
                $lx_member = M("Member");
                $lx_data = array('uid' => $uc_uid, 'nickname' => $user['nickname'], 'phone' => $user['account'], 'reg_time' => time(), 'status' => 1);
                $res = $lx_member->add($lx_data);
                if (!$res) {
                    $uc_member->delete($uc_uid);
                    $uc_uid = 0;
                }
            }
        } else {
            $uc_uid = $user_exit['uid'];
        }

        return $uc_uid;
    }

    /**
     * @deprecated
     * @param $set_user_id string
     * @param $set_status StatusModel
     * @return HitvUserModel|null
     */
    public static function createByUserId($set_user_id, $set_status){
        if($set_user_id){
            $user_data = QueryUtils::getUserById($set_user_id);
            if($user_data){
                //找到用户
                $result = new HitvUserModel();
                $result->user_id = $user_data['user_id'];
                $result->user_nickname = $user_data['user_nickname'];
                $result->user_phone = $user_data['user_phone'];
                return $result;
            }else{
                //无此用户
                $set_status->userNotFound();
            }
        }else{
            //未提交参数
            $set_status->needUserId();
        }
        return null;
    }

    /**
     * @deprecated
     * @param $set_token string
     * @param $set_status StatusModel
     * @return HitvUserModel|null
     */
    public static function createByToken($set_token, $set_status){
        $decodeData = HitvUserToken::createByToken($set_token, $set_status);
        if($decodeData){
//            $nowTime = new \DateTime();
            //超时判断
//            if($nowTime->getTimestamp() > $decodeData->exp_time){
                //超时
//                $set_status->userTokenExpired();
//            }else{
                //未超时,查询用户信息
                $user_data = QueryUtils::getUserById($decodeData->user_id);
                if($user_data){
                    //找到用户
                    $result = new HitvUserModel();
                    $result->user_id = $user_data['user_id'];
                    $result->user_nickname = $user_data['user_nickname'];
                    $result->user_phone = $user_data['user_phone'];
                    return $result;
                }else{
                    //无此用户
                    $set_status->userNotFound();
                }
//            }
        }
        return null;
    }

}