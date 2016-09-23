<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */

/**
 * 检测验证码
 * @param  integer $id 验证码ID
 * @return boolean     检测结果
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function check_verify($code, $id = 1){
	$verify = new \Think\Verify();
	return $verify->check($code, $id);
}

/**
 * 获取列表总行数
 * @param  string  $category 分类ID
 * @param  integer $status   数据状态
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_list_count($category, $status = 1){
    static $count;
    if(!isset($count[$category])){
        $count[$category] = D('Document')->listCount($category, $status);
    }
    return $count[$category];
}

/**
 * 获取段落总数
 * @param  string $id 文档ID
 * @return integer    段落总数
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_part_count($id){
    static $count;
    if(!isset($count[$id])){
        $count[$id] = D('Document')->partCount($id);
    }
    return $count[$id];
}

/**
 * 获取导航URL
 * @param  string $url 导航URL
 * @return string      解析或的url
 * @author 麦当苗儿 <zuojiazi@vip.qq.com>
 */
function get_nav_url($url){
    switch ($url) {
        case 'http://' === substr($url, 0, 7):
        case '#' === substr($url, 0, 1):
            break;        
        default:
            $url = U($url);
            break;
    }
    return $url;
}


/**
 * 获取频道的子栏目        add by heqh
 * @param int $categoryid         当前的栏目id
 * @param 字段 $field  如果指定字段，只能是单个字段
 * @return list不指定字段时      string指定字段时
 */
function get_category_son($categoryid, $field = null){
	if( !is_numeric($categoryid) ){
		return false;
	}
	$category_top = null;
	if($categoryid==0){
		$category_top = array('id'=>$categoryid);
	}else{
		//递归，寻找一级频道id
		$category_top = array('pid'=>$categoryid);
		for($i=0; $i<10 && $category_top['pid']!=0; $i++){
			$category_top = get_category($category_top['pid']);
			if(!$category_top){
				return false;
			}
		}
	}
	$category_list = M('Category')->field($field)->where(array('status'=>1,'pid'=>$category_top['id']))->order('sort,id')->select();
	if(!$category_list){
		return false;
	}
	
	if( empty($field) ){
		return $category_list;
	} else if( strstr($field, ',') ){
		return $category_list;
	} else{
		$ret = '';
		foreach ($category_list as $v) {
			if($ret == ''){
				$ret = $ret . $v[$field];
			}else{
				$ret = $ret . ',' . $v[$field];
			}
		}
		return $ret;
	}
}


/**
 * 获取栏目位置        add by heqh
 * @param int $categoryid         当前的栏目id
 * @return list栏目层级列表，如：首页->新闻资讯->行业新闻
 */
function get_category_pos($categoryid){
	
	if( !is_numeric($categoryid) ){
		return false;
	}
	$list = array();
	if($categoryid == 0){
		return $list;
	}
	$category = get_category($categoryid);
	array_push($list, $category);
	
	//递归，寻找一级频道id
	for($i=0; $i<10 && $category['pid']!=0; $i++){
		$category = get_category($category['pid']);
		if(!$category){
			return false;
		}
		array_push($list, $category);
	}
	krsort($list);	//倒序排列
	/* 重新构造数组 */
	$ret = array();
	foreach ($list as $key => $value){
		array_push($ret, $value);
	}
	return $ret;
}









/**
 * 系统邮件发送函数
 * @param string $to    接收邮件者邮箱
 * @param string $name  接收邮件者名称
 * @param string $subject 邮件主题
 * @param string $body    邮件内容
 * @param string $attachment 附件列表
 * @return boolean
 */
function sp_sendEmail($uid, $to, $name, $subject = '', $body = '', $attachment = null, $param = null){

	$config = C('LXSYS_EMAIL');
	if(empty($config['SMTP_HOST'])){		//没有配置SMTP服务器，则直接返回发送成功

		$sendemaillog = D("SendEmailLog");
		$sendemaillog->create();
		$sendemaillog->userid = $uid;
		$sendemaillog->email = $to;
		$sendemaillog->subject = $subject;
		$sendemaillog->body = $body;
		$sendemaillog->ip=get_client_ip();
		$sendemaillog->ret='模拟发送成功';
		$sendemaillog->addtime = time();
		$sendemaillog->add();
		return array('code'=>0, 'info'=>'模拟发送成功');
	}

	//判断邮件发送的频率  略


	vendor('Util.class#phpmailer', BS_VENDOR_PATH); //导入class.phpmailer.php类文件
	$mail             = new PHPMailer(); //PHPMailer对象

	$mail->CharSet    = 'UTF-8'; //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
	$mail->IsSMTP();  // 设定使用SMTP服务
	$mail->SMTPDebug  = 0;                     // 关闭SMTP调试功能   // 1 = errors and messages   // 2 = messages only
	$mail->SMTPAuth   = true;                  // 启用 SMTP 验证功能
	//$mail->SMTPSecure = 'ssl';                 // 使用安全协议
	if($param){
		if(isset($param->CharSet)){
			$mail->CharSet = $param->CharSet;
		}
		if(isset($param->SMTPDebug)){
			$mail->SMTPDebug = $param->SMTPDebug;
		}
		if(isset($param->SMTPAuth)){
			$mail->SMTPAuth = $param->SMTPAuth;
		}
		if(isset($param->SMTPSecure)){
			$mail->SMTPSecure = $param->SMTPSecure;
		}
	}
	$mail->Host       = $config['SMTP_HOST'];  // SMTP 服务器
	$mail->Port       = $config['SMTP_PORT'];  // SMTP服务器的端口号
	$mail->Username   = $config['SMTP_USER'];  // SMTP服务器用户名
	$mail->Password   = $config['SMTP_PASS'];  // SMTP服务器密码
	$mail->SetFrom($config['FROM_EMAIL'], $config['FROM_NAME']);
	$replyEmail       = $config['REPLY_EMAIL']?$config['REPLY_EMAIL']:$config['FROM_EMAIL'];
	$replyName        = $config['REPLY_NAME']?$config['REPLY_NAME']:$config['FROM_NAME'];
	$mail->AddReplyTo($replyEmail, $replyName);
	$mail->Subject    = $subject;
	$mail->MsgHTML($body);
	$mail->AddAddress($to, $name);
	if(is_array($attachment)){ // 添加附件
		foreach ($attachment as $file){
			is_file($file) && $mail->AddAttachment($file);
		}
	}
	$sendret = $mail->Send();

	//保存邮件发送日志
	$sendemaillog = D("SendEmailLog");
	$sendemaillog->create();
	$sendemaillog->userid = $uid;
	$sendemaillog->email = $to;
	$sendemaillog->subject = $subject;
	$sendemaillog->body = $body;
	$sendemaillog->ip=get_client_ip();
	$sendemaillog->ret=''.$sendret;
	$sendemaillog->addtime = time();
	$sendemaillog->add();

	if($sendret == true)
		return array('code'=>0, 'info'=>'发送成功');
	else
		return array('code'=>-1, 'info'=>$mail->ErrorInfo);
}

/**
 * 系统短信发送函数
 * @return boolean
 */
function sp_sendSms($uid, $phone, $body, $vcode = ''){
	$config = C('LXSYS_SMSL');
	//限制发送
	$sendsmslog_model = D("SendSmsLog");
	$lastsendtime = $sendsmslog_model->where('(userid='.$uid.' or phone=\''.$phone.'\')')->order('addtime desc')->field('addtime')->limit(1)->find();
	if( time() - $lastsendtime['addtime'] < $config['SMS_MIN_SECONDS']){
		return array('code'=>91, 'info'=>'短信发送太频繁');
	}
	$sendcount = $sendsmslog_model->where('userid='.$uid.' and addtime>='.strtotime(date('Y-m-d 00:00:00', time())))->count();
	if($sendcount >= $config['SMS_ACCT_MAX']){
		return array('code'=>92, 'info'=>'帐号发送次数达到超过上限');
	}
	$sendcount = $sendsmslog_model->where('phone=\''.$phone.'\' and addtime>='.strtotime(date('Y-m-d 00:00:00', time())))->count();
	if($sendcount >= $config['SMS_PHONE_MAX']){
		return array('code'=>93, 'info'=>'号码发送次数达到超过上限');
	}
	$sendcount = $sendsmslog_model->where('ip=\''.get_client_ip().'\' and addtime>='.strtotime(date('Y-m-d 00:00:00', time())))->count();
	if($sendcount >= $config['SMS_IP_MAX']){
		return array('code'=>94, 'info'=>'IP发送次数达到超过上限');
	}

	$sendsmsurl = $config['SMS_INTF'];
	if(empty($sendsmsurl)){
		// 未配置短信网关
		$sendsmslog = D("SendSmsLog");
		$sendsmslog->create();
		$sendsmslog->userid = $uid;
		$sendsmslog->phone = $phone;
		$sendsmslog->body = $body;
		$sendsmslog->ip=get_client_ip();
		$sendsmslog->vcode=$vcode;
		$sendsmslog->ret='未配置短信网关';
		$sendsmslog->addtime = time();
		$retid = $sendsmslog->add();
		if($retid){
			return array('code'=>0, 'info'=>'模拟发送成功');
		}else{
			return array('code'=>-1, 'info'=>'模拟发送系统失败');
		}
	}
	$sendsmsurl=str_replace("{phone}", $phone, $sendsmsurl);
	$sendsmsurl=str_replace("{body}", urlencode($body), $sendsmsurl);

	$getret = do_http_get($sendsmsurl);		//调用发送短信接口

	//保存短信发送日志
	$sendsmslog = D("SendSmsLog");
	$sendsmslog->create();
	$sendsmslog->userid = $uid;
	$sendsmslog->phone = $phone;
	$sendsmslog->body = $body;
	$sendsmslog->ip=get_client_ip();
	$sendsmslog->vcode=$vcode;
	$sendsmslog->ret=$getret;
	$sendsmslog->addtime = time();
	$sendsmslog->add();

	return array('code'=>0, 'info'=>'发送成功');
}

/**
 * 获取并保存微信用户信息
 * @return 用户信息 ，失败返回null
 */
function sp_getSaveUpdateWechatUserinfo($openid){

	$configweixin = C('LXSYS_WEIXIN');
	load_weichat_lib();
	$wechat_client = new WeChatClient($configweixin['AppID'], $configweixin['AppSecret']);
	$wxuser = $wechat_client->getUserInfoById($openid);
	if(!$wxuser){
		return null;
	}

	$wxuser['nickname'] = get_emoji_str($wxuser['nickname']);		//把昵称的表情符号，替换成指定符号

	//微信用户信息记录入库
	$wechatuserinfo_model = D("WechatUserinfo");
	$wechatuserinfo = $wxuser;
	if($wechatuserinfo_model->where(array('openid'=>$openid))->find()){
		$wechatuserinfo['modifytime'] = time();
		unset($wechatuserinfo['openid']);
		$wechatuserinfo_model->where(array('openid'=>$openid))->setField($wechatuserinfo);
	}else{
		$wechatuserinfo['addtime'] = time();
		$wechatuserinfo_model->add($wechatuserinfo);
	}
	return $wxuser;
}


function sp_getSaveUpdateWechatUserinfoByAuth($access_token, $openid){
	
	$configweixin = C('LXSYS_WEIXIN');
	load_weichat_lib();
	$wechat_client = new WeChatClient($configweixin['AppID'], $configweixin['AppSecret']);
	$wxuser = $wechat_client->getUserInfoByAuth($access_token, $openid);
	if(!$wxuser || !is_array($wxuser)){
		return null;
	}

	$wxuser['nickname'] = get_emoji_str($wxuser['nickname']);		//把昵称的表情符号，替换成指定符号
	$wxuser['privilege'] = json_encode($wxuser['privilege']);
	
	//微信用户信息记录入库
	$wechatuserinfo_model = D("WechatUserinfo");
	$wechatuserinfo = $wxuser;
	if($wechatuserinfo_model->where(array('openid'=>$openid))->find()){
		$wechatuserinfo['modifytime'] = time();
		unset($wechatuserinfo['openid']);
		$wechatuserinfo_model->where(array('openid'=>$openid))->setField($wechatuserinfo);
	}else{
		$wechatuserinfo['addtime'] = time();
		$wechatuserinfo_model->add($wechatuserinfo);
	}
	return $wxuser;
}



/**
 * 获取并保存QQ用户信息
 * @return 用户信息 ，失败返回null
 */
function sp_getSaveUpdateQqUserinfo($qc, $token, $openid){
	$qquser = $qc->get_user_info();
	if(!$qquser){
		return null;
	}
	$qquser['nickname'] = get_emoji_str($qquser['nickname']);		//把昵称的表情符号，替换成指定符号

	//QQ用户信息记录入库
	$qquserinfo_model = D("QqUserinfo");
	$qquserinfo = $qquser;
	if($qquserinfo_model->where(array('openid'=>$openid))->find()){
		$qquserinfo['token'] = $token;
		$qquserinfo['modifytime'] = time();
		$qquserinfo_model->where(array('openid'=>$openid))->setField($qquserinfo);
	}else{
		$qquserinfo['openid'] = $openid;
		$qquserinfo['token'] = $token;
		$qquserinfo['addtime'] = time();
		$qquserinfo_model->add($qquserinfo);
	}

	return $qquser;
}

//微信分享接口  获取微信分享接口的签名结构体
function sp_getWeixinShareSign($url=''){

	$configweixin = C('LXSYS_WEIXIN');
	load_weichat_lib();
	$client = new \WeChatClient($configweixin['AppID'], $configweixin['AppSecret']);

	$signurl = empty($url) ? get_current_uri() : $url;
	$sign = $client->getSignPackage($signurl);
	return $sign;
}

