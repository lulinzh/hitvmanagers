<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/9
 * Time: 16:40
 */

namespace Home\Controller;
use Home\Controller\Base\HomeController;
use Common\Lib\Hitv\Model\HitvUserModel;
use Common\Lib\Hitv\Model\StatusModel;

class WebApiController extends HomeController
{
//
//    function create_payment(){
//        $result->status = new StatusObject();
//        $app_id = I(ParamsString::WEB_APP_ID, '');
//        $app_secret = I(ParamsString::WEB_APP_SECRET, '');
//        $app_order_id = I(ParamsString::WEB_APP_ORDER_ID, '');
//        $app_order_title = I(ParamsString::WEB_APP_ORDER_TITLE, '');
//        $app_order_price = I(ParamsString::WEB_APP_ORDER_PRICE, '');
//        //参数检测
//        if(!$app_id){
//            $result->status->setNeedWebAppId();
//            $this->ajaxReturn($result);
//            return;
//        }
//        if(!$app_secret){
//            $result->status->setNeedAppSecret();
//            $this->ajaxReturn($result);
//            return;
//        }
//        if(!$app_order_id){
//            $result->status->setNeedAppOrderId();
//            $this->ajaxReturn($result);
//            return;
//        }
//        if(!$app_order_title){
//            $result->status->setNeedOrderTitle();
//            $this->ajaxReturn($result);
//            return;
//        }
//        if(!$app_order_price){
//            $result->status->setNeedOrderPrice();
//            $this->ajaxReturn($result);
//            return;
//        }
//        //查询WebApp
//        $query = new QueryUtils();
//        $app_info = $query->getWebAppFullInfo($app_id);
//        if($app_info['app_secret'] == $app_secret){
//            $query = new QueryUtils();
//            $order_id = $query->addNewPaymentOrder(
//                                    $app_id,
//                                    $app_order_id,
//                                    $app_order_price,
//                                    $app_order_title
//                                );
//            if($order_id){
//                $result->order_id = $order_id;
//            }else{
//                //插入数据失败
//                $result->status->setErrorUnknown();
//                $this->ajaxReturn($result);
//                return;
//            }
//        }else{
//            //app secret不匹配
//            $result->status->setAppSecretNotEqual();
//            $this->ajaxReturn($result);
//            return;
//        }
//        $this->ajaxReturn($result);
//    }
//获取支持的js api列表
//获取当前的系统环境及软件版本
//打开搜索结果界面
//打开扫一扫功能并回调。
//获取当前用户的hitv_id
//打开视频详情页面
//打开新的Web界面
    function angular_test(){
        $status = new StatusModel();
        $test1 = array("title"=>"获取支持的Api列表及版本号", "test_id"=>1);
        $test3 = array("title"=>"打开\"CCTV\"搜索结果界面", "test_id"=>2);
        $test4 = array("title"=>"打开扫一扫功能,当前页返回结果", "test_id"=>3);
        $test2 = array("title"=>"打开扫一扫功能,新页面读取结果", "test_id"=>4);

        $test5 = array("title"=>"获取当前用户的hitv_id", "test_id"=>5);
        $test6 = array("title"=>"打开视频详情页面", "test_id"=>6);
        $test7 = array("title"=>"打开新的Web界面", "test_id"=>7);
        $test8 = array("title"=>"打开分享界面", "test_id"=>8);
        $result = array(
            'status' => $status,
            'result' => array(
                $test1,$test3,$test4,$test2,$test5,$test6,$test7,$test8
                ),
        );
        $this->ajaxReturn($result, $this->_type);


    }
//
//    function get_user_info(){
//        $result->status = new StatusObject();
//        $app_id = I(ParamsString::WEB_APP_ID, '');
//        $app_secret = I(ParamsString::WEB_APP_SECRET, '');
//        //参数检测
//        if(!$app_id){
//            $result->status->setNeedWebAppId();
//            $this->ajaxReturn($result);
//            return;
//        }
//        if(!$app_secret){
//            $result->status->setNeedAppSecret();
//            $this->ajaxReturn($result);
//            return;
//        }
//
//        //查询WebApp
//        $query = new QueryUtils();
//        $app_info = $query->getWebAppFullInfo($app_id);
//        if($app_info['app_secret'] == $app_secret){
//            $query = new QueryUtils();
//
//            if($user_info){
//                $result->user_info = $user_info;
//            }else{
//                //插入数据失败
//                $result->status->setErrorUnknown();
//                $this->ajaxReturn($result);
//                return;
//            }
//        }else{
//            //app secret不匹配
//            $result->status->setAppSecretNotEqual();
//            $this->ajaxReturn($result);
//            return;
//        }
//        $this->ajaxReturn($result);
//    }
}