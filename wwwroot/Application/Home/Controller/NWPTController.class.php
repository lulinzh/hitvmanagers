<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/8/25
 * Time: 15:33
 */
namespace Home\Controller;

use Common\Lib\Hitv\Model\StatusModel;
use Home\Controller\Base\HomeController;

class NWPTController extends HomeController{
    function get_ip_cam_list(){
        $result = array(
            'status' => new StatusModel(),
            'result' => array(
                array('url' => 'http://10.0.64.227:10001/outdoor.ts', 'title' => '坛良村活动健身'),
                array('url' => 'http://10.0.64.227:10002/outdoor.ts', 'title' => '坛良村村尾'),
                array('url' => 'http://10.0.64.227:10003/outdoor.ts', 'title' => '坛良村村委'),
                array('url' => 'http://10.0.64.227:10004/outdoor.ts', 'title' => '坛良村卡口'),
//                array('url' => 'http://10.0.64.227:10005/outdoor3.ts', 'title' => '坛良村村尾')
            )
        );
        $this->ajaxReturn($result, $this->_type);
    }

    function get_home_cam_list(){
        $result = array(
            'status' => new StatusModel(),
            'result' => array(
                array('url' => 'http://10.0.64.227:30001/home.ts', 'title' => '会展现场')
            )
        );
        $this->ajaxReturn($result, $this->_type);
    }

    function get_rtmp_push_address(){
        $result = array(
            'status' => new StatusModel(),
            'result' => array(
                'url' => 'http://10.0.11.9:8011/rtmp.ffm'
            )
        );
        $this->ajaxReturn($result, $this->_type);
    }

    function get_rtmp_video_address(){
        $result = array(
            'status' => new StatusModel(),
            'result' => array(
                'url' => 'http://10.0.11.9:8011/rtmp.ts'
            )
        );
        $this->ajaxReturn($result, $this->_type);
    }
}