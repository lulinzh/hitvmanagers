<?php
/**
 * QQ登录回调入口
 */

// 指定控制器
define('BIND_CONTROLLER','Passport');

// 指定访问方法
define('BIND_ACTION','qqlogincallback');

// 使用统一入口
require './index.php';