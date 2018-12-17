<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 2018/12/10
 * Time: 6:38 PM
 */

namespace backend\components;

use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Response;

class Controller extends \yii\web\Controller
{
    /**
     * Ajax方式返回数据到客户端
     * @access protected
     * @param mixed $data 要返回的数据
     * @param String $type AJAX返回数据格式
     * @param int $json_option 传递给json_encode的option参数
     * @return void
     */
    protected function ajaxReturn($data,$type='',$json_option=0) {

        if(empty($type)) $type  =  "JSON";
        switch (strtoupper($type)){
            case 'JSON' :
                Yii::$app->response->format = Response::FORMAT_JSON;
                Yii::$app->response->getHeaders()->set('Content-length', strlen(Json::encode($data)));
                // 返回JSON数据格式到客户端 包含状态信息
                return $data;
            //return $this->renderAjax('/layouts/ajax',['data'=>$data]);
            case 'XML'  :
                // 返回xml格式数据
                //header('Content-Type:text/xml; charset=utf-8');
                //exit(xml_encode($data));
                Yii::$app->response->format = Response::FORMAT_XML;
                return $data;
            case 'JSONP':
                // 返回JSON数据格式到客户端 包含状态信息
                Yii::$app->response->format = Response::FORMAT_JSONP;
                Yii::$app->response->getHeaders()->set('Content-length', strlen(Json::encode($data)));
                $handler  =   isset($_GET["callback"]) ? $_GET["callback"] : "jsonpReturn";
                return($handler.'('.json_encode($data,$json_option).');');
            case 'EVAL' :
                // 返回可执行的js脚本
                Yii::$app->response->format = Response::FORMAT_HTML;
                return $data;
            default:
        }
    }

    protected function success($data){
        return $this->ajaxReturn(['code'=>0,'msg'=>'','data'=>$data]);
    }

    protected function error($code,$msg,$data=[]){
        return $this->ajaxReturn(['code'=>$code,'msg'=>$msg,'data'=>$data]);
    }

    //检查登录
    protected function checkLogin() {
        if (Yii::$app->request->isAjax) {
            if (Yii::$app->user->isGuest) {
                return $this->error(1001,'未登录!');
            }
        } else {
            if (Yii::$app->user->isGuest) {
                header('Location:' . Url::to(['/login/index']));
                exit(0);
            }
        }
    }

    protected function getFlash($type, $default = null)
    {
        $app = Yii::$app;
        $flash = $app->session->getFlash($type, $default);
        if ($flash === null) {
            $flash = [];
        }
        if (is_string($flash)) {
            $flash = [$flash];
        }
        return $flash;
    }

    protected function setFlash($type, $message, $append = true)
    {
        if ($append) {
            $flash = $this->getFlash($type);
            if (is_string($message)) {
                $flash[] = $message;
            } else if (is_array($message)) {
                $flash = array_merge($flash, $message);
            } else if ($message === null) {
                $flash = null;
            }

            $message = $flash;
        }
        $app = Yii::$app;
        $app->session->setFlash($type, $message);
    }

}