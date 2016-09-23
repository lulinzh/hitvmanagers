<?php
/**
 * Created by PhpStorm.
 * User: Tiger
 * Date: 2016/7/12
 * Time: 17:46
 */

namespace Common\Lib\Hitv;

use Admin\Model\ConfigModel;
use Common\Lib\Hitv\Model\HitvUserModel;
use Think\Model;
use Common\Lib\Hitv\GDUcenterSoap;
use Common\Lib\Hitv\StatusObject;
use Common\Lib\Hitv\Auth\HitvUserToken;

use Common\Lib\Hitv\Model\StatusModel;
use Common\Lib\Hitv\Model\HitvWebAppModel;

class HitvUser
{
    /**
     * @deprecated
     * 获取BOSS用户中心注册验证码
     * @param $account String 手机号码
     * @return mixed 返回JSON字符串
     */
    public function getVerifyCode($account)
    {
        if ($account) {    //提交资料正确
            $xmlFields["AccountID"] = $account;    //手机号

            $soap = new GDUcenterSoap();
            $xml_result = $soap->sendSMSData($xmlFields); //发送数据，以数组方式传入参数，返回回来的是XML字符串

            if ($xml_result) {   //调用接口成功
                $xmlArrTmp = $soap->xmlToArray($xml_result);
                $operResponseXML = $xmlArrTmp['soap:Envelope']['soap:Body']['ns2:operReqResponse']['operResponseXML']['value'];
                $xmlArr = $soap->xmlToArray($operResponseXML);
                $RspCode = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspCode']['value']; //应答错误代码
                $RspDesc = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspDesc']['value']; //应答错误描述
                if ($RspCode == '0000') { //发验证码成功
                    $RandomCodeID = $xmlArr['UAPRoot']['SessionBody']['AuthResp']['RandomCodeID']['value']; //短信随机码流水ID
                    $resultData = array('status' => '0', 'msg' => '验证码发送成功', 'data' => array('codeid' => $RandomCodeID));
                    //写入短信发送日志
                    $this->_saveSmsLog(array('phone' => $account, 'codeid' => $RandomCodeID));
                } else if ($RspCode == '1002' && $RspDesc == '帐号已经存在') { //帐号已经存在
                    $resultData = array('status' => '1', 'msg' => '帐号已经存在');
                } else { //验证码发送失败
                    $RspDesc = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspDesc']['value']; //应答错误描述
                    $resultData = array('status' => '2', 'msg' => '验证码发送失败：' . $RspDesc);
                }
            } else {
                $resultData = array('status' => '3', 'msg' => '网络错误！请重试');
            }
        } else { //提交资料不正确
            $resultData = array('status' => '4', 'msg' => '请检查您的手机号码');
        }

        return $resultData;
    }

    /**
     * 获取BOSS用户中心注册验证码 v2
     * @param $account
     * @param $type
     * @param $status StatusModel
     * @return array
     */
    public function getVerifyCode_v2($account, $type,  $status)
    {
        $is_reset = false;
        if($type == 'reset_password'){
            $is_reset = true;
        }else if($type == 'register'){
            $is_reset = false;
        }else{
            $status->needVerifyType();
            return null;
        }
        if ($account) {    //提交资料正确
            $xmlFields["AccountID"] = $account;    //手机号

            $soap = new GDUcenterSoap();
            $xml_result = $soap->sendSMSData($xmlFields, $is_reset); //发送数据，以数组方式传入参数，返回回来的是XML字符串
            if ($xml_result) {   //调用接口成功
                $xmlArrTmp = $soap->xmlToArray($xml_result);
                $operResponseXML = $xmlArrTmp['soap:Envelope']['soap:Body']['ns2:operReqResponse']['operResponseXML']['value'];
                $xmlArr = $soap->xmlToArray($operResponseXML);
                $RspCode = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspCode']['value']; //应答错误代码
                $RspDesc = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspDesc']['value']; //应答错误描述
                if ($RspCode == '0000') { //发验证码成功
                    $RandomCodeID = $xmlArr['UAPRoot']['SessionBody']['AuthResp']['RandomCodeID']['value']; //短信随机码流水ID
                    //$resultData = array('status' => '0', 'msg' => '验证码发送成功', 'data' => array('codeid' => $RandomCodeID));
                    //写入短信发送日志
                    $this->_saveSmsLog(array('phone' => $account, 'codeid' => $RandomCodeID));
                    $result['verify_id'] = $RandomCodeID;
                    return $result;
                } else if ($RspCode == '1002' && $RspDesc == '帐号已经存在') { //帐号已经存在
                    $status->userPhoneIsExisted();
                } else { //验证码发送失败
                    $RspDesc = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspDesc']['value']; //应答错误描述
                    $status->bossVerifyFailed($RspDesc);
                }
            } else {
                $status->bossLoginFailed(null);
            }
        } else { //提交资料不正确
            $status->needUserPhone();
        }
        return null;
    }

    public function doNickNameChange($account, $nickname, $status){

    }

    /**
     * 注册接口
     * @param $account
     * @param $password
     * @param $nickname
     * @param $verify_id
     * @param $verify_code
     * @param $web_app_item HitvWebAppModel
     * @param $status StatusModel
     * @return  HitvUserToken
     */
    public function doRegister_v2($account, $password, $nickname, $verify_id, $verify_code, $web_app_item, $status){
        if(!$account){
            $status->needUserPhone();
            return null;
        }
        if(!$password){
            $status->needUserPassword();
            return null;
        }
        if(!$nickname){
            $status->needUserNickName();
            return null;
        }
        if(!$verify_id){
            $status->needVerifyId();
            return null;
        }
        if(!$verify_code){
            $status->needVerifyCode();
            return null;
        }
        $user = array(
            'account'=>$account,
            'password'=>$password,
            'nickname'=>$nickname,
            'vcode'=>$verify_code,
            'codeid'=>$verify_id,
            'phone_imei'=>'',
            'phone_mac'=>'',
            'tv_code'=>''
        );
        if ($this->_checkMobile($user['account'])) {
            $xmlFields["AccountID"] = $user['account'];    //账号
            $xmlFields["Password"] = $user['password'];    //密码
            $xmlFields["CustName"] = $user['nickname'];    //昵称
            $xmlFields["RandomCode"] = $user['vcode'];    //短信验证码
            $xmlFields["RandomCodeID"] = $user['codeid'];    //短信流水号
            $xmlFields["CustCode"] = ''; //客户编号

            $soap = new GDUcenterSoap();
            $xml_result = $soap->sendRegData($xmlFields); //发送数据，以数组方式传入参数，返回回来的是XML字符串

            if ($xml_result) {   //调用接口成功
                $xmlArrTmp = $soap->xmlToArray($xml_result);
                $operResponseXML = $xmlArrTmp['soap:Envelope']['soap:Body']['ns2:operReqResponse']['operResponseXML']['value'];
                $xmlArr = $soap->xmlToArray($operResponseXML);
                $RspCode = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspCode']['value']; //应答错误代码
                $RspDesc = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspDesc']['value']; //应答错误描述
                if ($RspCode == '0000') { //注册成功
                    $user_id = $this->_registerUser($user);
                    action_log('member_register', 'member', $user_id, $user_id); //写入日志
                    $loginResult = $this->doLogin_v2($account,$password,$web_app_item, $status);
                    if($status->noError()){
                        return $loginResult;
                    }
                    //$resultData = array('status' => '0', 'msg' => '注册成功', 'data' => array('username' => $user['account'], 'nickname' => $user['nickname']));
                } else if ($RspCode == '1002' && $RspDesc == '帐号已经存在') { //帐号已经存在
                    $status->userPhoneIsExisted();
//                    $resultData = array('status' => '2', 'msg' => '帐号已经存在');
                } else { //注册失败
                    //$resultData = array('status' => '3', 'msg' => '注册失败：' . $RspDesc);
                    $status->bossLoginFailed($RspDesc);
                }
            } else { //调用注册接口失败
                $status->bossLoginFailed(null);
            }
        } else {
            $status->needUserPhone();
        }

        return null;
    }

        /**
     * @deprecated
     * 短信验证码注册BOSS用户中心
     * @param $user
     * @return array
     */
    public function doRegister($user)
    {
        if ($this->_checkMobile($user['account'])) {
            $xmlFields["AccountID"] = $user['account'];    //账号
            $xmlFields["Password"] = $user['password'];    //密码
            $xmlFields["CustName"] = $user['nickname'];    //昵称
            $xmlFields["RandomCode"] = $user['vcode'];    //短信验证码
            $xmlFields["RandomCodeID"] = $user['codeid'];    //短信流水号
            $xmlFields["CustCode"] = ''; //客户编号

            $soap = new GDUcenterSoap();
            $xml_result = $soap->sendRegData($xmlFields); //发送数据，以数组方式传入参数，返回回来的是XML字符串

            if ($xml_result) {   //调用接口成功
                $xmlArrTmp = $soap->xmlToArray($xml_result);
                $operResponseXML = $xmlArrTmp['soap:Envelope']['soap:Body']['ns2:operReqResponse']['operResponseXML']['value'];
                $xmlArr = $soap->xmlToArray($operResponseXML);
                $RspCode = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspCode']['value']; //应答错误代码
                $RspDesc = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspDesc']['value']; //应答错误描述
                if ($RspCode == '0000') { //注册成功
                    $user_id = $this->_registerUser($user);
                    action_log('member_register', 'member', $user_id, $user_id); //写入日志
                    $resultData = array('status' => '0', 'msg' => '注册成功', 'data' => array('username' => $user['account'], 'nickname' => $user['nickname']));
                } else if ($RspCode == '1002' && $RspDesc == '帐号已经存在') { //帐号已经存在
                    $resultData = array('status' => '2', 'msg' => '帐号已经存在');
                } else { //注册失败
                    $resultData = array('status' => '3', 'msg' => '注册失败：' . $RspDesc);
                }
            } else { //调用注册接口失败
                $resultData = array('status' => '4', 'msg' => '网络错误！请重试');
            }
        } else {
            $resultData = array('status' => '1', 'msg' => '请检查您的手机号码');
        }

        return $resultData;
    }

    /**
     * 登录BOSS用户中心
     * @param $user_account
     * @param $user_password
     * @param $web_app_item HitvWebAppModel
     * @param $status StatusModel
     * @return HitvUserToken token
     */
    public function doLogin_v2($user_account,$user_password,$web_app_item , $status)
    {
        if ($this->_checkMobile($user_account)) {
            $xmlFields["AccountID"] = $user_account;
            $xmlFields["Password"] = $user_password;
            $soap = new GDUcenterSoap();
            $xml_result = $soap->sendLoginData($xmlFields); //发送数据，以数组方式传入参数，返回回来的是XML字符串
            if ($xml_result) {   //调用接口成功
                $xmlArrTmp = $soap->xmlToArray($xml_result);
                $operResponseXML = $xmlArrTmp['soap:Envelope']['soap:Body']['ns2:operReqResponse']['operResponseXML']['value'];
                $xmlArr = $soap->xmlToArray($operResponseXML);
                $RspCode = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspCode']['value']; //应答错误代码
                if ($RspCode == '0000') { //登录成功
//                    $resultData = array('status' => '0', 'msg' => '登录成功');
                    $user['account'] = $user_account;
                    $user['password'] = $user_password;
                    $user['phone_imei'] = '';
                    $user['phone_mac'] = '';
                    $user['tv_code'] = '';
                    $user['nickname'] = $xmlArr['UAPRoot']['SessionBody']['AuthResp']['CustInfo']['CustName']['value']; //用户昵称
                    $user_result = $this->_oldUserLogin($user);
                    action_log('member_login', 'member', $user_result['uid'], $user_result['uid']); //写入日志
                    //生成token
                    $open_id = $web_app_item->createOpenIdByUserId($user_result['uid'],$status);
                    if($status->noError()){
                        $token = HitvUserToken::create($open_id, $status);
                        return $token;
                    }
                } else { //登录失败
                    $RspDesc = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspDesc']['value']; //应答错误描述
                    $status->bossLoginFailed($RspDesc);
                }
            } else {
                $status->bossLoginFailed(null);
            }
        } else { //提交资料不正确
            $status->needUserPhone();
        }

        return null;
    }

    /**
     * @param $account
     * @param $password
     * @param $verify_id
     * @param $verify_code
     * @param $status StatusModel
     */
    public function doPasswordReset($account, $password, $verify_id, $verify_code , $status){
        if(!$account){
            $status->needUserPhone();
            return;
        }
        if(!$password){
            $status->needUserPassword();
            return;
        }
        if(!$verify_id){
            $status->needVerifyId();
            return;
        }
        if(!$verify_code){
            $status->needVerifyCode();
            return;
        }
        if ($this->_checkMobile($account)) {
            $xmlFields["Account"] = $account;
            $xmlFields["RandomCodeID"] = $verify_id;
            $xmlFields["RandomCode"] = $verify_code;
            $xmlFields["NewPassword"] = $password;
            $soap = new GDUcenterSoap();
            $xml_result = $soap->sendPasswordResetData($xmlFields);
            if ($xml_result) {   //调用接口成功
                $xmlArrTmp = $soap->xmlToArray($xml_result);
                $operResponseXML = $xmlArrTmp['soap:Envelope']['soap:Body']['ns2:operReqResponse']['operResponseXML']['value'];
                $xmlArr = $soap->xmlToArray($operResponseXML);
                $RspCode = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspCode']['value']; //应答错误代码
                if ($RspCode == '0000') { //登录成功
                    // $user['account'] = $user_account;
//                    $user['password'] = $user_password;
//                    $user['nickname'] = $xmlArr['UAPRoot']['SessionBody']['AuthResp']['CustInfo']['CustName']['value']; //用户昵称
//                    $user_result = $this->_oldUserLogin($user);
//                    action_log('member_login', 'member', $user_result['uid'], $user_result['uid']); //写入日志
//                    //生成token

                } else { //登录失败
                    $RspDesc = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspDesc']['value']; //应答错误描述
                    $status->bossLoginFailed($RspDesc);
                }
            } else {
                $status->bossLoginFailed(null);
            }
        }else { //提交资料不正确
            $status->needUserPhone();
        }
    }

    /**
     * @deprecated
     * 登录BOSS用户中心
     * @param $user
     * @return array
     */
    public function doLogin($user)
    {
        $result->status = new StatusObject();
        if ($this->_checkMobile($user['account'])) {
            $xmlFields["AccountID"] = $user['account'];    //手机号
            $xmlFields["Password"] = $user['password'];    //手机号

            $soap = new GDUcenterSoap();
            $xml_result = $soap->sendLoginData($xmlFields); //发送数据，以数组方式传入参数，返回回来的是XML字符串

            if ($xml_result) {   //调用接口成功
                $xmlArrTmp = $soap->xmlToArray($xml_result);
                $operResponseXML = $xmlArrTmp['soap:Envelope']['soap:Body']['ns2:operReqResponse']['operResponseXML']['value'];
                $xmlArr = $soap->xmlToArray($operResponseXML);
                $RspCode = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspCode']['value']; //应答错误代码
                if ($RspCode == '0000') { //登录成功
//                    $resultData = array('status' => '0', 'msg' => '登录成功');
                    $user['nickname'] = $xmlArr['UAPRoot']['SessionBody']['AuthResp']['CustInfo']['CustName']['value']; //用户昵称
                    $user_result = $this->_oldUserLogin($user);
                    action_log('member_login', 'member', $user_result['uid'], $user_result['uid']); //写入日志
                    //生成token
                    $token = new HitvUserToken();
                    $token->user_id = $user_result['username'];
                    $result->user->user_token = $token->encode();
                    $result->user->user_id = $token->user_id;
                    $result->user->user_nickname = $user['nickname'];
                } else { //登录失败
                    $RspDesc = $xmlArr['UAPRoot']['SessionHeader']['Response']['RspDesc']['value']; //应答错误描述
                    $result->status->setBossLoginFailed();
                    $result->status->message = $RspDesc;
                    //$resultData = array('status' => '2', 'msg' => '登录失败：' . $RspDesc);
                }
            } else {
                $result->status->setErrorUnknown();
//                $resultData = array('status' => '3', 'msg' => '网络错误！请重试');
            }
        } else { //提交资料不正确
            $result->status->setPhoneNumberError();
            //$resultData = array('status' => '4', 'msg' => '请检查您的手机号码');
        }

        return $result;
    }

    /**
     * 根据机顶盒号查询最后绑定的用户账号
     * @param $tv_code
     * @return string
     */
    public function getUserByTvcode($tv_code)
    {
        $lx_tvcode_member = M("tvcode_member");
        $result = $lx_tvcode_member->table('lx_tvcode_member a')
            ->field('b.username,c.nickname')
            ->where("a.tv_code='" . $tv_code . "' and a.status=1")
            ->join('left join uc_members b on b.uid=a.uid')
            ->join('left join lx_member c on c.uid=a.uid')
            ->order('a.update_time desc')->find();
        if ($result) {
            $resultData = array('status' => '0', 'msg' => '获取成功', 'data' => $result);
        } else {
            $resultData = array('status' => '1', 'msg' => '该机顶盒尚未绑定账号');
        }
        return $resultData;
    }

    /**
     * 用户登出，取消绑定
     * @param $user
     * @return array
     */
    public function loginOut($user)
    {
        $tvcode_member = M("tvcode_member");
        $result = $tvcode_member->table('lx_tvcode_member a,uc_members b')
            ->where("a.uid=b.uid and b.username='" . $user['account'] . "' and a.tv_code='" . $user['tv_code'] . "'")
            ->save(array('status' => 0));
        $resultData = array('status' => '0', 'msg' => '登出成功');
        return $resultData;
    }

    /**
     * 获取用户信息通过账号
     * @param $username
     * @return mixed
     */
    public function getUserInfoByUsername($username)
    {
        $uc_members = new Model();
        $uc_members_result = $uc_members->table('uc_members a')->field('a.uid,a.username,b.nickname')
            ->join('left join lx_member b on b.uid=a.uid')
            ->where("a.username='$username'")->find();
        return $uc_members_result;
    }

    /**
     * 注册当前系统用户
     * @param $user
     * @return mixed
     */
    private function _registerUser($user)
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
     * 老用户登录更新本系统用户表
     * @param $user
     * @return mixed
     */
    private function _oldUserLogin($user)
    {
        $uc_member = new Model();
        $user_result = $uc_member->table('uc_members')->where(array('username' => $user['account']))->find();
        if (!$user_result) { //BOSS老用户，注册本系统用户
            $user_id = $this->_registerUser($user);
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
     * 存储短信发送日志
     * @param $sms
     * @return mixed
     */
    private function _saveSmsLog($sms)
    {
        $sms_data = array(
            'phone' => $sms['phone'],
            'ip' => get_client_ip(),
            'addtime' => time(),
            'codeid' => $sms['codeid']);
        $send_sms_log = M('send_sms_log');
        $log_id = $send_sms_log->add($sms_data);

        return $log_id;
    }

    /**
     * 检测手机
     * @param  string $mobile 手机
     * @return integer         错误编号
     */
    private function _checkMobile($mobile)
    {
        if (!is_numeric($mobile)) {
            return false;
        }
        return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
    }

}