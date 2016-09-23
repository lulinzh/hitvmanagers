<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/16
 * Time: 13:39
 */
namespace Common\Lib\Hitv\Model;

/**
 * App配置类,一般在启动app的时候获取
 * Class AppInfoModel
 * @package Common\Lib\Hitv\Model
 */
class AppInfoModel {
    public $splash_link_url;
    public $splash_img_url;
    public $deprecated;

    public $index_url;
    public $discover_url;
    public $agreement_url;
    public $about_url;
    public $update_url;
    public function __construct($app_type = '', $app_ver = ''){
        $this->splash_img_url = 'http://hitv.lxsky.com/Uploads/Picture/splash.jpg';
        $this->splash_link_url = 'http://gdshop.lxsky.com/mobile/index.php?m=default&c=goods&a=index&id=58&u=0';

        $this->index_url = "http://ionic.projectd.in/index.html";
        $this->discover_url = "http://ionic.projectd.in/discover.html";
        $this->agreement_url = "http://hitv.lxsky.com/Test/test_web?type=agreement&platform={$app_type}&app_ver={$app_ver}";
        $this->about_url = "http://ionic.projectd.in/hitv_about.html";
        $this->update_url = "http://hitv.lxsky.com/Test/test_web?type=update&platform={$app_type}&app_ver={$app_ver}";

        $this->deprecated = 0;
    }
}