<?php
namespace Home\Controller;
use Home\Controller\Base\HomeController;

/**
 * 接口页面
 */
class IntfController extends HomeController {
	
	
	//测试
	public function test(){
		
		echo 'test';
	}
	
	//清空所有的cookie和session
	public function clean(){
		
		session('user_auth', null);		
		cookie(null);

		$this->ajaxReturn(array('code'=>0,'msg'=>'clean success!'));
	}
	
	//接口列表
	public function index(){
		echo 'test qrcode wxnotify';
	}
	
	//二维码
	public function qrcode(){
	C('HTML_CACHE_ON',false);

	vendor('phpqrcode.phpqrcode', BS_VENDOR_PATH);
	$data = $_REQUEST['data'];
	$level = I('level','L');	// 纠错级别：L、M、Q、H
	$size = I('size',10);		// 点的大小：1到10,用于手机端4就可以了
	$margin = I('margin', 4);		// 边距

	$QRcode = new \QRcode();
	$QRcode::png($data, false, $level, $size, $margin);
}
	
	
	//微信通知     微信配置的URL：   http://域名/index.php/Home/Intf/wxnotify
	//   /index.php/Home/Intf/wxnotify?signature=9d78912406dc9c1693fb160c0dff5b0316cccd15&timestamp=1445331100&nonce=1885892517
	//   /index.php/Home/Intf/wxnotify?signature=9d78912406dc9c1693fb160c0dff5b0316cccd15&timestamp=1445331100&nonce=1885892517&echostr=123456789876543
	public function wxnotify(){
	
		$configweixin = C('LXSYS_WEIXIN');
	
		//记录响应日志
		if( !empty($configweixin['NotifyLogFile']) ){
			$content = file_get_contents ( 'php://input' );
			if( empty ($content) ){
				$content = 'xml is empty!';
			}
			$fp = fopen($configweixin['NotifyLogFile'].'.'.date("ymd").'.log', 'a');
			fwrite($fp, $content."\n");
			fclose($fp);
		}
	
		load_weichat_lib();
	
		$hooks = unserialize($configweixin['ResponseHooks']);
		$server = new \WeChatServer($configweixin['Token'], $hooks);
	}
	
	//微信接口授权，统一出口点  模仿： https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=APPID&secret=APPSECRET
	//调用示例：/Intf/wxtoken.html?grant_type=client_credential&appid=APPID&secret=APPSECRET
	public function wxtoken(){
		$configweixin = C('LXSYS_WEIXIN');
		if(I('appid') != $configweixin['AppID'] || I('secret') != $configweixin['AppSecret']){
			$this->ajaxReturn(array('errcode'=>40013,'errmsg'=>'invalid appid'));
		}
		load_weichat_lib();
		$client = new \WeChatClient($configweixin['AppID'], $configweixin['AppSecret']);
		$access_token = $client->getAccessToken();
		$this->ajaxReturn(array('access_token' => $access_token,'expires_in' => 7200));
	}
	
	//微信网页授权统一接口：
	//微信网页授权获取用户基本信息   参考 http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html
	//入参scope，授权类型包括  snsapi_base(默认)  和 snsapi_userinfo
	//调用实例1：  /Intf/wxoauth2.html?url=urlencode(回调连接)          等价于     /Intf/wxoauth2/scope/snsapi_base.html?url=urlencode(回调连接)
	//调用实例2：  /Intf/wxoauth2/scope/snsapi_userinfo.html?url=urlencode(回调连接)
	public function wxoauth2(){
		//判断请求
		if(!is_weixin()){
			$this->error('请用微信访问', __ROOT__.'/');
		}
		//判断入参
		$scope = I('get.scope', 'snsapi_base');			//授权类型  snsapi_base  和 snsapi_userinfo
		$url = $_REQUEST['url'];
		if ( empty($url) ) {
			die ('no param: url');
		}
		
		//第0步 判读是否获取过openid
		if($scope === 'snsapi_userinfo') {
			if( null != cookie('openid') && null != cookie('nickname') && null != cookie('headimgurl') ) {
				$urlparams = 'openid='. cookie('openid').'&nickname='. urlencode(cookie('nickname')).'&sex='. cookie('sex').'&province='. urlencode(cookie('province')).'&city='. urlencode(cookie('city')).'&country='. urlencode(cookie('country')).'&headimgurl='. urlencode(cookie('headimgurl')).'&privilege=&unionid='. cookie('unionid');
				header('Location:'.get_url_concat($url,$urlparams));
				return;
			}
		}else{
			if( null != cookie('openid') ) {
				$urlparams = 'openid='. cookie('openid');
				header('Location:'.get_url_concat($url,$urlparams));
				return;
			}
		}
		
		$configweixin = C('LXSYS_WEIXIN');
		if ( empty($_REQUEST['code'])) {
			//第一步用户授权，获取code
			$redirect_uri = get_url_concat(get_full_php_self(), 'url='.urlencode($url));		//拼接url
			header('Location:https://open.weixin.qq.com/connect/oauth2/authorize?appid='.$configweixin['AppID'].'&redirect_uri='.urlencode($redirect_uri).'&response_type=code&scope='.$scope.'&state=1#wechat_redirect');
		} else {
			//第二步 通过code获取openid和其它信息
			$wx_intf = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $configweixin['AppID'] . '&secret=' . $configweixin['AppSecret'] . '&code='.$_REQUEST['code'].'&grant_type=authorization_code';
			$wx_json = do_http_get($wx_intf);
			//dump($wx_intf);  dump($wx_json);  exit;
			if(empty($wx_json)){
				die ('weixin oauth2 warn!');
			}
			$wx_json = json_decode($wx_json, true);
			$cookie_exp_time = time()+30*24*3600;		//一个月
			if($wx_json['scope'] === 'snsapi_userinfo') {
				$wx_intf = 'https://api.weixin.qq.com/sns/userinfo?access_token=' . $wx_json['access_token'] . '&openid=' . $wx_json['openid'] . '&lang=zh_CN';
				$u = do_http_get($wx_intf);
				$u = json_decode($u, true);
				if($wx_json['openid'] != $u['openid']){
					die ('weixin oauth2 userinfo warn!');
				}
				$nickname = get_emoji_str($u['nickname']); 				
				cookie('openid',$u['openid'],$cookie_exp_time);
				cookie('nickname',$nickname,$cookie_exp_time);
				cookie('sex',$u['sex'],$cookie_exp_time);
				cookie('province',$u['province'],$cookie_exp_time);
				cookie('city',$u['city'],$cookie_exp_time);
				cookie('country',$u['country'],$cookie_exp_time);
				cookie('headimgurl',$u['headimgurl'],$cookie_exp_time);
				cookie('privilege',$u['privilege'],$cookie_exp_time);
				cookie('unionid',$u['unionid'],$cookie_exp_time);
				$urlparams = 'openid='. $u['openid'].'&nickname='. urlencode($nickname).'&sex='. $u['sex'].'&province='. urlencode($u['province']).'&city='. urlencode($u['city']).'&country='. urlencode($u['country']).'&headimgurl='. urlencode($u['headimgurl']).'&privilege=&unionid='. $u['unionid'];
			} else {
				cookie('openid', $wx_json['openid'], $cookie_exp_time);
				$urlparams = 'openid='. $wx_json['openid'];
			}
			header('Location:'.get_url_concat($url,$urlparams));
		}
	}
	//单元测试， 测试如：/Intf/wxoauth2_test/clean/clean.html     /Intf/wxoauth2_test.html   /Intf/wxoauth2_test/scope/snsapi_userinfo.html
	public function test_wxoauth2(){

		$openid = $_GET['openid'];
		if(!$openid){
			$current_url = get_current_uri();
			$auth_url = U('wxoauth2', array('scope'=>I('scope')));	// 授权类型  snsapi_base(默认)  和 snsapi_userinfo
			header('Location:'.get_url_concat($auth_url, 'url='.urlencode($current_url)));
		}
		else{
			echo '222<br/>';
			echo 'openid:'.$openid;
			echo '<br />';
			echo 'nickname:'.$_GET['nickname'];
			echo '<br />';
			echo 'headimgurl:'.$_GET['headimgurl'].'<br/><img src="'.$_GET['headimgurl'].'" width="120" height="120" />';
			echo '<br />';
			echo 'sex:'.$_GET['sex'];
			echo '<br />';
			echo 'country:'.$_GET['country'];
			echo '<br />';
			echo 'province:'.$_GET['province'];
			echo '<br />';
			echo 'city:'.$_GET['city'];
			echo '<br />';
			echo 'privilege:'.$_GET['privilege'];
			echo '<br />';
			echo 'unionid:'.$_GET['unionid'];
			echo '<br />';
			$wxcookies = 'openid='. cookie('openid').'&nickname='. urlencode(cookie('nickname')).'&sex='. cookie('sex').'&province='. urlencode(cookie('province')).'&city='. urlencode(cookie('city')).'&country='. urlencode(cookie('country')).'&headimgurl='. urlencode(cookie('headimgurl')).'&privilege=&unionid='. cookie('unionid');
			echo 'cookie: '.$wxcookies;
		}
	}
		
	//微信分享接口
	public function wxshare(){
		$url = empty($_REQUEST['url']) ? get_current_uri() : $_REQUEST['url'];
		$sign = sp_getWeixinShareSign($url);
		$this->ajaxReturn($sign);
	}
	
	//测试
	public function test_wxshare(){
		$this->display();
	}
}
