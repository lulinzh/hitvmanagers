<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/17
 * Time: 17:29
 */
namespace Common\Lib\Hitv\Model;

use Common\Lib\Hitv\Query\QueryUtils;

class HitvOrderModel{
    public $price;
    public $title;
    public $info;
    public $app_order_id;
    public $app_id;
    public $create_time;
    public $pay_time;
    public $order_id;
    public $gen_user_id;
    public $pay_user_id;

    /**
     * @param $set_app_id
     * @param $set_app_secret
     * @param $set_app_order_id
     * @param $set_price
     * @param $set_title
     * @param $set_info
     * @param $set_gen_user_id
     * @param $set_status StatusModel
     * @return HitvOrderModel
     */
    static function start_order(
        $set_app_id,
        $set_app_secret,
        $set_app_order_id,
        $set_price,
        $set_title,
        $set_info,
        $set_gen_user_id,
        $set_status
    ){
        $app_info = HitvWebAppModel::createByAppId($set_app_id, $set_status);
        if($app_info){
            if($app_info->app_secret == $set_app_secret){
                //下单
                if($set_app_order_id){
                    if($set_price){
                        if($set_title){
                            $order_id = QueryUtils::addNewOrder($set_title,$set_info,$set_price,$set_app_order_id, $set_app_id,$set_gen_user_id);
                            return HitvOrderModel::createByOrderId($order_id, $set_status);
                        }else{
                            $set_status->needOrderTitle();
                        }
                    }else{
                        $set_status->needOrderPrice();
                    }
                }else{
                    $set_status->needAppOrderId();
                }
            }else{
                $set_status->webAppSecretNotEqual();
            }
        }else{
            $set_status->webAppNotFound();
        }
        return null;
    }

    /**
     * @param $set_id string
     * @param $set_status StatusModel
     * @return HitvOrderModel
     */
    public static function createByOrderId($set_id, $set_status){
        if($set_id){
            $order_data = QueryUtils::getOrderById($set_id);
            if($order_data){
                $result = new HitvOrderModel();
                $result->order_id = $order_data['id'];
                $result->title = $order_data['title'];
                $result->info = $order_data['info'];
                $result->app_order_id = $order_data['app_order_id'];
                $result->app_id = $order_data['app_id'];
                $result->create_time = $order_data['create_time'];
                $result->pay_time = $order_data['pay_time'];
                $result->price = $order_data['price'];
                $result->gen_user_id = $order_data['gen_user_id'];
                $result->pay_user_id = $order_data['pay_user_id'];
                return $result;
            }else{
                $set_status->orderNotFound();
            }
        }else{
            $set_status->needOrderId();
        }
        return null;
    }

    /**
     * 订单是否付款
     * @return bool
     */
    public function isPay(){
       return $this->pay_time && $this->pay_user_id;
    }

    /**
     * @param StatusModel $set_status
     */
    public function save($set_status)
    {
        $result = QueryUtils::setOrderInfo(
            $this->order_id,
            $this->title,
            $this->info,
            $this->app_order_id,
            $this->app_id,
            $this->create_time,
            $this->pay_time,
            $this->price,
            $this->gen_user_id,
            $this->pay_user_id
        );
        if ($result == -1) {
            $set_status->operationFailed();
        }
    }


}