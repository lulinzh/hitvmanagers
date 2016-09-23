<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/9/12
 * Time: 22:43
 */

namespace Common\Lib\Hitv\Model;


class HitvOperationItemModel
{
    public $title;
    public $info;
    public $thumb;
    public $action;
    public $data;

    public function __construct()
    {
        $this->title = "测试";
        $this->thumb = "http://hitvout.starcor1.net:8098/nn_cms_app_out/data/upimg/ui_style/1/default/3aaff2bd40417410f0f0dde11a5b7492.jpg";
        $this->action = "web";
        $this->data = "http://baidu.com";
        $this->info = "测试内容";

    }
}