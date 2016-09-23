<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.thinkphp.cn>
// +----------------------------------------------------------------------

/**
 * 前台配置文件
 * 所有除开系统级别的前台配置
 */
return array(

    // 预先加载的标签库
    'TAGLIB_PRE_LOAD' => 'OT\\TagLib\\Article,OT\\TagLib\\Think',

    /* 主题设置 */
    'TMPL_ACTION_ERROR' => 'Public:error',
    'TMPL_ACTION_SUCCESS' => 'Public:success',
    //'TMPL_EXCEPTION_FILE'   =>  MODULE_PATH.'View/tplgxbs/Public/exception.html',// 异常页面的模板文件
    'DEFAULT_THEME' => 'test',  // 默认模板主题名称   default,tplgxbs

    /* 数据缓存设置 */
    'DATA_CACHE_PREFIX' => 'bsth_', // 缓存前缀  brightstarthink的简称
    'DATA_CACHE_TYPE' => 'File', // 数据缓存类型

    /* 文件上传相关配置 */
    'DOWNLOAD_UPLOAD' => array(
        'mimes' => '', //允许上传的文件MiMe类型
        'maxSize' => 5 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
        'exts' => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml', //允许上传的文件后缀
        'autoSub' => true, //自动子目录保存文件
        'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './Uploads/Download/', //保存根路径
        'savePath' => '', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt' => '', //文件保存后缀，空则使用原后缀
        'replace' => false, //存在同名是否覆盖
        'hash' => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //下载模型上传配置（文件上传类配置）

    /* 编辑器图片上传相关配置 */
    'EDITOR_UPLOAD' => array(
        'mimes' => '', //允许上传的文件MiMe类型
        'maxSize' => 2 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
        'exts' => 'jpg,gif,png,jpeg', //允许上传的文件后缀
        'autoSub' => true, //自动子目录保存文件
        'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './Uploads/Editor/', //保存根路径
        'savePath' => '', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt' => '', //文件保存后缀，空则使用原后缀
        'replace' => false, //存在同名是否覆盖
        'hash' => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ),

    /* 模板相关配置 */
    'TMPL_PARSE_STRING' => array(
        '__STATIC__' => __ROOT__ . '/Public/static',
        '__TPLROOT__' => __ROOT__ . '/Public/' . MODULE_NAME . '/tplgxbs',
        '__ADDONS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/tplgxbs/Addons',
        '__IMG__' => __ROOT__ . '/Public/' . MODULE_NAME . '/tplgxbs/images',
        '__CSS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/tplgxbs/css',
        '__JS__' => __ROOT__ . '/Public/' . MODULE_NAME . '/tplgxbs/js',
    ),

    /* SESSION 和 COOKIE 配置 */
    'SESSION_PREFIX' => 'lx_home_', //session前缀
    'COOKIE_PREFIX' => 'lx_home_', // Cookie前缀 避免冲突

    /**
     * 附件相关配置
     * 附件是规划在插件中的，所以附件的配置暂时写到这里
     * 后期会移动到数据库进行管理
     */
    'ATTACHMENT_DEFAULT' => array(
        'is_upload' => true,
        'allow_type' => '0,1,2', //允许的附件类型 (0-目录，1-外链，2-文件)
        'driver' => 'Local', //上传驱动
        'driver_config' => null, //驱动配置
    ), //附件默认配置

    'ATTACHMENT_UPLOAD' => array(
        'mimes' => '', //允许上传的文件MiMe类型
        'maxSize' => 5 * 1024 * 1024, //上传的文件大小限制 (0-不做限制)
        'exts' => 'jpg,gif,png,jpeg,zip,rar,tar,gz,7z,doc,docx,txt,xml', //允许上传的文件后缀
        'autoSub' => true, //自动子目录保存文件
        'subName' => array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => './Uploads/Attachment/', //保存根路径
        'savePath' => '', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt' => '', //文件保存后缀，空则使用原后缀
        'replace' => false, //存在同名是否覆盖
        'hash' => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //附件上传配置（文件上传类配置）


    //邮件配置 add by hqh 20150922
    'LXSYS_EMAIL' => array(
        'SMTP_HOST' => 'smtp.mxhichina.com', //SMTP服务器，如果是空值表示用于测试模拟发送
        //'SMTP_HOST'   => '', //SMTP服务器，如果是空值表示用于测试模拟发送
        'SMTP_PORT' => '25', //SMTP服务器端口   默认SSL465       非SSL默认25
        'SMTP_USER' => 'webmaster@daliyao.com', //SMTP服务器用户名
        'SMTP_PASS' => 'daliyaO111911', //SMTP服务器密码
        'FROM_EMAIL' => 'webmaster@daliyao.com', //发件人EMAIL
        'FROM_NAME' => '大力摇互动', //发件人名称
        'REPLY_EMAIL' => '', //回复EMAIL（留空则为发件人EMAIL）
        'REPLY_NAME' => '', //回复名称（留空则为发件人名称）
    ),
    'LXSYS_SMS' => array(
        //'SMS_INTF'  => 'http://utf8.sms.webchinese.cn/?Uid=heqianhang&Key=7de2d0d3c8fa9a9a6c38&smsMob={phone}&smsText={body}',	//发送短信接口，如果是空值表示用于测试模拟发送
        'SMS_INTF' => '',    //发送短信接口，如果是空值表示用于测试模拟发送
        'SMS_ACCT_MAX' => 1000,        //一个帐号每天最大发送的短信数量    建议值10
        'SMS_PHONE_MAX' => 500,        //一个手机号每天最大发送的短信数量  建议值5
        'SMS_IP_MAX' => 1000,        //一个IP每天最大发送的短信数量  建议值100
        'SMS_MIN_SECONDS' => 10,        //一个帐号或手机号码，最快60秒发送一条短信    建议值60
        'SMS_EXPIRE_SECONDS' => 600,        //验证码超时时间   建议值600
    ),

    //微信配置    通知URL:http://域名/index.php/Home/Intf/wxnotify   add by hqh 20151020
    'LXSYS_WEIXIN' => array(
        'Token' => 'lx123token456abc', //
        'AppID' => 'wx506b7818b6cb97a6', //   jsiq@qq.com登录的开发测试号
        'AppSecret' => '695ad9edaac46ac5c17d897ccc4ec14a', //
        'EncodingAESKey' => 'fDcYrBa2Vmnzpy4nLwewFBreSI1sKiqocSy6TO035Bc', //
        'ResponseHooks' => 'a:4:{s:16:"receiveMsg::text";a:4:{s:6:"plugin";s:6:"wechat";s:7:"include";s:18:"response.class.php";s:5:"class";s:11:"WSQResponse";s:6:"method";s:4:"text";}s:19:"receiveEvent::click";a:4:{s:6:"plugin";s:6:"wechat";s:7:"include";s:18:"response.class.php";s:5:"class";s:11:"WSQResponse";s:6:"method";s:5:"click";}s:23:"receiveEvent::subscribe";a:4:{s:6:"plugin";s:6:"wechat";s:7:"include";s:18:"response.class.php";s:5:"class";s:11:"WSQResponse";s:6:"method";s:9:"subscribe";}s:18:"receiveEvent::scan";a:4:{s:6:"plugin";s:6:"wechat";s:7:"include";s:18:"response.class.php";s:5:"class";s:11:"WSQResponse";s:6:"method";s:4:"scan";}}',    //响应代码
        'NotifyLogFile' => APP_PATH . 'Runtime/Logs/intf.wxnotify'
    ),

);
