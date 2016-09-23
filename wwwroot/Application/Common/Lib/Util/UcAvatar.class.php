<?php

namespace Common\Lib\Util;


/**
 *  自动更新ucenter的用户头像
 * @author heqh
 *  2016-03-29 by heqh create file
 */
class UcAvatar {

	public static function upload($uid, $imgFileUrl) {
		
		if(!$uid || !$imgFileUrl) {
			return false;
		}
		$avatarPath = TEMP_PATH;		//临时目录
		$tmpAvatar = $avatarPath.'./'.$uid.'_avatar.tmp';		//下载的原始文件
		file_exists($tmpAvatar) && @unlink($tmpAvatar);
		
		self::curl_download($imgFileUrl, $tmpAvatar);
		if(!is_file($tmpAvatar)) {
			return false;
		}		
		list($width, $height, $type, $attr) = getimagesize($tmpAvatar);
		if(!$width) {
			return false;
		}
		$imageType = array(1 => '.gif', 2 => '.jpg', 3 => '.png');
		$fileType = $imageType[$type];
		if(!$fileType) {
			$fileType = '.jpg';
		}
		
		$tmpAvatarBig = $avatarPath.'./'.$uid.'_avatar_big'.$fileType;
		$tmpAvatarMiddle = $avatarPath.'./'.$uid.'_avatar_middle'.$fileType;
		$tmpAvatarSmall = $avatarPath.'./'.$uid.'_avatar_small'.$fileType;
		$image = new \Think\Image();
		$image->open($tmpAvatar);
		$image->thumb(200,200)->save($tmpAvatarBig);
		$image->thumb(120,120)->save($tmpAvatarMiddle);
		$image->thumb(48,48)->save($tmpAvatarSmall);
		
		
		$avatar1 = self::byte2hex(file_get_contents($tmpAvatarBig));
		$avatar2 = self::byte2hex(file_get_contents($tmpAvatarMiddle));
		$avatar3 = self::byte2hex(file_get_contents($tmpAvatarSmall));
		
		$extra = '&avatar1='.$avatar1.'&avatar2='.$avatar2.'&avatar3='.$avatar3;
		$result = self::uc_api_post_ex('user', 'rectavatar', array('uid' => $uid), $extra);
		
		@unlink($tmpAvatar);
		@unlink($tmpAvatarBig);
		@unlink($tmpAvatarMiddle);
		@unlink($tmpAvatarSmall);
		
		return true;
	}

	public static function byte2hex($string) {
		$buffer = '';
		$value = unpack('H*', $string);
		$value = str_split($value[1], 2);
		$b = '';
		foreach($value as $k => $v) {
			$b .= strtoupper($v);
		}

		return $b;
	}

	public static function uc_api_post_ex($module, $action, $arg = array(), $extra = '') {
		$s = $sep = '';
		foreach($arg as $k => $v) {
			$k = urlencode($k);
			if(is_array($v)) {
				$s2 = $sep2 = '';
				foreach($v as $k2 => $v2) {
					$k2 = urlencode($k2);
					$s2 .= "$sep2{$k}[$k2]=".urlencode(uc_stripslashes($v2));
					$sep2 = '&';
				}
				$s .= $sep.$s2;
			} else {
				$s .= "$sep$k=".urlencode(uc_stripslashes($v));
			}
			$sep = '&';
		}
		$postdata = uc_api_requestdata($module, $action, $s, $extra);
		return uc_fopen2(UC_API.'/index.php', 500000, $postdata, '', TRUE, UC_IP, 20);
	}
	
	/**
	 * 采集远程文件
	 * @access public
	 * @param string $remote 远程文件名
	 * @param string $local 本地保存文件名
	 * @return mixed
	 */
	static public function curl_download($remote,$local) {
		$cp = curl_init($remote);
		$fp = fopen($local,"w");
		curl_setopt($cp, CURLOPT_FILE, $fp);
		curl_setopt($cp, CURLOPT_HEADER, 0);
		curl_exec($cp);
		curl_close($cp);
		fclose($fp);
	}
}
