<?php
include_once  dirname(__FILE__).'/config.db.php';		//数据库配置集中化   add by heqh 2016/4/12 10:07:42

define('UC_CONNECT', 'mysql');
define('UC_DBHOST', LX_DBHOST.':'.LX_DBPORT);
define('UC_DBUSER', LX_DBUSER);
define('UC_DBPW', LX_DBPW);
define('UC_DBNAME', LX_DBNAME);
define('UC_DBCHARSET', 'utf8');
define('UC_DBTABLEPRE', '`'.UC_DBNAME.'`.uc_');
define('UC_DBCONNECT', '0');
define('UC_KEY', '8qRTHk3JkOVl0nZCyeMGKWFayTJXyUC7');
define('UC_API', 'http://'.$_SERVER["SERVER_NAME"].'/uc_server');
define('UC_CHARSET', 'utf-8');
define('UC_IP', '');
define('UC_APPID', '1');
define('UC_PPP', '20');

//应用程序数据库连接参数
$dbhost = UC_DBHOST;			// 数据库服务器
$dbuser = UC_DBUSER;			// 数据库用户名
$dbpw = UC_DBPW;				// 数据库密码
$dbname = UC_DBNAME;			// 数据库名
$pconnect = 0;				// 数据库持久连接 0=关闭, 1=打开
$tablepre = 'lx_';   		// 表名前缀, 同一数据库安装多个论坛请修改此处
$dbcharset = 'utf8';			// MySQL 字符集, 可选 'gbk', 'big5', 'utf8', 'latin1', 留空为按照论坛字符集设定
//同步登录 Cookie 设置
$cookiedomain = ''; 			// cookie 作用域
$cookiepath = '/';			// cookie 作用路径