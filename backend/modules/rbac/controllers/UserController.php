<?php

namespace backend\modules\rbac\controllers;

use admin\models\HsdkUserAdmin;
use admin\components\AdminBaseController;
use Yii;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

/** 用户管理
 * Class PermissionController
 * @package admin\modules\jrbac\controllers
 */
class UserController extends AdminBaseController {

    /** 查看用户列表 */
    public function actionIndex() {
        $model = new HsdkUserAdmin();
        $dataProvider = $model->seacher();
        return $this->render("index.php", [
                    'dataProvider' => $dataProvider,
                    'filterModel' => $model
        ]);
    }

    /** 创建用户 */
    public function actionCreate() {
        $model = new HsdkUserAdmin();
        $auth = Yii::$app->getAuthManager();
        if (Yii::$app->request->isPost) {
            $model->scenario = "create";
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->validate()) {
                if (!isset($post['HsdkUserAdmin']['myrole']) || empty($post['HsdkUserAdmin']['myrole'])) {
                    Yii::$app->session->setFlash('error', '请选择一个角色');
                } else {
                    if (!preg_match("/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,10}$/",$post['HsdkUserAdmin']['password_hash'])){ 
                        Yii::$app->session->setFlash('error', '密码必须为大小写和数字组合，不能使用特殊字符，长度在8-10之间');
                        return $this->render("create", [
                            'model' => $model,
                            'roles' => $roles = ArrayHelper::map($auth->getRoles(), "name", "description"),
                            'my_role' => []
                        ]);
                    }
                    
                    $model->auth_key = Yii::$app->security->generateRandomString();
                    $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($model->password_hash);
                    $model->phone = $post['HsdkUserAdmin']['phone'];
                    $model->created_at = time();
                    $model->updated_at = time();
                    if ($model->save(false)) {
                        //给创建的用户的权限
                        $uid = $model->getPrimaryKey();
                        $roles = $post['HsdkUserAdmin']['myrole'];
                        foreach ($roles as $r) {
                            $role = $auth->getRole($r);
                            if ($role) {
                                $auth->assign($role, $uid);
                            }
                        }
                        $this->redirect(['index']);
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', array_shift(array_shift(array_values($model->errors))));
            }
        }
        $roles = ArrayHelper::map($auth->getRoles(), "name", "description");
        return $this->render("create", [
                    'model' => $model,
                    'roles' => $roles,
                    'my_role' => []
        ]);
    }

    /** 更新用户信息 */
    public function actionUpdate($id) {
        $auth = Yii::$app->getAuthManager();
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            $model->scenario = "update";
            $post = Yii::$app->request->post();
            if ($model->load($post) && $model->validate()) {
                if (!isset($post['HsdkUserAdmin']['myrole']) || empty($post['HsdkUserAdmin']['myrole'])) {
                    Yii::$app->session->setFlash('error', '请选择一个角色');
                } else {
                    //$model->phone = $post['HsdkUserAdmin']['phone'];
                    $model->updated_at = time();
                    if ($model->save(false)) {
                        $uid = $model->getPrimaryKey();
                        $auth = Yii::$app->getAuthManager();
                        $auth->revokeAll($uid);
                        $roles = $post['HsdkUserAdmin']['myrole'];
                        foreach ($roles as $r) {
                            $role = $auth->getRole($r);
                            if ($role) {
                                $auth->assign($role, $uid);
                            }
                        }
                        return $this->redirect(['index']);
                    }
                }
            } else {
                Yii::$app->session->setFlash('error', array_shift(array_shift(array_values($model->errors))));
            }
        }
        $keyValue = array_keys($auth->getRolesByUser($id));
        $model->myrole = $keyValue;
        $roles = ArrayHelper::map($auth->getRoles(), "name", "description");
        return $this->render("update", [
                    'model' => $model,
                    'roles' => $roles
        ]);
    }

    /** 删除用户信息 */
    public function actionDelete($id) {

        $model = $this->findModel($id);
        $model->status = -1;
        $model->save(false);
        return $this->redirect(["index"]);
    }

    /**
     * 修改密码
     * @param type $id
     * @return type
     */
    public function actionModpwd() {
        $id = Yii::$app->request->get('id');
        $id = $id?$id:Yii::$app->user->id;
        $model = $this->findModel($id);
        if (Yii::$app->request->isPost) {
            $post = Yii::$app->request->post('HsdkUserAdmin');
            $model->scenario = 'modpwd';
            $model->password_hash = $post['password_hash'];
            $model->rep_password_hash = $post['rep_password_hash'];
            if ($model->validate()) {
                $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($model->password_hash);
                $model->updated_at = time();
                $model->save(false);
                Yii::$app->session->setFlash('success', '修改成功');
            } else {
                Yii::$app->session->setFlash('error', array_shift(array_shift(array_values($model->errors))));
            }
        }
        return $this->render("modpwd", [
                    'model' => $model
        ]);
    }

    /**
     * 用户游戏管理
     * @param type $id
     * @return type
     */
    public function actionGames($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->game_id) {
                $model->game_id = join($model->game_id, ',');
            } else {
                $model->game_id = '';
            }
            $model->save(false);
            Yii::$app->session->setFlash('success', '修改成功');
        }
        if ($model->game_id && is_string($model->game_id)) {
            $model->game_id = explode(',', trim($model->game_id));
        }

        return $this->render("games", [
                    'model' => $model
        ]);
    }

    protected function findModel($id) {
        if (($model = HsdkUserAdmin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
