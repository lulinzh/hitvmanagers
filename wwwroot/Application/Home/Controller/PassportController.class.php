<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Home\Controller\Base\HomeController;
use User\Api\UserApi;

/**
 * 用户控制器
 * 包括用户中心，用户登录及注册
 */
class PassportController extends HomeController {
	
	
	//检测用户名是否被占用     true未被占用    false已被占用
	private function checkUserName($username) {
		$Api = new UserApi();
		return $Api->checkUsername($username) == 1;
	}
	
	//检测Email是否被占用     true未被占用    false已被占用
	private function checkEmail($email) {
		$membersafe = D("MemberSafe")->where(array('email'=>$email))->find();
		if($membersafe){
			return false;
		}
		$Api = new UserApi();
		return $Api->checkEmail($email) == 1;
	}
	

	/* 验证码，用于登录和注册 */
	public function verify(){
		$config =	array(
				'codeSet'   =>  '0123456789',             // 验证码字符集合
				'length'    =>  4,               // 验证码位数
		);
		$verify = new \Think\Verify($config);
		$verify->entry(1);
	}
    
    public function header(){
    	$this->display();
    }
    
    public function index(){
    	$this->display();
    }
    	
    public function regPhone(){
    	$this->display();
    }
    
    //提交：普通注册 (Email注册)
    public function doreg(){
    	C('HTML_CACHE_ON',false);
    	if(C('USER_ALLOW_REGISTER') != 1) {
    		$this->ajaxReturn(array('code'=>'001500', 'info'=>'当前禁止注册新用户!'));
    	}
    	//判断验证码
    	$validateCode = $_GET['validateCode'];
    	if( !check_verify($validateCode) ) {
    		$this->ajaxReturn(array('code'=>'001001'));
    	}
    	if($_GET['password'] != $_GET['password2']){
    		$this->ajaxReturn(array('code'=>'001190', 'info'=>'两次输入的密码不一致'));
    	}
    
    	$userName = $_GET['userName'];
    	$password = $_GET['password'];
    	$email = $_GET['email'];
    
    	if(!$this->checkEmail($email)){
    		$this->ajaxReturn(array('code'=>'001190', 'info'=>'邮箱['.$email.']已被注册'));
    	}
    
    	$regtype = 'normal';
    	//自动生成用户名
    	if(empty($userName)){
    		$regtype = 'email';
    		do {
    			$userName = 'email'.rand(10000000,19999999);		//随机用户名
    		} while ( !$this->checkUserName($userName) );
    
    	}
    
    	$nickname = '';
    	$phone = '';
    	$wxopenid = '';
    
    	$ret = $this->functionUserRegister($regtype, $userName, $password, $email, $nickname, $phone, $wxopenid);
    	$this->ajaxReturn($ret);
    }
    
    public function dologin() {
    	C('HTML_CACHE_ON',false);
    	
    	if(C('USER_ALLOW_LOGIN') != 1) {
    		$this->ajaxReturn(array('code'=>'001000', 'info'=>'当前禁止用户登录!'));
    	}
    	
    	$passwordlog_model = D("PasswordLog");
    	$member_model = D("Member");
    	$Api = new UserApi();
    
    	$userName = $_REQUEST["userName"];		//可能是邮箱/会员帐号/手机号
    	$password = $_REQUEST["password"];
    	$validateCode = $_REQUEST['validateCode'];
    	$autoLogin = intval($_REQUEST['autoLogin']);
    	 
    	//判断号码格式
    	if(preg_match(C('LXSYS_PREG_PHONE'),$userName)){		//电话号码登录
    		$membersafe = D("MemberSafe")->where(array('phone'=>$userName,'phonests'=>2))->find();
    	}else if(preg_match(C('LXSYS_PREG_EMAIL'),$userName)){
    		$membersafe = D("MemberSafe")->where(array('email'=>$userName))->find();
    	}
    	//--查找用户，确定是哪个用户登录
    	if($membersafe){
    		$userinfo = $Api->info($membersafe['id'], 1);
    	}else{
    		$userinfo = $Api->info($userName);
    	}
    	if(!$userinfo){
    		$this->ajaxReturn(array('code'=>'001000', 'info'=>'此帐号['.$userName.']不存在'));
    	}
    	if($userinfo[0] <= 0){
    		$this->ajaxReturn(array('code'=>'001000', 'info'=>'此帐号['.$userName.']不存在'));
    	}
    	$userinfo = array('id'=>$userinfo[0], 'username'=>$userinfo[1], 'nickname'=>$userinfo[1], 'email'=>$userinfo[2], 'last_login_time'=>0);
    	//查询用户昵称，登录时间等其它信息
    	$memberlocal=$member_model->find($userinfo['id']);
    	if($memberlocal){
    		$userinfo['nickname'] = $memberlocal['nickname'];
    		$userinfo['last_login_time'] = $memberlocal['last_login_time'];
    	}
    	
    	//--判断是否需要输入验证码
    	if(C('USER_LOGIN_NEED_VALIDATECODE') == 1){
    		if(!check_verify($validateCode)) {			//配置每次都要输入验证码
    			$this->ajaxReturn(array('code'=>'001001'));
    		}
    	}else{
    		$timeonedayago = time()-86400;		//24小时内
    		$pwdFaildLogCount = $passwordlog_model->where(array('uid'=>$userinfo['id'],'checkret'=>2,'addtime'=>array('gt',$timeonedayago)))->count();    		
    		// >100是防止有人穷举密码  24小时候没有登录成功过，并且输入密码次数>n次， 则需要输入验证码
    		if($pwdFaildLogCount > 100 || ($userinfo['last_login_time'] < $timeonedayago && $pwdFaildLogCount > intval(C('USER_LOGIN_NEED_VCODE_COUNT'))))
    		{
    			if(empty($validateCode)){
    				$this->ajaxReturn(array('code'=>'001002'));
    			}
    			if(!check_verify($validateCode)) {			//配置每次都要输入验证码
    				$this->ajaxReturn(array('code'=>'001001'));
    			}
    		}
    	}
    	
    	$uid = $Api->login($userinfo['username'], $password);		//此函数内部会调用用户中心接口
    	if(0 < $uid){ //UC登录成功
    		/* 登录本站用户 */
    		if($member_model->login($uid, '', $autoLogin)){ //登录用户
    			//登录成功
    			//$ucsynlogin = uc_user_synlogin($uid);	//ucenter异步登录脚本，这个脚本不在浏览器运行是没用的
    			echo '{"code":"000000","user":[{"userName":"'.$userinfo['username'].'","userId":"'.$userinfo['id'].'","nick":"'.$userinfo['nickname'].'"}]}';
    			exit;
    		} else {
    			$this->ajaxReturn(array('code'=>'001000', 'info'=>$member_model->getError()));
    		}
    	}else { //登录失败
    		//-- 记录密码日志
    		$passwordlog = array(
    				'uid' => $userinfo['id'],
    				'checkname' => $userinfo['username'],
    				'checkpassword' => $password,
    				'checkret' => ($uid > 0) ? 1 : 2,		//密码验证结果
    				'addtime' => time()
    		);
    		$passwordlog_model->add($passwordlog);
    		
    		$this->ajaxReturn(array('code'=>'001000', 'info'=>$Api->showLoginError($uid)));
    	}
    }
    
    
    /* 退出登录 */
    public function logout(){
    	C('HTML_CACHE_ON',false);
    	if(is_login()){
    		D('Member')->logout();
    	}
    	if($_REQUEST['type'] == 'redirect'){
    		redirect('/');
    	}
    	else{
    		$this->ajaxReturn(array('code'=>0, 'info'=>'退出成功'));
    	}
    }
    
    //QQ登录页面
    public function qqlogin(){
    	C('HTML_CACHE_ON',false);
    	if(is_login()){
    		echo '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
    		echo '<br/><br/><br/><br/><br/><br/><br/><br/><br/><center>您已登录，正在刷新网页！</center>';
    		echo '<script type="text/javascript">try {	document.domain="'.$_SERVER['HTTP_HOST'].'"; 	}catch(e){ }  setTimeout("top.location.reload();",500); </script>';
    		return;
    	}
    	vendor("QQConnect.API.qqConnectAPI", BS_VENDOR_PATH);
    	$qc = new \QC();
    	$qc->qq_login();		//跳转到QQ登录页面
    }
    
    //QQ登录回调，如果没有绑定QQ号码，则跳出自动注册页面
    //授权信息如：callback=EDDCDA98D62489700F80BEA75002D802 token=EDDCDA98D62489700F80BEA75002D802 openid=4C20BE938277346CA305D88FE18026F2
    //用户信息如：array(18) { ["ret"]=> int(0) ["msg"]=> string(0) "" ["is_lost"]=> int(0) ["nickname"]=> string(6) "远航" ["gender"]=> string(3) "男" ["province"]=> string(6) "广西" ["city"]=> string(6) "南宁" ["year"]=> string(4) "1984" ["figureurl"]=> string(73) "http://qzapp.qlogo.cn/qzapp/101271764/4C20BE938277346CA305D88FE18026F2/30" ["figureurl_1"]=> string(73) "http://qzapp.qlogo.cn/qzapp/101271764/4C20BE938277346CA305D88FE18026F2/50" ["figureurl_2"]=> string(74) "http://qzapp.qlogo.cn/qzapp/101271764/4C20BE938277346CA305D88FE18026F2/100" ["figureurl_qq_1"]=> string(69) "http://q.qlogo.cn/qqapp/101271764/4C20BE938277346CA305D88FE18026F2/40" ["figureurl_qq_2"]=> string(70) "http://q.qlogo.cn/qqapp/101271764/4C20BE938277346CA305D88FE18026F2/100" ["is_yellow_vip"]=> string(1) "0" ["vip"]=> string(1) "0" ["yellow_vip_level"]=> string(1) "0" ["level"]=> string(1) "0" ["is_yellow_year_vip"]=> string(1) "0" }
    public function qqlogincallback(){
    	C('HTML_CACHE_ON',false);
    
    	if(is_login()){
    		$this->assign('msg', '您已登录，正在刷新网页！');
    		$this->assign('script', 'setTimeout(function(){parent.location.reload();},500);');
    		//$this->display();
    		return;
    	}
    		
    	vendor("QQConnect.API.qqConnectAPI", BS_VENDOR_PATH);
    	$qc = new \QC();
    
    	$qqtoken = $qc->qq_callback();
    	$qqopenid = $qc->get_openid();
    	if(empty($qqtoken) || empty($qqopenid)){
    		$this->assign('msg', '检测不到您的QQ登录信息，请重新操作！');
    		$this->display();
    		return;
    	}
    
    	$qc = new \QC($qqtoken, $qqopenid);			//根据授权信息，拉取用户信息
    	$qquser = sp_getSaveUpdateQqUserinfo($qc, $qqtoken, $qqopenid);
    
    	//QQ登录成功了		查询是否已经注册
    	$membersafe = D("MemberSafe")->where(array('qqopenid'=>$qqopenid,'qqsts'=>1))->find();		//
    	if($membersafe){
    		//已经有帐号绑定此QQ，直接登录帐号
    		$member_model = D("Member");
    		/* 登录本站用户 */
    		if( !$member_model->login($membersafe['id']) ){ //登录用户
    			$this->assign('msg', $member_model->getError());
    			$this->display();
    			return;
    		}
    		$this->assign('msg', '登录成功，正在刷新');
    		$this->assign('script', 'setTimeout(function(){parent.location.reload();},100);');
    		$this->display();
    		return;
    	}else{
    		//提示用户，只要点一下就自动注册新帐号
    		//生成用户名，并检测是否重复
    		$userName = 'QQ'.random_string(6);		//随机用户名
    		do {
    			$userName = 'QQ'.random_string(6);		//随机用户名
    		} while ( !$this->checkUserName($userName) );
    			
    		$this->assign('userName', $userName);
    		$this->assign('qqtoken', $qqtoken);
    		$this->assign('qqopenid', $qqopenid);
    		$this->assign('qquser', $qquser);
    		$this->display();
    	}
    }
    
    //提交：QQ注册
    public function doregQQ(){
    	C('HTML_CACHE_ON',false);
    	if(C('USER_ALLOW_QQLOGIN') != 1) {
    		$this->ajaxReturn(array('code'=>'001500', 'info'=>'当前禁止注册新用户!'));
    	}
    	$qqtoken = $_GET['token'];
    	$qqopenid = $_GET['openid'];
    	$username = $_GET['userName'];
    	$password = $_GET['password'];
    	$nickname = $_GET['nickname'];
    
    	if(!$this->checkUserName($username)){
    		$this->ajaxReturn(array('code'=>'001000', 'info'=>'该用户名号已被注册'));
    	}
    
    	//校验QQ授权是否合法
    	vendor("QQConnect.API.qqConnectAPI", BS_VENDOR_PATH);
    	$qc = new \QC($qqtoken, $qqopenid);			//根据授权信息，拉取用户信息
    	$qquser = $qc->get_user_info();
    	if(!$qquser){
    		$this->ajaxReturn(array('code'=>'001000', 'info'=>'注册失败，请关闭窗口重新操作'));
    	}
    
    	//判断微信号是否重复注册
    	$membersafeold = D("MemberSafe")->where(array('qqopenid'=>$qqopenid,'qqsts'=>1))->find();
    	if($membersafeold){
    		$this->ajaxReturn(array('code'=>'001000', 'info'=>'该QQ号已经注册'));
    	}
    
    	//自动指定email
    	$email = $username.'@null.null';
    	//自动指定密码
    	if($password == 'meiyoumimadefaultpwd' || empty($password)){
    		$password = C('LXSYS_PREG_DEFAULT_PASSWORD');		//使用默认密码
    	}
    
    	$ret = $this->functionUserRegister('qq', $username, $password, $email, $nickname, '', '', $qqopenid);
    	$this->ajaxReturn($ret);
    }
    
    
    //微信登录、自动注册功能(弹出二维码小窗口页面)
    public function wxQrcode(){
    	C('HTML_CACHE_ON',false);
    		
    	$wechatqrcode_model = D("WechatQrcode");
    	//场景二维码 场景ID
    	$scene=0;
    	do{
    		$scene = rand(100000000,999999999);		//9位整数
    		$hadrecord = $wechatqrcode_model->where(array('scene'=>$scene))->find();
    	}while($hadrecord);
    
    	$configweixin = C('LXSYS_WEIXIN');
    	include_once './'.APP_PATH.'/Common/Lib/Weixin/WeChat.class.php';
    	$wechat_client = new \WeChatClient($configweixin['AppID'], $configweixin['AppSecret']);
    	$ticketjson = $wechat_client->getQrcodeTicket(array('scene_id' => $scene, 'expire' => 1800, 'ticketOnly' => '0'));
    	if(!$ticketjson){
    		$this->assign('msg', '微信公众号暂不能使用');
    		$this->display();
    		exit;
    	}
    
    	$wechatqrcode = array(
    			'uid'=>0,
    			'scene'=>$scene,
    			'sts'=>0,
    			'ststime'=>time(),
    			'ticket'=>$ticketjson['ticket'],
    			'expire_seconds'=>$ticketjson['expire_seconds'],
    			'url'=>$ticketjson['url'],
    			'action'=>'passport',
    			'addtime'=>time()
    	);
    	$addid = $wechatqrcode_model->add($wechatqrcode);
    	if(!$addid){
    		$this->assign('msg', '此功能暂不能使用');
    		$this->display();
    		exit;
    	}
    
    	$this->assign('scene', $scene);
    	$this->assign('ticket', $ticketjson['ticket']);
    	$this->assign('msg', empty($_REQUEST['msg']) ? '请关注本站微信公众号执行下一步操作' : $_REQUEST['msg']);
    	$this->display();
    }
    
    //绑定微信(弹出小窗口页面，扫码过程中扫描此请求)
    // url:"/index.php/Home/Passport/dologinByWx/wxscene/"
    // post: {scene:WxScan._scene, ticket:WxScan._ticket, msg:WxScan._msg}
    public function dologinByWx(){
    	C('HTML_CACHE_ON',false);
    
    	$scene = $_POST['scene'];
    	$ticket = $_POST['ticket'];
    	$msg = $_POST['msg'];
    
    	$wechatqrcode = D("WechatQrcode")->where(array('scene'=>$scene,'ticket'=>$ticket))->find();
    	if(!$wechatqrcode){
    		$this->ajaxReturn(array('code'=>-1, 'info'=>'二维码错误，请重新操作'));
    	}
    	if($wechatqrcode['sts'] != 1 || empty($wechatqrcode['scanopenid'])){
    		$this->ajaxReturn(array('code'=>1, 'info'=>$msg));
    	}
    	//扫码成功了
    	$membersafe = D("MemberSafe")->where(array('wxopenid'=>$wechatqrcode['scanopenid'],'wxsts'=>1))->find();
    	if(!$membersafe){
    		$this->ajaxReturn(array('code'=>2, 'info'=>$wechatqrcode['scanopenid']));		//未有帐号绑定此微信
    	}
    	
    	/* 登录本站用户 */
    	$member_model = D("Member");
    	if($member_model->login($membersafe['id'])){ //登录用户
    		$this->ajaxReturn(array('code'=>0, 'info'=>'微信登录成功'));
    	} else {
    		$this->ajaxReturn(array('code'=>-1, 'info'=>$member_model->getError()));
    	}
    }
    
    //微信自动注册页面
    //  /index.php/Home/Passport/regWx/scene/111013793/ticket/gQH97zoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL2huWElVZUhtanYtM2FyU0lvRnU3AAIEBQYqVgMECAcAAA%3D%3D/openid/of0N4joZZtU2SZyka8ep073RIovw
    public function regWx(){
    	C('HTML_CACHE_ON',false);
    
    	$scene = $_GET['scene'];
    	$ticket = $_GET['ticket'];
    	$openid = $_GET['openid'];			//如 of0N4joZZtU2SZyka8ep073RIovw
    
    	$wechatqrcode = D("WechatQrcode")->where(array('scene'=>$scene,'ticket'=>$ticket))->find();
    	if(!$wechatqrcode){
    		die('系统暂时罢工，请重新操作！');		//传入参数不符合逻辑
    	}
    	if($wechatqrcode['sts'] != 1 || empty($wechatqrcode['scanopenid']) || $wechatqrcode['scanopenid'] != $openid){
    		die('系统暂时罢工，请重新操作！');		//传入参数不符合逻辑
    	}
    
    	$wxuser = sp_getSaveUpdateWechatUserinfo($openid);
    	if(!$wxuser){
    		die('获取微信用户信息失败，请重新操作！');
    	}
    	$this->assign('wxuser', $wxuser);
    	// 		echo '<!--';
    	// 		dump($wxuser);
    	// 		echo '-->';
    	$this->display();
    }
    
    //提交：微信注册
    public function doregWx(){
    	C('HTML_CACHE_ON',false);
    	if(C('USER_ALLOW_WXLOGIN') != 1) {
    		$this->ajaxReturn(array('code'=>'001500', 'info'=>'当前禁止注册新用户!'));
    	}
    
    	$scene = $_GET['scene'];
    	$ticket = $_GET['ticket'];
    	$openid = $_GET['openid'];
    	$userName = $_GET['userName'];
    	$password = $_GET['password'];
    	$nickname = $_GET['nickname'];
    
    	$wechatqrcode = D("WechatQrcode")->where(array('scene'=>$scene,'ticket'=>$ticket))->find();
    	if(!$wechatqrcode){
    		$this->ajaxReturn(array('code'=>'001000', 'info'=>'注册失败，请关闭窗口重新操作'));
    	}
    	if($wechatqrcode['sts'] != 1 || empty($wechatqrcode['scanopenid']) || $wechatqrcode['scanopenid'] != $openid){
    		$this->ajaxReturn(array('code'=>'001000', 'info'=>'注册失败，请关闭窗口重新操作'));
    	}
    
    	//判断微信号是否重复注册
    	$membersafeold = D("MemberSafe")->where(array('wxopenid'=>$openid,'wxsts'=>1))->find();
    	if($membersafeold){
    		$this->ajaxReturn(array('code'=>'001000', 'info'=>'该微信号已经注册'));
    	}
    
    	//自动指定email
    	$email = $userName.'@null.null';
    	//自动指定密码
    	if($password == 'meiyoumimadefaultpwd' || empty($password)){
    		$password = C('LXSYS_PREG_DEFAULT_PASSWORD');		//使用默认密码
    	}
    
    	$ret = $this->functionUserRegister('weixin', $userName, $password, $email, $nickname, '', $openid);
    	$this->ajaxReturn($ret);
    }
    
    
    //用户注册函数
    private function functionUserRegister($regtype, $username, $password, $email, $nickname, $phone='', $wxopenid='', $qqopenid=''){
    
    	if(empty($regtype)){
    		$regtype = 'normal';		//默认注册方式
    	}
    	//用户中心注册
    	$Api = new UserApi();
    	$ucuid = $Api->register($username, $password, $email, $phone);
    	if($ucuid <= 0) {
    		$this->ajaxReturn(array('code'=>'001190', 'info'=>$Api->showRegError($ucuid)));
    	}
    	else {    
    		$member_model=D("Member");
    		/* 登录本站用户 */
    		if( !$member_model->login($ucuid,$nickname) ){ //登录用户
    			$Api->delete($ucuid);		//删除之前注册的用户数据
    			$this->ajaxReturn(array('code'=>'001190', 'info'=>$member_model->getError()));
    		}
    
    		//发送验证邮件
    		$emailvcode=0;
    		$emailsts=0;
    		if($regtype=='email'){
    			$emailvcode = rand(100000000, 999999999);		//验证码随机生成
    			$emailurl = 'http://' . $_SERVER['HTTP_HOST'] . '/index.php/Home/User/safe_emailCheckCallBack/id/'.$ucuid.'/vcode/'.$emailvcode.'/t/'.time();
    				
    			$subject = C('USER_EMAIL_SUBJECT');		//邮件主题
    			$body = C('USER_EMAIL_BODY');	//邮件内容
    			$body=str_replace("{username}",$nickname,$body);
    			$body=str_replace("{hrefurl}",$emailurl,$body);
    			$body=str_replace("{copyurl}",$emailurl,$body);
    				
    			$ret = sp_sendEmail($ucuid, $email, $nickname, $subject, $body);
    			if($ret['code'] == 0){
    				$emailsts=1;
    			}
    		}
    		//插入安全中心记录
    		$membersafe = array(
    				'id'=>$ucuid,
    				'regtype'=>$regtype,
    				'email'=>$email,
    				'emailvcode'=> $emailvcode,
    				'emailsts'=> $emailsts,
    				'emailststime'=> $emailsts==0 ? 0 : time(),
    				'phone'=>$phone,
    				'phonests'=> empty($phone) ? 0 : 2,
    				'phoneststime'=> empty($phone) ? 0 : time(),
    				'wxopenid'=>$wxopenid,
    				'wxsts'=> empty($wxopenid) ? 0 : 1,
    				'wxststime'=> empty($wxopenid) ? 0 : time(),
    				'qqopenid'=>$qqopenid,
    				'qqsts'=> empty($qqopenid) ? 0 : 1,
    				'qqststime'=> empty($qqopenid) ? 0 : time()
    		);
    		$membersafeid = D("MemberSafe")->add($membersafe);
    		if(!$membersafeid){
    			$member_model->delete($ucuid);	//删除之前注册的用户数据
    			$Api->delete($ucuid);		//删除之前注册的用户数据
    			$this->ajaxReturn(array('code'=>'001190', 'info'=>'系统失败，请重新操作'));
    		}
    		//注册成功后同步用户头像
    		if( !empty($wxopenid) ){
    			$wechatuserinfo = D("WechatUserinfo")->where(array('openid'=>$wxopenid))->find();
    			if($wechatuserinfo){
    				\Common\Lib\Util\UcAvatar::upload($ucuid, $wechatuserinfo['headimgurl']);
    			}
    		}
    		return array('code'=>'000000', 'info'=>'注册成功', 'user'=>array('userName'=>$username, 'userId'=>$ucuid));
    	}
    }
    
    
    
    
    
    
    
}
