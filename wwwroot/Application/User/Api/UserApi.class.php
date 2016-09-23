<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace User\Api;
use User\Api\Api;


/**
 * 用户接口 
 * @author 统一在这里调用用户中心接口
 *
 */
class UserApi extends Api{
    /**
     * 构造方法，实例化操作模型
     */
    protected function _init(){
    	load_ucenter();
    }

    /**
     * 注册一个新用户
     * @param  string $username 用户名
     * @param  string $password 用户密码
     * @param  string $email    用户邮箱
     * @param  string $mobile   用户手机号码
     * @return integer          注册成功-用户信息，注册失败-错误编号
     */
    public function register($username, $password, $email, $mobile = ''){
    	//调用ucenter接口
    	return uc_user_register($username, $password, $email);
    }
    
    /**
     * 获取注册错误信息  参考http://faq.comsenz.com/library/UCenter/interface/interface_user.htm
     */
    public function showRegError($code){
    	switch ($code) {
    		case -1:  $error = '用户名不合法'; break;
    		case -2:  $error = '包含不允许注册的词语'; break;
    		case -3:  $error = '用户名已经存在'; break;
    		case -4:  $error = 'Email 格式有误'; break;
    		case -5:  $error = 'Email 不允许注册'; break;
    		case -6:  $error = '该 Email 已经被注册'; break;
    		default:  $error = '未知错误['.$code.']';
    	}
    	return $error;
    }

    public function delete($ucuid){
    	//调用ucenter接口
    	return uc_user_delete($ucuid);
    }
    
    
    /**
     * 用户登录认证  参考http://faq.comsenz.com/library/UCenter/interface/interface_user.htm
     */
    public function login($username, $password, $isuid = 0){
    	//调用ucenter接口
    	list($uid, $username, $password, $email) = uc_user_login($username, $password, $isuid);
    	return $uid;
    }
    

    /**
     * 获取登录错误信息  参考http://faq.comsenz.com/library/UCenter/interface/interface_user.htm
     */
    public function showLoginError($code){
    	switch ($code) {
    		case -1:  $error = '用户不存在，或者被删除'; break;
    		case -2:  $error = '密码错误'; break;
    		case -3:  $error = '安全提问错'; break;
    		default:  $error = '未知错误['.$code.']';
    	}
    	return $error;
    }
    
    
    

    /**
     * 获取用户信息
     * @param  string  $username
     * @param  boolean $isuid
     * @return array                array	integer [0]	用户 ID      string [1]	用户名     string [2]	Email
     */
    public function info($username, $isuid=0){
    	return uc_get_user($username, $isuid);
    }

    /**
     * 检测用户名    参考http://faq.comsenz.com/library/UCenter/interface/interface_user.htm
     */
    public function checkUsername($username){
    	$ucresult = uc_user_checkname($username);
    	return $ucresult;
    }

    public function showCheckUsernameError($code){
    	switch ($code) {
    		case -1:  $error = '用户名不合法'; break;
    		case -2:  $error = '包含要允许注册的词语'; break;
    		case -3:  $error = '用户名已经存在'; break;
    		default:  $error = '未知错误['.$code.']';
    	}
    	return $error;
    }
    

    /**
     * 检测邮箱
     * @param  string  $email  邮箱
     * @return integer         错误编号  参考http://faq.comsenz.com/library/UCenter/interface/interface_user.htm
     */
    public function checkEmail($email){
    	$ucresult = uc_user_checkemail($email);
    	return $ucresult;
    }


    /**
     * 检测手机
     * @param  string  $mobile  手机
     * @return integer         错误编号
     */
    public function checkMobile($mobile){
        return 1;
    }

    

    /**
     * 更新用户信息   参考http://faq.comsenz.com/library/UCenter/interface/interface_user.htm
     */
    public function edit($username, $oldpw , $newpw , $email, $isuid=0){
    	//获取用户名
    	if($isuid == 1){
    		$uid = $username;
	    	if($data = uc_get_user($uid, $isuid)) {
	    		$username = $data[1];
	    	} else {
	    		return -99;
	    	}
    	}
    	//修改密码
    	$ucresult = uc_user_edit($username, $oldpw, $newpw, $email);
    	return $ucresult;
    }

    public function showEditError($code){
    	switch ($code) {
    		case 1:  $error = '更新成功'; break;
    		case 0:  $error = '没有做任何修改'; break;
    		case -1:  $error = '旧密码不正确'; break;
    		case -4:  $error = 'Email 格式有误'; break;
    		case -5:  $error = 'Email 不允许注册'; break;
    		case -6:  $error = '该 Email 已经被注册'; break;
    		case -7:  $error = '没有做任何修改'; break;
    		case -8:  $error = '该用户受保护无权限更改'; break;
    		case -99:  $error = '用户不存在，或者被删除'; break;
    		default:  $error = '未知错误['.$code.']';
    	}
    	return $error;
    }
    
    //============================================ 下面是从 Common/Api/UserApi迁移过来的代码 ============================================
    /**
     * 检测用户是否登录
     * @return integer 0-未登录，大于0-当前登录用户ID
     */
    public static function is_login(){
    	$auth = session('user_auth');
    	if (empty($auth)) {
    		
    		$cookieval = cookie(C('USER_AUTH_KEY'));
    		$cookieval = auth_decode($cookieval);
    		if(empty($cookieval)){
    			return 0;
    		}
    		$arrayauth=explode("\t", $cookieval);
    		if(count($arrayauth)==4){
    			$isRemember = intval($arrayauth[0]);
    			if($isRemember === 1){
	    			$auth = array(
	    					'uid'             => intval($arrayauth[1]),
	    					'username'        => $arrayauth[2],
	    					'last_login_time' => intval($arrayauth[3]),
	    			);
	    			session('user_auth', $auth);
	    			return $auth['uid'];
    			}
    		}
    		return 0;
    	} else {
    		return $auth['uid'];
    	}
    }
    
    /**
     * 检测当前用户是否为管理员
     * @return boolean true-管理员，false-非管理员
     */
    public static function is_administrator($uid = null){
    	$uid = is_null($uid) ? is_login() : $uid;
    	return $uid && (intval($uid) === C('USER_ADMINISTRATOR'));
    }
    
    /**
     * 根据用户ID获取用户名
     * @param  integer $uid 用户ID
     * @return string       用户名
     */
    public static function get_username($uid = 0){
    	$User = new UserApi();
    	$info = $User->info($uid, 1);
    	return $info[1];
    }
    
    /**
     * 根据用户ID获取用户昵称
     * @param  integer $uid 用户ID
     * @return string       用户昵称
     */
    public static function get_nickname($uid = 0){
    	static $list;
    	if(!($uid && is_numeric($uid))){ //获取当前登录用户名
    		return session('user_auth.username');
    	}
    
    	/* 获取缓存数据 */
    	if(empty($list)){
    		$list = S('sys_user_nickname_list');
    	}
    
    	/* 查找用户信息 */
    	$key = "u{$uid}";
    	if(isset($list[$key])){ //已缓存，直接使用
    		$name = $list[$key];
    	} else { //调用接口获取用户信息
    		$info = M('Member')->field('nickname')->find($uid);
    		if($info !== false && $info['nickname'] ){
    			$nickname = $info['nickname'];
    			$name = $list[$key] = $nickname;
    			/* 缓存用户 */
    			$count = count($list);
    			$max   = C('USER_MAX_CACHE');
    			while ($count-- > $max) {
    				array_shift($list);
    			}
    			S('sys_user_nickname_list', $list);
    		} else {
    			$name = '';
    		}
    	}
    	return $name;
    }
}
