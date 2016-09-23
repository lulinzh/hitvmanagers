<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------


// 数据库配置集中化   add by heqh 2016/4/12 10:07:42
include_once './config.db.php';

/**
 * 系统配文件
 * 所有系统级别的配置
 */
return array(
    /* 模块相关配置 */
    'AUTOLOAD_NAMESPACE' => array('Addons' => ONETHINK_ADDON_PATH), //扩展模块列表
    'DEFAULT_MODULE'     => 'Home',	//默认模块
    'MODULE_DENY_LIST'   => array('Common','User','Admin','Install'),	//禁止模块列表
    'MODULE_ALLOW_LIST'  => array('Home'),   //允许访问的模块，设置了此配置，加载不存在的模块，可以跳转empty

    /* 系统数据加密设置 */
    'DATA_AUTH_KEY' => '8qRTHk3JkOVl0nZCyeMGKWFayTJXyUC7', //默认数据加密KEY

    /* 用户相关设置 */
    'USER_MAX_CACHE'     => 1000, //最大缓存用户数
    'USER_ADMINISTRATOR' => 1, //管理员用户ID

    /* URL配置 */
    'URL_CASE_INSENSITIVE' => true, //默认false 表示URL区分大小写 true则表示不区分大小写
    'URL_MODEL'            => 2, //URL模式
    'VAR_URL_PARAMS'       => '', // PATHINFO URL参数变量
    'URL_PATHINFO_DEPR'    => '/', //PATHINFO URL分割符

    /* 全局过滤配置 */
    'DEFAULT_FILTER' => '', //全局过滤函数

    /* 数据库配置 */
    'DB_TYPE'   => 'mysql', // 数据库类型
    'DB_HOST'   => LX_DBHOST, // 服务器地址            数据库配置集中化   modify by heqh 2016/4/12 10:07:42
    'DB_NAME'   => LX_DBNAME, // 数据库名            数据库配置集中化   modify by heqh 2016/4/12 10:07:42
    'DB_USER'   => LX_DBUSER, // 用户名            数据库配置集中化   modify by heqh 2016/4/12 10:07:42
    'DB_PWD'    => LX_DBPW,  // 密码            数据库配置集中化   modify by heqh 2016/4/12 10:07:42
    'DB_PORT'   => LX_DBPORT, // 端口
    'DB_PREFIX' => 'lx_', // 数据库表前缀

    /* 文档模型配置 (文档模型核心配置，请勿更改) */
    'DOCUMENT_MODEL_TYPE' => array(2 => '主题', 1 => '目录', 3 => '段落'),
    
    
    
    //正则表达式
    'LXSYS_PREG_PHONE'  => "/1[3458]{1}\d{9}$/",	//正则表达式：判断手机号码
    'LXSYS_PREG_EMAIL'  => "/^\\w+((-\\w+)|(\\.\\w+))*\\@[A-Za-z0-9]+((\\.|-)[A-Za-z0-9]+)*\\.[A-Za-z0-9]+$/",	//正则表达式：判断邮箱

    //************广西广电用户中心接口测试参数*************
//    'SrcSysID'=>'21006', //发起方编码，HiTV电视客户端
//    'DstSysID'=>'21000', //用户中心
//    'GDUCENTER_SOAPURL' => 'http://219.232.84.141:8080/UCenterWeb/services/userOperService', //广西广电用户中心接口地址
    //************广西广电用户中心接口测试参数*************

    //************广西广电用户中心接口生产参数*************
    'SrcSysID'=>'21006', //发起方编码，HiTV电视客户端
    'DstSysID'=>'21000', //用户中心
    'GDUCENTER_URL' => 'http://10.0.64.10:8180/UCenterWeb/services/userOperService', //广西广电用户中心接口地址，转发至219.232.84.141
    //************广西广电用户中心接口生产参数*************

    'HITV_QRCODE'=>'hitv://ss?session_token=', //HiTV二维码字符串头
    'QRCODE_URL' => 'http://10.0.64.226:8000/index.php?c=Intf&a=qrcode&data=', //二维码接口地址
);
