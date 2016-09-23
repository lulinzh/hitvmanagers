<?php

/**
 * 微信公众号 url通知，处理消息后响应
 *
 */
class WSQResponse {

	private static function _init() {
	
	}
	
	public static function text($param) {
		self::_init();
		echo WeChatServer::getXml4Txt("感谢您关注大力摇互动微信公众号！");
	}

	public static function click($param) {
		self::_init();
		echo 'success';
	}

	public static function subscribe($param) {
		self::_init();
		list($data) = $param;
		
		$wechatqrcode = D("WechatQrcode")->where(array('scene'=>$data['key']))->find();
		if(!$wechatqrcode){
			echo WeChatServer::getXml4Txt("二维码已过期，请重新操作！");
		}else{
			$upret = D("WechatQrcode")->where(array('scene'=>$data['key']))->setField(array('sts'=>1,'ststime'=>time(),'scanopenid'=>$data['from']));
			if($upret){
				echo WeChatServer::getXml4Txt(self::_get_action_msg($wechatqrcode['action']));
			}else{
				echo WeChatServer::getXml4Txt("很遗憾，您的操作失败，请重试！");
			}
		}
	}

	public static function scan($param) {
		self::_init();
		list($data) = $param;
		
		$wechatqrcode = D("WechatQrcode")->where(array('scene'=>$data['key']))->find();
		if(!$wechatqrcode){
			echo WeChatServer::getXml4Txt("二维码已过期，请重新操作！");
		}else{
			$upret = D("WechatQrcode")->where(array('scene'=>$data['key']))->setField(array('sts'=>1,'ststime'=>time(),'scanopenid'=>$data['from']));
			if($upret){
				echo WeChatServer::getXml4Txt(self::_get_action_msg($wechatqrcode['action']));
			}else{
				echo WeChatServer::getXml4Txt("很遗憾，您的操作失败，请重试！");
			}
		}
	}



	private static function _get_action_msg($action) {
		
		$msg='欢迎您关注大力摇互动微信公众号！';
		switch ($action) {
			case 'bind':
				$msg='大力摇互动温馨提示：恭喜您已成功绑定微信！';
				break;
				
			case 'unbind':
				$msg='大力摇互动温馨提示：您已成功解除绑定微信！';
				break;
				
			default:
				$msg='大力摇互动温馨提示：您已扫描二维码，请您在PC官网上继续操作！';
				break;
		}
		return $msg;
	}
	
}

//  普通文本
// 	<xml><ToUserName><![CDATA[gh_d7ccc2fac5a8]]></ToUserName>
// 	<FromUserName><![CDATA[of0N4joZZtU2SZyka8ep073RIovw]]></FromUserName>
// 	<CreateTime>1445402248</CreateTime>
// 	<MsgType><![CDATA[text]]></MsgType>
// 	<Content><![CDATA[浮云了]]></Content>
// 	<MsgId>6207955385124977975</MsgId>
// 	</xml>

//  关注
// 	<xml><ToUserName><![CDATA[gh_d7ccc2fac5a8]]></ToUserName>
// 	<FromUserName><![CDATA[of0N4joZZtU2SZyka8ep073RIovw]]></FromUserName>
// 	<CreateTime>1445408392</CreateTime>
// 	<MsgType><![CDATA[event]]></MsgType>
// 	<Event><![CDATA[subscribe]]></Event>
// 	<EventKey><![CDATA[qrscene_485924917]]></EventKey>
// 	<Ticket><![CDATA[gQFS8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL0UzV0xDeFRtei1fMlVDSFE0MXU3AAIEcC4nVgMECAcAAA==]]></Ticket>
// 	</xml>

//  关注后的扫码
// 	<xml><ToUserName><![CDATA[gh_d7ccc2fac5a8]]></ToUserName>
// 	<FromUserName><![CDATA[of0N4joZZtU2SZyka8ep073RIovw]]></FromUserName>
// 	<CreateTime>1445408404</CreateTime>
// 	<MsgType><![CDATA[event]]></MsgType>
// 	<Event><![CDATA[SCAN]]></Event>
// 	<EventKey><![CDATA[485924917]]></EventKey>
// 	<Ticket><![CDATA[gQFS8DoAAAAAAAAAASxodHRwOi8vd2VpeGluLnFxLmNvbS9xL0UzV0xDeFRtei1fMlVDSFE0MXU3AAIEcC4nVgMECAcAAA==]]></Ticket>
// 	</xml>

//  不再关注
// 	<xml><ToUserName><![CDATA[gh_d7ccc2fac5a8]]></ToUserName>
// 	<FromUserName><![CDATA[of0N4joZZtU2SZyka8ep073RIovw]]></FromUserName>
// 	<CreateTime>1445411554</CreateTime>
// 	<MsgType><![CDATA[event]]></MsgType>
// 	<Event><![CDATA[unsubscribe]]></Event>
// 	<EventKey><![CDATA[]]></EventKey>
// 	</xml>