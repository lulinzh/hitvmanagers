<?php
namespace Home\Controller;
use Home\Controller\Base\WxbaseController;

/**
 * 微信页面
 */
class WeixinController extends WxbaseController {
	
	
	protected function _initialize(){
		parent::_initialize();
		$this->check_login();	//检测登录
		//修改提示页配置
		C('TMPL_ACTION_ERROR', 'User:error');
		C('TMPL_ACTION_SUCCESS', 'User:success');
	}
	
	
	//测试  请分别用普通浏览器或微信打开，看看效果	/Weixin/test_autologin.html
	public function test_autologin(){
		$this->success("登录成功！", U('User/profile'));
	}
	
	
	public function test_dump(){
	
		echo '<!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml"><head><meta charset="utf-8"></head><body>';
		echo 'login success!<br/>';
		$member = D('Member')->find(UID);
		$this->assign('member', $member);
		dump($member);
		echo '</body></html>';
	}
	
	//测试
	public function test_wxshare(){
		$this->display();
	}
	
}
