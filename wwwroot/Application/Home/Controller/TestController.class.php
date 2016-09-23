<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/9/6
 * Time: 10:18
 */
namespace Home\Controller;
use Home\Controller\Base\HomeController;

class TestController extends HomeController{
    public function test_web(){
        $type = I('param.type');
        $ver = I('param.app_ver');
        $platform = I('param.platform');
        echo("测试{$platform} ver {$ver}, {$type}页面");
    }
}