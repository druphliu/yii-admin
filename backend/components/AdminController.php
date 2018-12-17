<?php
/**
 * Created by PhpStorm.
 * User: hero
 * Date: 2018/12/17
 * Time: 12:02 AM
 */

namespace backend\components;


use Yii;

class AdminController extends Controller
{
    public function beforeAction($action) {
        $this->checkLogin();
        if (Yii::$app->user->identity->username == "admin") {
            return parent::beforeAction($action);
        }

        $auth = Yii::$app->getAuthManager();

        $permission = "/" . (Yii::$app->requestedRoute ? Yii::$app->requestedRoute : "index/index");
        $flag = $auth->checkAccess(Yii::$app->user->id, $permission);
        if ($flag) {
            return parent::beforeAction($action);
        }
        if (Yii::$app->request->isAjax) {
            return $this->error('1002','对不起，您现在还没获此操作的权限');
        } else {
            Yii::$app->session->setFlash('error', '对不起，您现在还没获此操作的权限');
            return $this->redirect(Yii::$app->urlManager->createUrl("base/index")); //没有权限 退出登录
        }
    }

}