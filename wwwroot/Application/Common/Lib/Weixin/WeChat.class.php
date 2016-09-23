<?php
defined('CHARSET') or define('CHARSET', 'UTF-8');

class WeChatServer {

	private $_token;

	private $_hooks;
	private $_classes;

	public function __construct($token, $hooks = array()) {
		$this->_token = $token;
		$this->_hooks = $hooks;
		$this->accessDataPush();
	}

	private function _activeHook($type) {
		if (!isset($this->_hooks[$type])) {
			return null;
		}
		$hook = & $this->_hooks[$type];
		if (!preg_match("/^[\w\_]+$/i", $hook['plugin']) || !preg_match('/^[\w\_\.]+\.php$/i', $hook['include'])) {
			return null;
		}
		//include_once 'Lib/Weixin/' . $hook['plugin'] . '/' . $hook['include'];
		include_once './'.APP_PATH.'/Common/Lib/Weixin/' . $hook['plugin'] . '/' . $hook['include'];
		
		if (!class_exists($hook['class'], false)) {
			return null;
		}
		if (!isset($this->classes[$hook['class']])) {
			$this->classes[$hook['class']] = new $hook['class'];
		}
		if (!method_exists($this->classes[$hook['class']], $hook['method'])) {
			return null;
		}
		$param = func_get_args();
		array_shift($param);
		return $this->classes[$hook['class']]->$hook['method']($param);
	}

	private function _checkSignature() {
		$signature = $_GET["signature"];
		$timestamp = $_GET["timestamp"];
		$nonce = $_GET["nonce"];

		$token = $this->_token;
		$tmpArr = array($token, $timestamp, $nonce);
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode($tmpArr);
		$tmpStr = sha1($tmpStr);

		return $tmpStr == $signature;
	}

	private function _handlePostObj($postObj) {
		$MsgType = strtolower((string) $postObj->MsgType);
		$result = array(
				'from' => self::$_from_id = (string) htmlspecialchars($postObj->FromUserName),
				'to' => self::$_my_id = (string) htmlspecialchars($postObj->ToUserName),
				'time' => (int) $postObj->CreateTime,
				'type' => (string) $MsgType
		);

		if (property_exists($postObj, 'MsgId')) {
			$result['id'] = $postObj->MsgId;
		}

		switch ($result['type']) {
			case 'text':
				$result['content'] = (string) $postObj->Content; // Content ��Ϣ����
				break;

			case 'location':
				$result['X'] = (float) $postObj->Location_X; // Location_X ����λ��γ��
				$result['Y'] = (float) $postObj->Location_Y; // Location_Y ����λ�þ���
				$result['S'] = (float) $postObj->Scale;      // Scale ��ͼ���Ŵ�С
				$result['I'] = (string) $postObj->Label;     // Label ����λ����Ϣ
				break;

			case 'image':
				$result['url'] = (string) $postObj->PicUrl;  // PicUrl ͼƬ���ӣ������߿�����HTTP GET��ȡ
				$result['mid'] = (string) $postObj->MediaId; // MediaId ͼƬ��Ϣý��id�����Ե��ö�ý���ļ����ؽӿ���ȡ���ݡ�
				break;

			case 'video':
				$result['mid'] = (string) $postObj->MediaId;      // MediaId ͼƬ��Ϣý��id�����Ե��ö�ý���ļ����ؽӿ���ȡ���ݡ�
				$result['thumbmid'] = (string) $postObj->ThumbMediaId; // ThumbMediaId ��Ƶ��Ϣ����ͼ��ý��id�����Ե��ö�ý���ļ����ؽӿ���ȡ���ݡ�
				break;

			case 'link':
				$result['title'] = (string) $postObj->Title;
				$result['desc'] = (string) $postObj->Description;
				$result['url'] = (string) $postObj->Url;
				break;

			case 'voice':
				$result['mid'] = (string) $postObj->MediaId;
				$result['format'] = (string) $postObj->Format;
				if (property_exists($postObj, Recognition)) {
					$result['txt'] = (string) $postObj->Recognition;
				}
				break;

			case 'event':
				$result['event'] = strtolower((string) $postObj->Event);
				switch ($result['event']) {

					case 'subscribe':
					case 'scan':
						if (property_exists($postObj, EventKey)) {
							$result['key'] = str_replace(
									'qrscene_', '', (string) $postObj->EventKey			//关注扫码特征前缀去掉
							);
							$result['ticket'] = (string) $postObj->Ticket;
						}
						break;

					case 'location':
						$result['la'] = (string) $postObj->Latitude;
						$result['lo'] = (string) $postObj->Longitude;
						$result['p'] = (string) $postObj->Precision;
						break;

					case 'click':
						$result['key'] = (string) $postObj->EventKey;
						break;
						
					case 'masssendjobfinish':
						$result['msg_id'] = (string) $postObj->MsgID;
						$result['status'] = (string) $postObj->Status;
						$result['totalcount'] = (string) $postObj->TotalCount;
						$result['filtercount'] = (string) $postObj->FilterCount;
						$result['sentcount'] = (string) $postObj->SentCount;
						$result['errorcount'] = (string) $postObj->ErrorCount;
						break;
						
				}
		}

		return $result;
	}

	private function accessDataPush() {
		if (!$this->_checkSignature()) {
			if (!headers_sent()) {
				header('HTTP/1.1 404 Not Found');
				header('Status: 404 Not Found');
			}
			//$this->_activeHook('404');
			echo '404';		//签名错误
			return;
		}

		$postdata = file_get_contents("php://input");
		if ($postdata) {
			if (!$this->_checkSignature()) {
				return;
			}
			$postObj = simplexml_load_string($postdata, 'SimpleXMLElement', LIBXML_NOCDATA);
			$postObj = $this->_handlePostObj($postObj);

			$this->_activeHook('receiveAllStart', $postObj);

			if (isset($postObj['event'])) {
				$hookName = 'receiveEvent::' . $postObj['event'];
			} else {
				$hookName = 'receiveMsg::' . $postObj['type'];
			}
			$this->_activeHook($hookName, $postObj);

			$this->_activeHook('receiveAllEnd', $postObj);
		} elseif (isset($_GET['echostr'])) {

			$this->_activeHook('accessCheckSuccess');
			if (!headers_sent()) {
				header('Content-Type: text/plain');
			}
			echo preg_replace('/[^a-z0-9]/i', '', $_GET['echostr']);
		}
	}

	private static $_from_id;
	private static $_my_id;

	private static function _format2xml($nodes) {
		$xml = '<xml>'
				. '<ToUserName><![CDATA[%s]]></ToUserName>'
						. '<FromUserName><![CDATA[%s]]></FromUserName>'
								. '<CreateTime>%s</CreateTime>'
										. '%s'
												. '</xml>';
		$return = sprintf(
				$xml, self::$_from_id, self::$_my_id, time(), $nodes
		);
		return diconv($return, CHARSET, 'UTF-8');
	}

	public static function getXml4Txt($txt) {
		$xml = '<MsgType><![CDATA[text]]></MsgType>'
				. '<Content><![CDATA[%s]]></Content>';
		return self::_format2xml(
				sprintf(
						$xml, $txt
				)
		);
	}

	public static function getXml4ImgByMid($mid) {
		$xml = '<MsgType><![CDATA[image]]></MsgType>'
				. '<Image>'
						. '<MediaId><![CDATA[%s]]></MediaId>'
								. '</Image>';
		return self::_format2xml(
				sprintf(
						$xml, $mid
				)
		);
	}

	public static function getXml4VoiceByMid($mid) {
		$xml = '<MsgType><![CDATA[voice]]></MsgType>'
				. '<Voice>'
						. '<MediaId><![CDATA[%s]]></MediaId>'
								. '</Voice>';
		return self::_format2xml(
				sprintf(
						$xml, $mid
				)
		);
	}

	public static function getXml4VideoByMid($mid, $title, $desc = '') {
		$desc = '' !== $desc ? $desc : $title;
		$xml = '<MsgType><![CDATA[video]]></MsgType>'
				. '<Video>'
						. '<MediaId><![CDATA[%s]]></MediaId>'
								. '<Title><![CDATA[%s]]></Title>'
										. '<Description><![CDATA[%s]]></Description>'
												. '</Video>';

		return self::_format2xml(
				sprintf(
						$xml, $mid, $title, $desc
				)
		);
	}

	public static function getXml4MusicByUrl($url, $thumbmid, $title, $desc = '', $hqurl = '') {
		$xml = '<MsgType><![CDATA[music]]></MsgType>'
				. '<Music>'
						. '<Title><![CDATA[%s]]></Title>'
								. '<Description><![CDATA[%s]]></Description>'
										. '<MusicUrl><![CDATA[%s]]></MusicUrl>'
												. '<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>'
														. '<ThumbMediaId><![CDATA[%s]]></ThumbMediaId>'
																. '</Music>';

		return self::_format2xml(
				sprintf(
						$xml, $title, '' === $desc ? $title : $desc, $url, $hqurl ? $hqurl : $url, $thumbmid
				)
		);
	}

	public static function getXml4RichMsgByArray($list) {
		$max = 10;
		$i = 0;
		$ii = count($list);
		$list_xml = '';
		while ($i < $ii && $i < $max) {
			$item = $list[$i++];
			$list_xml .=
			sprintf(
					'<item>'
					. '<Title><![CDATA[%s]]></Title> '
					. '<Description><![CDATA[%s]]></Description>'
					. '<PicUrl><![CDATA[%s]]></PicUrl>'
					. '<Url><![CDATA[%s]]></Url>'
					. '</item>', $item['title'], $item['desc'], $item['pic'], $item['url']
			);
		}

		$xml = '<MsgType><![CDATA[news]]></MsgType>'
				. '<ArticleCount>%s</ArticleCount>'
						. '<Articles>%s</Articles>';

		return self::_format2xml(
				sprintf(
						$xml, $i, $list_xml
				)
		);
	}

}

class WeChatClient {

	public static $_URL_API_ROOT = 'https://api.weixin.qq.com';
	public static $_URL_FILE_API_ROOT = 'http://file.api.weixin.qq.com';
	public static $_URL_QR_ROOT = 'http://mp.weixin.qq.com';
	public static $_QRCODE_TICKET_DEFAULT_ID = 1;
	public static $ERRCODE_MAP = array(
			'-1' => '&#x7CFB;&#x7EDF;&#x7E41;&#x5FD9;',
			'0' => '&#x8BF7;&#x6C42;&#x6210;&#x529F;',
			'40001' => '&#x83B7;&#x53D6;access_token&#x65F6;AppSecret&#x9519;&#x8BEF;&#xFF0C;&#x6216;&#x8005;access_token&#x65E0;&#x6548;',
			'40002' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x51ED;&#x8BC1;&#x7C7B;&#x578B;',
			'40003' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;OpenID',
			'40004' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x5A92;&#x4F53;&#x6587;&#x4EF6;&#x7C7B;&#x578B;',
			'40005' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x6587;&#x4EF6;&#x7C7B;&#x578B;',
			'40006' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x6587;&#x4EF6;&#x5927;&#x5C0F;',
			'40007' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x5A92;&#x4F53;&#x6587;&#x4EF6;id',
			'40008' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x6D88;&#x606F;&#x7C7B;&#x578B;',
			'40009' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x56FE;&#x7247;&#x6587;&#x4EF6;&#x5927;&#x5C0F;',
			'40010' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x8BED;&#x97F3;&#x6587;&#x4EF6;&#x5927;&#x5C0F;',
			'40011' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x89C6;&#x9891;&#x6587;&#x4EF6;&#x5927;&#x5C0F;',
			'40012' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x7F29;&#x7565;&#x56FE;&#x6587;&#x4EF6;&#x5927;&#x5C0F;',
			'40013' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;APPID',
			'40014' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;access_token',
			'40015' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x83DC;&#x5355;&#x7C7B;&#x578B;',
			'40016' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x6309;&#x94AE;&#x4E2A;&#x6570;',
			'40017' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x6309;&#x94AE;&#x4E2A;&#x6570;',
			'40018' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x6309;&#x94AE;&#x540D;&#x5B57;&#x957F;&#x5EA6;',
			'40019' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x6309;&#x94AE;KEY&#x957F;&#x5EA6;',
			'40020' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x6309;&#x94AE;URL&#x957F;&#x5EA6;',
			'40021' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x83DC;&#x5355;&#x7248;&#x672C;&#x53F7;',
			'40022' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x5B50;&#x83DC;&#x5355;&#x7EA7;&#x6570;',
			'40023' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x5B50;&#x83DC;&#x5355;&#x6309;&#x94AE;&#x4E2A;&#x6570;',
			'40024' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x5B50;&#x83DC;&#x5355;&#x6309;&#x94AE;&#x7C7B;&#x578B;',
			'40025' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x5B50;&#x83DC;&#x5355;&#x6309;&#x94AE;&#x540D;&#x5B57;&#x957F;&#x5EA6;',
			'40026' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x5B50;&#x83DC;&#x5355;&#x6309;&#x94AE;KEY&#x957F;&#x5EA6;',
			'40027' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x5B50;&#x83DC;&#x5355;&#x6309;&#x94AE;URL&#x957F;&#x5EA6;',
			'40028' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x81EA;&#x5B9A;&#x4E49;&#x83DC;&#x5355;&#x4F7F;&#x7528;&#x7528;&#x6237;',
			'40029' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;oauth_code',
			'40030' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;refresh_token',
			'40031' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;openid&#x5217;&#x8868;',
			'40032' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;openid&#x5217;&#x8868;&#x957F;&#x5EA6;',
			'40033' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x8BF7;&#x6C42;&#x5B57;&#x7B26;&#xFF0C;&#x4E0D;&#x80FD;&#x5305;&#x542B;\uxxxx&#x683C;&#x5F0F;&#x7684;&#x5B57;&#x7B26;',
			'40035' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x53C2;&#x6570;',
			'40038' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x8BF7;&#x6C42;&#x683C;&#x5F0F;',
			'40039' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;URL&#x957F;&#x5EA6;',
			'40050' => '&#x4E0D;&#x5408;&#x6CD5;&#x7684;&#x5206;&#x7EC4;id',
			'40051' => '&#x5206;&#x7EC4;&#x540D;&#x5B57;&#x4E0D;&#x5408;&#x6CD5;',
			'41001' => '&#x7F3A;&#x5C11;access_token&#x53C2;&#x6570;',
			'41002' => '&#x7F3A;&#x5C11;appid&#x53C2;&#x6570;',
			'41003' => '&#x7F3A;&#x5C11;refresh_token&#x53C2;&#x6570;',
			'41004' => '&#x7F3A;&#x5C11;secret&#x53C2;&#x6570;',
			'41005' => '&#x7F3A;&#x5C11;&#x591A;&#x5A92;&#x4F53;&#x6587;&#x4EF6;&#x6570;&#x636E;',
			'41006' => '&#x7F3A;&#x5C11;media_id&#x53C2;&#x6570;',
			'41007' => '&#x7F3A;&#x5C11;&#x5B50;&#x83DC;&#x5355;&#x6570;&#x636E;',
			'41008' => '&#x7F3A;&#x5C11;oauth code',
			'41009' => '&#x7F3A;&#x5C11;openid',
			'42001' => 'access_token&#x8D85;&#x65F6;',
			'42002' => 'refresh_token&#x8D85;&#x65F6;',
			'42003' => 'oauth_code&#x8D85;&#x65F6;',
			'43001' => '&#x9700;&#x8981;GET&#x8BF7;&#x6C42;',
			'43002' => '&#x9700;&#x8981;POST&#x8BF7;&#x6C42;',
			'43003' => '&#x9700;&#x8981;HTTPS&#x8BF7;&#x6C42;',
			'43004' => '&#x9700;&#x8981;&#x63A5;&#x6536;&#x8005;&#x5173;&#x6CE8;',
			'43005' => '&#x9700;&#x8981;&#x597D;&#x53CB;&#x5173;&#x7CFB;',
			'44001' => '&#x591A;&#x5A92;&#x4F53;&#x6587;&#x4EF6;&#x4E3A;&#x7A7A;',
			'44002' => 'POST&#x7684;&#x6570;&#x636E;&#x5305;&#x4E3A;&#x7A7A;',
			'44003' => '&#x56FE;&#x6587;&#x6D88;&#x606F;&#x5185;&#x5BB9;&#x4E3A;&#x7A7A;',
			'44004' => '&#x6587;&#x672C;&#x6D88;&#x606F;&#x5185;&#x5BB9;&#x4E3A;&#x7A7A;',
			'45001' => '&#x591A;&#x5A92;&#x4F53;&#x6587;&#x4EF6;&#x5927;&#x5C0F;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45002' => '&#x6D88;&#x606F;&#x5185;&#x5BB9;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45003' => '&#x6807;&#x9898;&#x5B57;&#x6BB5;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45004' => '&#x63CF;&#x8FF0;&#x5B57;&#x6BB5;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45005' => '&#x94FE;&#x63A5;&#x5B57;&#x6BB5;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45006' => '&#x56FE;&#x7247;&#x94FE;&#x63A5;&#x5B57;&#x6BB5;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45007' => '&#x8BED;&#x97F3;&#x64AD;&#x653E;&#x65F6;&#x95F4;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45008' => '&#x56FE;&#x6587;&#x6D88;&#x606F;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45009' => '&#x63A5;&#x53E3;&#x8C03;&#x7528;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45010' => '&#x521B;&#x5EFA;&#x83DC;&#x5355;&#x4E2A;&#x6570;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45015' => '&#x56DE;&#x590D;&#x65F6;&#x95F4;&#x8D85;&#x8FC7;&#x9650;&#x5236;',
			'45016' => '&#x7CFB;&#x7EDF;&#x5206;&#x7EC4;&#xFF0C;&#x4E0D;&#x5141;&#x8BB8;&#x4FEE;&#x6539;',
			'45017' => '&#x5206;&#x7EC4;&#x540D;&#x5B57;&#x8FC7;&#x957F;',
			'45018' => '&#x5206;&#x7EC4;&#x6570;&#x91CF;&#x8D85;&#x8FC7;&#x4E0A;&#x9650;',
			'46001' => '&#x4E0D;&#x5B58;&#x5728;&#x5A92;&#x4F53;&#x6570;&#x636E;',
			'46002' => '&#x4E0D;&#x5B58;&#x5728;&#x7684;&#x83DC;&#x5355;&#x7248;&#x672C;',
			'46003' => '&#x4E0D;&#x5B58;&#x5728;&#x7684;&#x83DC;&#x5355;&#x6570;&#x636E;',
			'46004' => '&#x4E0D;&#x5B58;&#x5728;&#x7684;&#x7528;&#x6237;',
			'47001' => '&#x89E3;&#x6790;JSON/XML&#x5185;&#x5BB9;&#x9519;&#x8BEF;',
			'48001' => 'api&#x529F;&#x80FD;&#x672A;&#x6388;&#x6743;',
			'50001' => '&#x7528;&#x6237;&#x672A;&#x6388;&#x6743;&#x8BE5;api',
	);
	public static $_USERINFO_LANG = 'en';
	private $_ACCESS_TOKEN_FILE;
	private $_JSAPI_TICKET_FILE;
	private $_appid;
	private $_appsecret;
	//private static $_accessTokenCache = array();
	private static $ERROR_LOGS = array();
	private static $ERROR_NO = 0;

	public function __construct($appid, $appsecret) {
		$this->_appid = $appid;
		$this->_appsecret = $appsecret;
		$this->_ACCESS_TOKEN_FILE = RUNTIME_PATH.'.access_token';
		$this->_JSAPI_TICKET_FILE = RUNTIME_PATH.'.jsapi_ticket';
	}

	public static function error() {
		return self::$ERRCODE_MAP[self::$ERROR_NO] ? self::$ERRCODE_MAP[self::$ERROR_NO] : self::$ERROR_NO;
	}

	public static function checkIsSuc($res) {
		$result = true;
		if (is_string($res)) {
			$res = json_decode($res, true);
		}
		if (isset($res['errcode']) && ( 0 !== (int) $res['errcode'])) {
			array_push(self::$ERROR_LOGS, $res);
			$result = false;
			self::$ERROR_NO = $res['errcode'];
		}
		return $result;
	}

	public static function get($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		# curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		if (!curl_exec($ch)) {
			error_log(curl_error($ch));
			$data = '';
		} else {
			$data = curl_multi_getcontent($ch);
		}
		curl_close($ch);
		return $data;
	}

	private static function post($url, $data) {
		if (!function_exists('curl_init')) {
			return '';
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		# curl_setopt( $ch, CURLOPT_HEADER, 1);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		$data = curl_exec($ch);
		if (!$data) {
			error_log(curl_error($ch));
		}
		curl_close($ch);
		return $data;
	}

	public function getAccessToken($cache = true) {
		if($cache && file_exists($this->_ACCESS_TOKEN_FILE)){
			$data = json_decode ( file_get_contents ($this->_ACCESS_TOKEN_FILE), true);
		}else{
			$data = array('expire_time'=>(time()-1), 'access_token'=>'');
		}
		if ($data['expire_time'] < time ()) {
			$url = self::$_URL_API_ROOT . "/cgi-bin/token?grant_type=client_credential&appid=$this->_appid&secret=$this->_appsecret";
			$res = json_decode ( self::get($url), true );
			$access_token = $res['access_token'];
			if ($access_token) {
				$data['expire_time'] = time () + 7000;
				$data['access_token'] = $access_token;
				$fp = fopen ($this->_ACCESS_TOKEN_FILE, "w" );
				fwrite ( $fp, json_encode ( $data ) );
				fclose ( $fp );
			}
		} else {
			$access_token = $data['access_token'];
		}
		return $access_token;
	}

	public function setAccessToken($tokenInfo) {
		if ($tokenInfo) {
			$data = array('expire_time'=>(time () + 7000), 'access_token'=>$tokenInfo);
			$fp = fopen ($this->_ACCESS_TOKEN_FILE, "w" );
			fwrite ( $fp, json_encode ( $data ) );
			fclose ( $fp );
		}
	}

	public function upload($type, $file_path, $mediaidOnly = 1) {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_FILE_API_ROOT . "/cgi-bin/media/upload?access_token=$access_token&type=$type";

		$res = self::post($url, array('media' => "@$file_path"));
		$res = json_decode($res, true);

		if (self::checkIsSuc($res)) {
			return $mediaidOnly ? $res['media_id'] : $res;
		}
		return null;
	}

	public function download($mid) {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_FILE_API_ROOT . "/cgi-bin/media/get?access_token=$access_token&media_id=$mid";

		return self::get($url);
	}

	public function getMenu() {

		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/menu/get?access_token=$access_token";

		$json = self::get($url);

		$res = json_decode($json, true);
		if (self::checkIsSuc($res)) {
			return $res;
		}
		return null;
	}

	public function deleteMenu() {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/menu/delete?access_token=$access_token";

		$res = self::get($url);
		return self::checkIsSuc($res);
	}

	public function setMenu($myMenu) {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/menu/create?access_token=$access_token";

		if (defined('JSON_UNESCAPED_UNICODE')) {
			$json = is_string($myMenu) ? $myMenu : json_encode($myMenu, JSON_UNESCAPED_UNICODE);
		} else {
			$json = is_string($myMenu) ? $myMenu : json_encode($myMenu);
		}

		$json = urldecode($json);
		$res = self::post($url, $json);

		return self::checkIsSuc($res);
	}

	private function _send($to, $type, $data) {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/message/custom/send?access_token=$access_token";

		$json = json_encode(
				array(
						'touser' => $to,
						'msgtype' => $type,
						$type => $data
				)
		);

		$res = self::post($url, $json);

		return self::checkIsSuc($res);
	}

	public function sendTextMsg($to, $msg) {
		return $this->_send($to, 'text', array('content' => $msg));
	}

	public function sendImgMsg($to, $mid) {
		return $this->_send($to, 'image', array('media_id' => $mid));
	}

	public function sendVoice($to, $mid) {
		return $this->_send($to, 'voice', array('media_id' => $mid));
	}

	public function sendVideo($to, $mid, $title, $desc) {
		return $this->_send($to, 'video', array(
				'media_id' => $mid,
				'title' => $title,
				'description' => $desc
		));
	}

	public function sendMusic($to, $url, $thumb_mid, $title, $desc = '', $hq_url = '') {
		return $this->_send($to, 'music', array(
				'media_id' => $mid,
				'title' => $title,
				'description' => $desc || $title,
				'musicurl' => $url,
				'thumb_media_id' => $thumb_mid,
				'hqmusicurl' => $hq_url || $url
		));
	}

	static private function _filterForRichMsg($articles) {
		$i = 0;
		$ii = len($articles);
		$list = array('title', 'desc', 'url', 'thumb_url');
		$result = array();
		while ($i < $ii) {
			$currentArticle = $articles[$i++];
			try {
				array_push($result, array(
						'title' => $currentArticle['title'],
						'description' => $currentArticle['desc'],
						'url' => $currentArticle['url'],
						'picurl' => $currentArticle['thumb_url']
				));
			} catch (Exception $e) {

			}
		}
		return $result;
	}

	public function uploadNews($articles) {
		$i = 0;
		$ii = count($articles);
		$result = array();
		while ($i < $ii) {
			$currentArticle = $articles[$i++];
			try {
				array_push($result, array(
						'thumb_media_id' => $currentArticle['thumb_media_id'],
						'title' => $this->convertToUtf($currentArticle['title']),
						'content' => $this->convertToUtf($currentArticle['content']),
						'author' => $this->convertToUtf($currentArticle['author']),
						'content_source_url' => $this->convertToUtf($currentArticle['url']),
						'digest' => $this->convertToUtf($currentArticle['desc']),
						'show_cover_pic' => 1
				));
			} catch (Exception $e) {

			}
		}

		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/media/uploadnews?access_token=$access_token";
		if (defined('JSON_UNESCAPED_UNICODE')) {
			$json = json_encode(array('articles' => $result), JSON_UNESCAPED_UNICODE);
		} else {
			$json = json_encode(array('articles' => $result));
		}

		$json = urldecode($json);

		$res = self::post($url, $json);
		if (self::checkIsSuc($res)) {
			return json_decode($res, true);
		} else {
			return false;
		}
	}

	public function sendMassMsg($msg) {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/message/mass/sendall?access_token=$access_token";
		$post = array();
		$post['filter'] = array('group_id' => $msg['group_id']);
		if ($msg['type'] == 'media') {
			$post['mpnews'] = array('media_id' => $msg['media_id']);
			$post['msgtype'] = 'mpnews';
		} else {
			$post['text'] = array('content' => $this->convertToUtf($msg['text']));
			$post['msgtype'] = 'text';
		}

		if (defined('JSON_UNESCAPED_UNICODE')) {
			$json = json_encode($post, JSON_UNESCAPED_UNICODE);
		} else {
			$json = json_encode($post);
		}

		$json = urldecode($json);

		$res = self::post($url, $json);
		if (self::checkIsSuc($res)) {
			return json_decode($res, true);
		} else {
			return false;
		}
	}

	function convertToUtf($str) {
		return urlencode(diconv($str, CHARSET, 'UTF-8'));
	}

	public function sendRichMsg($to, $articles) {

		return $this->_send($to, 'news', array(
				'articles' => self::_filterForRichMsg($articles)
		));
	}

	public function createGroup($name) {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/groups/create?access_token=$access_token";

		$res = self::post($url, json_encode(array(
				'group' => array('name' => $name)
		)));

		$res = json_decode($res, true);
		return self::checkIsSuc($res) ? $res['group']['id'] : null;
	}

	public function renameGroup($gid, $name) {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/groups/update?access_token=$access_token";

		$res = self::post($url, json_encode(array(
				'group' => array(
						'id' => $gid,
						'name' => $name
				)
		)));

		$res = json_decode($res, true);
		return self::checkIsSuc($res);
	}

	public function moveUserById($uid, $gid) {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/groups/members/update?access_token=$access_token";

		$res = self::post(
				$url, json_encode(
						array(
								'openid' => $mid,
								'to_groupid' => $gid
						)
				)
		);

		$res = json_decode($res, true);
		return self::checkIsSuc($res);
	}

	public function getAllGroups() {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/groups/get?access_token=$access_token";

		$res = json_decode(self::get($url), true);

		if (self::checkIsSuc($res)) {
			return $res['groups'];
		} else {
			return null;
		}
	}

	public function getGroupidByUserid($uid) {
		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/groups/getid?access_token=$access_token";

		$res = self::post($url, json_encode(array(
				'openid' => $mid
		)));

		$res = json_decode($res, true);
		return self::checkIsSuc($res) ? $res['groupid'] : null;
	}

	public function getUserInfoById($uid, $lang = '') {
		if (!$lang) {
			$lang = self::$_USERINFO_LANG;
		}
		$access_token = $this->getAccessToken();
		$url = self::$_URL_API_ROOT . "/cgi-bin/user/info?access_token=$access_token&openid=$uid&lang=$lang";

		$res_str = self::get($url);
		if($_GET['op'] == "clearn"){	//打印测试信息
			echo $res_str;
		}

		$res = json_decode($res_str, true);

		return self::checkIsSuc($res) ? $res : null;
	}

	public function getFollowersList($next_id = '') {
		$access_token = $this->getAccessToken();
		$extend = '';
		if ($next_id) {
			$extend = "&next_openid=$next_id";
		}
		$url = self::$_URL_API_ROOT . "/cgi-bin/user/get?access_token=${access_token}$extend";

		$res = json_decode(
				self::get($url), true
		);

		return self::checkIsSuc($res) ? array(
				'total' => $res['total'],
				'list' => $res['data']['openid'],
				'next_id' => isset($res['next_openid']) ? $res['next_openid'] : null
		) : null;
	}

	public function getOAuthConnectUri($redirect_uri, $state = '', $scope = 'snsapi_base') {
		$redirect_uri = urlencode($redirect_uri);
		$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid={$this->_appid}&redirect_uri={$redirect_uri}&response_type=code&scope={$scope}&state={$state}#wechat_redirect";
		return $url;
	}

	// 返回参数，参考：http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html
	// 	access_token  网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
	// 	expires_in  access_token接口调用凭证超时时间，单位（秒）
	// 	refresh_token  用户刷新access_token
	// 	openid  用户唯一标识，请注意，在未关注公众号时，用户访问公众号的网页，也会产生一个用户和公众号唯一的OpenID
	// 	scope  用户授权的作用域，使用逗号（,）分隔
	// 	unionid  只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。详见：获取用户个人信息（UnionID机制）
	public function getAccessTokenByCode($code) {
		$url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid={$this->_appid}&secret={$this->_appsecret}&code=$code&grant_type=authorization_code";
		$res_str = self::get($url);
	
		if($_GET['op'] == "clearn"){	//打印测试信息
			echo $res_str;
		}
	
		$res = json_decode($res_str, true);
		return $res;
	}

	public function refreshAccessTocken($refresh_token) {
		$url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid={$this->_appid}&grant_type=refresh_token&refresh_token=$refresh_token";
		$res = json_decode(self::get($url), true);
		return $res;
	}

	// 返回参数，参考：http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html
	// 	openid  用户的唯一标识
	// 	nickname  用户昵称
	// 	sex  用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
	// 	province  用户个人资料填写的省份
	// 	city  普通用户个人资料填写的城市
	// 	country  国家，如中国为CN
	// 	headimgurl  用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效。
	// 	privilege  用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）
	// 	unionid  只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段。详见：获取用户个人信息（UnionID机制）
	public function getUserInfoByAuth($access_token, $openid, $lang = 'zh_CN') {
		$url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=$lang";
		$res = json_decode(self::get($url), true);
		return $res;
	}
	
	public static function getQrcodeImgByTicket($ticket) {
		return self::get($this->getQrcodeImgUrlByTicket($ticket));
	}
	
	public static function getQrcodeImgUrlByTicket($ticket) {
		$ticket = urlencode($ticket);
		return self::$_URL_QR_ROOT . "/cgi-bin/showqrcode?ticket=$ticket";
	}
	
	//场景二维码
	public function getQrcodeTicket($options = array()) {
		$access_token = $this->getAccessToken();
	
		$scene_id = isset($options['scene_id']) ? (int) $options['scene_id'] : 0;
		$scene_str = isset($options['scene_str']) ? $options['scene_str'] : '';
		$expire = isset($options['expire']) ? (int) $options['expire'] : 0;
		$ticketOnly = isset($options['ticketOnly']) ? $options['ticketOnly'] : 1;
	
		$url = self::$_URL_API_ROOT . "/cgi-bin/qrcode/create?access_token=$access_token";
		if( !empty($scene_str)){
			$data = array(
					'action_name' => 'QR_LIMIT_SCENE',
					'action_info' => array(
					'scene' => array(
					    'scene_str' => $scene_str
							)
					)
			);
		}else{
			$data = array(
					'action_name' => 'QR_LIMIT_SCENE',
					'action_info' => array(
					'scene' => array(
					    'scene_id' => $scene_id
							)
					)
			);
		}
		if ($expire) {
			$data['expire_seconds'] = $expire;
			$data['action_name'] = 'QR_SCENE';
		}
	
		if ($data['action_name'] == 'QR_LIMIT_SCENE' && $scene_id > 100000) {
			$data['action_info']['scene']['scene_id'] = self::$_QRCODE_TICKET_DEFAULT_ID;
		}
	
		$data = json_encode($data);
	
		$res = self::post($url, $data);
		$res = json_decode($res, true);
	
		if (self::checkIsSuc($res)) {
			return $ticketOnly ? $res['ticket'] : $res;
		}
		return null;
	}
	
	//JSAPI接口， 获取jsapi的ticket
	private function getJsApiTicket() {
		if($cache && file_exists($this->_JSAPI_TICKET_FILE)){
			$data = json_decode ( file_get_contents ($this->_JSAPI_TICKET_FILE), true);
		}else{
			$data = array('expire_time'=>(time()-1), 'jsapi_ticket'=>'');
		}
		if ($data['expire_time'] < time()) {
			$accessToken = $this->getAccessToken();
			$url = "https://api.weixin.qq.com/cgi-bin/ticket/getticket?type=jsapi&access_token=$accessToken";
			$res = json_decode(self::get($url), true);
			$ticket = $res['ticket'];
			if ($ticket) {
				$fp = fopen($this->_JSAPI_TICKET_FILE, 'w');
				fwrite($fp, json_encode(array('expire_time'=>time()+7000, 'jsapi_ticket'=>$ticket)));
				fclose($fp);
			}
		} else {
			$ticket = $data['jsapi_ticket'];
		}
		return $ticket;
	}
	//成才随机字符串
	private function createNonceStr($length = 16) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
			$str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	
	//获取签名包 （用于自定义分享）
	public function getSignPackage($url) {
		$jsapiTicket = $this->getJsApiTicket();
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
		// 这里参数的顺序要按照 key 值 ASCII 码升序排序
		$string = "jsapi_ticket=$jsapiTicket&noncestr=$nonceStr&timestamp=$timestamp&url=$url";
		$signature = sha1($string);
		$signPackage = array(
				"appId"     => $this->_appid,
				"nonceStr"  => $nonceStr,
				"timestamp" => $timestamp,
				"url"       => $url,
				"signature" => $signature,
				"rawString" => $string
		);
		return $signPackage;
	}
	

}


