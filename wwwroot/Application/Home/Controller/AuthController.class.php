<?php
/**
 * Created by PhpStorm.
 * User: Zapper
 * Date: 2016/7/12
 * Time: 16:26
 */
namespace Home\Controller;
use Home\Controller\Base\HomeController;
use Common\Lib\Hitv\QueryUtils;
use Common\Lib\Hitv\StatusObject;
use Common\Lib\Hitv\WebAppItem;
use Common\Lib\Hitv\AccessTokenItem;

class AuthController extends HomeController
{
    const USER_ID = 'user_id';
    const APP_ID = 'app_id';
//    const APP_NAME = 'app_name';
//    const APP_THUMB = 'app_thumb';
    const APP_SECRET = 'app_secret';
    const REDIRECT_URI = 'redirect_uri';
    const INFO_LEVEL = 'info_level';
    const AUTH_TOKEN = 'auth_token';
    const ACCESS_CODE = 'access_code';
    const ACCESS_TOKEN = 'access_token';
    const OPEN_ID = 'open_id';

    /**
     * 网页发起调用客户端授权验证 接口
     */
    public function authorize(){
        $result->status = new StatusObject();
        $app_id = I(AuthController::APP_ID, '');
        $redirect_uri = I(AuthController::REDIRECT_URI, '');
        $info_level = I(AuthController::INFO_LEVEL, '');
        if(!$app_id){
            $result->status->setNeedWebAppId();
            $this->ajaxReturn($result);
            return;
        }
        if(!$redirect_uri){
            $result->status->setNeedRedirectURI();
            $this->ajaxReturn($result);
            return;
        }
        if(!$info_level){
            $result->status->setNeedInfoLevel();
            $this->ajaxReturn($result);
            return;
        }
        $query = new QueryUtils();
        $app_db_item = $query->getWebAppInfo($app_id);
        if($app_db_item){
            $app_item = WebAppItem::initFromDataBase($app_db_item);
            //获取参数字符串
            $app_name = $app_item->app_name;
            $app_thumb = $app_item->app_thumb;
            $app_info = $app_item->app_info;
            $is_auth = $app_item->is_auth;
            $app_id = $app_item->app_id;
            //添加数据表字段
            $token = $query->addNewAuthToken($app_id, $redirect_uri);
            if($token){
                //信息获取等级
                if($info_level){
                    //完整信息 需授权
                    $url = "hitv://hitv_need_auth?app_name={$app_name}&app_thumb={$app_thumb}&app_info={$app_info}&app_id={$app_id}&is_auth={$is_auth}&token={$token}";
                }else{
                    //只有open_id
                    $url = "hitv://hitv_need_openid?app_id={$app_id}&token={$token}";
                }
                header("Location: $url");
                return;
            }else{
                $result->status->setErrorUnknown();
            }
        }else{
            $result->status->setWebAppNotFound();
        }
        $this->ajaxReturn($result);
    }

    function hitv_auth(){
        $result->status = new StatusObject();
        $user_id = I(AuthController::USER_ID);
        //$user_token = ;
        $app_id = I(AuthController::APP_ID, '');
        $token = I(AuthController::AUTH_TOKEN, '');
        //TODO:token参数检测
        if(!$app_id){
            $result->status->setNeedWebAppId();
            $this->ajaxReturn($result);
            return;
        }
        if(!$token){
            $result->status->setNeedAuthToken();
            $this->ajaxReturn($result);
            return;
        }
        if(!$user_id){
            $result->status->setNeedUserId();
            $this->ajaxReturn($result);
            return;
        }
        $query = new QueryUtils();
        //获取用户
        $user_info = $query->getUser($user_id);
        if($user_info){
            //TODO:检查TOKEN
            //获取app信息
            $app_info = $query->getWebAppInfo($app_id);
            if($app_info){
                //获取token信息
                $token_info = $query->getAuthTokenInfo($app_id, $token);
                if($token_info){
                    $now_time = new \DateTime();
                    $now_time_stamp = $now_time->getTimestamp();
                    //if($token_info['expired_time'] < $now_time_stamp){
                        //TODO:token 超时
                    //    $result->status->message = "expired";
                    //}else{

                        //TODO:注销此次token
                        //取出url,写入授权信息
                        $redirect_uri = $token_info['redirect_uri'];
                        $auth_code = $query->addNewAuthCode($app_id, $user_id);
                        if($auth_code){
                            $result->result->auth_code = $auth_code;
                            $result->result->redirect_uri = $app_info['security_domain']."/".$redirect_uri;
                        }else{
                            $result->status->setErrorUnknown();
                        }

                   // }
                }else{
                    $result->status->setAuthTokenNotFound();
                }
            }else{
                $result->status->setWebAppNotFound();
            }
        }else{
            $result->status->setUserNotFound();
        }
        //$redirect_uri = I(AuthController::REDIRECT_URI, '');
        $this->ajaxReturn($result);
    }

    function access_token(){
        $result->status = new StatusObject();
        $app_id = I(AuthController::APP_ID, '');
        $app_secret = I(AuthController::APP_SECRET, '');
        $access_code = I(AuthController::ACCESS_CODE, '');
        if(!$app_id){
            $result->status->setNeedWebAppId();
            $this->ajaxReturn($result);
            return;
        }
        if(!$app_secret){
            $result->status->setNeedAppSecret();
            $this->ajaxReturn($result);
            return;
        }
        if(!$access_code){
            $result->status->setNeedAcceccCode();
            $this->ajaxReturn($result);
            return;
        }
        $query = new QueryUtils();
        $app_db_item = $query->getWebAppFullInfo($app_id);
        //查询app信息
        if($app_db_item){
            $app_item = WebAppItem::initFromDataBase($app_db_item);
            //校验secret
            if($app_item->app_secret == $app_secret){
                $code_info = $query->getAccessCodeInfo($app_id, $access_code);
                if($code_info){
                    //TODO:注销code
                    //生成access token
                    $user_id = $code_info['user_id'];
                    $access_token_item = AccessTokenItem::create($user_id, $app_id);
                    $access_token_id = $query->addAccessTokenByItem($access_token_item);//$app_id,$user_id,$access_token,$refresh_token,$create_time,$expired_time,$open_id);
                    if($access_token_id){
                        $result->result->access_token = $access_token_item->access_token;
                        $result->result->refresh_token = $access_token_item->refresh_token;
                        $result->result->open_id = $access_token_item->open_id;
                    }else{
                        $result->status->setErrorUnknown();
                    }
                }else{
                    $result->status->setAccessCodeNotFound();
                }
            }else{
                $result->status->setAppSecretNotEqual();
            }
        }else{
            $result->status->setWebAppNotFound();
        }
        $this->ajaxReturn($result);
    }

    function user_info(){
        $result->status = new StatusObject();
        $access_token = I(AuthController::ACCESS_TOKEN, '');
        $open_id = I(AuthController::OPEN_ID, '');
        //TODO:参数检测
        $query = new QueryUtils();
        $token_info = $query->getUserInfoByAccessToken($access_token, $open_id);
        if($token_info){
            //TODO:超时判断
            $user_id = $token_info['user_id'];
            $user_info = $query->getUser($user_id);
            if($user_info){
                $result->user = $user_info;
            }else{
                //TODO:查不到用户
            }
        }else{
            //TODO:查不到
        }
        $this->ajaxReturn($result);
    }
}