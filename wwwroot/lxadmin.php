<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2014 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用入口文件

// 检测PHP环境
if(version_compare(PHP_VERSION,'5.3.0','<'))  die('require PHP > 5.3.0 !');

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG',True);

// 显示php环境
if( APP_DEBUG && isset($_REQUEST['phpinfo']) && $_REQUEST['phpinfo']=== 'phpinfo' ){
	phpinfo();
	exit;
}

// 后台入口模块
define('BIND_MODULE','Admin');

// 定义应用目录
define('APP_PATH','./Application/');

// 自己新增的第三方类库路径 add by heqh 2016/1/27 19:48:02
define('BS_VENDOR_PATH','./BrightStarCore/Lib/');

// 引入ThinkPHP入口文件
require './BrightStarCore/ThinkPHP/ThinkPHP.php';

// 亲^_^ 后面不需要任何代码了 就是如此简单