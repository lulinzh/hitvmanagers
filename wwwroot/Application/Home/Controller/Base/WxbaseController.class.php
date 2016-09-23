<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller\Base;
use User\Api\UserApi;

/**
 * 前台公共控制器    微信页面
 */
class WxbaseController extends HomeController {

    protected function _initialize(){
    	parent::_initialize();
        //实现微信用户自动注册、登录
        if( !is_login() && is_weixin()){
        	$this->weixin_auto();
        }
    }

	//微信用户自动注册和登录
	private function weixin_auto(){
		$configweixin = C('LXSYS_WEIXIN');
		if ( empty($_REQUEST['code'])) {
			//第一步用户授权，获取code
			$scope = 'snsapi_userinfo';
			$redirect_uri = get_current_uri();		//拼接url
			header('Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$configweixin['AppID'].'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope='.$scope.'&state=1#wechat_redirect');
		} else {
			//第二步 通过code获取openid和其它信息
			$wx_intf = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $configweixin['AppID'] . '&secret=' . $configweixin['AppSecret'] . '&code='.$_REQUEST['code'].'&grant_type=authorization_code';
			$wx_json = do_http_get($wx_intf);
			if(empty($wx_json)){
				die ('weixin oauth2 warn!');
			}
			$wx_json = json_decode($wx_json, true);
			$wx_user = sp_getSaveUpdateWechatUserinfoByAuth($wx_json['access_token'], $wx_json['openid']);
			if($wx_json['openid'] != $wx_user['openid']){
				die ('weixin oauth2 userinfo warn!');
			}

			//主动注册并登录
			$membersafe = D("MemberSafe")->where(array('wxopenid'=>$wx_user['openid'],'wxsts'=>1))->find();
			if($membersafe){
				//登录本站用户 
				$member_model = D("Member");
				if( $member_model->login($membersafe['id'],$wx_user['nickname'],1) ){ //登录用户，用cookie记住自动登录信息
					return;	//登录成功
				} else {
					\Think\Log::record('微信访问自动登录失败 openid='.$wx_user['openid']);
					return;
				}
			}else{
				//注册并登录本站用户
				load_ucenter();
				$username = 'weixin'.random_string(8, array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'));
				$Api = new UserApi();
				do{
					$username = 'weixin'.random_string(8, array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9'));
				}while($Api->checkUsername($username) != 1);
				$email = $username.'@null.null';
				
				$regret = $this->weixin_auto_reg($username, $password, $email, $wx_user['nickname'],$wx_user['openid']);
				if($regret['code'] !== '000000'){
					\Think\Log::record('微信访问自动注册失败 openid='.$wx_user['openid']);
					return;
				}
				//注册成功后同步用户头像
				\Common\Lib\Util\UcAvatar::upload($regret['ucuid'], $wx_user['headimgurl']);
			}
		}
	}
	
	
	//用户注册函数
	private function weixin_auto_reg($username, $password, $email, $nickname, $wxopenid=''){
	
		$regtype = 'weixin';
		$phone = '';
		$$qqopenid = '';

		//用户中心注册
		$Api = new UserApi();
		$ucuid = $Api->register($username, $password, $email, $phone);
		if($ucuid <= 0) {
			\Think\Log::record('微信自动注册失败 openid='.$wxopenid);
			return array('code'=>'001190', 'info'=>$Api->showRegError($ucuid));
		}
		else {
			$member_model=D("Member");
			/* 登录本站用户 */
			if( !$member_model->login($ucuid,$nickname,1) ){ //登录用户
				$Api->delete($ucuid);		//删除之前注册的用户数据
				return array('code'=>'001190', 'info'=>$member_model->getError());
			}
			//插入安全中心记录
			$membersafe = array(
					'id'=>$ucuid,
					'regtype'=>$regtype,
					'email'=>$email,
    				'emailsts'=> 0,
					'phone'=>$phone,
					'phonests'=> empty($phone) ? 0 : 2,
					'phoneststime'=> empty($phone) ? 0 : time(),
					'wxopenid'=>$wxopenid,
					'wxsts'=> empty($wxopenid) ? 0 : 1,
					'wxststime'=> empty($wxopenid) ? 0 : time()
			);
			$membersafeid = D("MemberSafe")->add($membersafe);
			if(!$membersafeid){
				$member_model->delete($ucuid);	//删除之前注册的用户数据
				$Api->delete($ucuid);		//删除之前注册的用户数据
				\Think\Log::record('微信自动注册失败，插入绑定表失败 openid='.$wxopenid);
				return array('code'=>'001190', 'info'=>'系统失败，请重新操作');
			}
			return array('code'=>'000000', 'info'=>'注册成功', 'ucuid'=>$ucuid);
		}
	}
}
