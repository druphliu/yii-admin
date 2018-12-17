<?php

namespace backend\modules\rbac\controllers;

use admin\components\AdminBaseController;
use admin\modules\rbac\models\AuthItem;
use admin\modules\rbac\components\Route;
use Yii;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use admin\modules\rbac\components\Tree;
use admin\modules\rbac\components\HMenu;

/** 角色管理
 * Class PermissionController
 * @package admin\modules\jrbac\controllers
 */
class RoleController extends AdminBaseController {

    /** 初始化权限 * */
    public function actionInit() {

        if (Yii::$app->request->isAjax) {
            $route = new Route();
            $items = $route->getAppRoutes();
            exit("1");
        }
    }

    /** 查看角色列表 */
    public function actionIndex() {
        $auth = Yii::$app->getAuthManager();
        $items = $auth->getRoles();
        $dataProvider = new ArrayDataProvider([
            'allModels' => $items,
            'pagination' => [
                'pageSize' => 30,
            ]
        ]);
        return $this->render("index.php", [
                    'dataProvider' => $dataProvider,
        ]);
    }

    /** 创建角色 */
    public function actionCreate() {
        $model = new AuthItem();
        if (Yii::$app->request->isPost) {
            if ($model->load(Yii::$app->request->post())) {
                $auth = Yii::$app->getAuthManager();
                $role = $auth->createRole($model->name);
                $role->description = $model->description;
                if ($auth->add($role)) {
                    return $this->redirect(Yii::$app->urlManager->createUrl("rbac/role/index")); //登录成功，跳转到首页
                } else {
                    Yii::$app->session->setFlash('error', array_shift(array_shift(array_values($auth->errors))));
                }
            }else{
                Yii::$app->session->setFlash('error', array_shift(array_shift(array_values($model->errors))));
            }
        }
        return $this->render("create", ['model' => $model]);
    }

    /** 删除角色 */
    public function actionDelete($id) {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getRole($id);
        $auth->remove($role);
        return $this->redirect(['index']);
    }

    /** 编辑角色信息 */
    public function actionUpdate($id) {
        $auth = Yii::$app->authManager;
        $model = AuthItem::findOne($id);
        if ($model->load(Yii::$app->getRequest()->post())) {
            $role = $auth->getRole($id);
            $role->name = $model->name;
            $role->description = $model->description;
            if ($auth->update($id, $role)) {
                return $this->redirect(['index']);
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * 查看并设置权限
     * @param type $id
     * @return type
     */
    public function actionView($id) {
        $authManager = Yii::$app->getAuthManager();
        if (Yii::$app->request->isPost) {
            if (!$role = $authManager->getRole($id)) {
                Yii::$app->session->setFlash('error', '角色不存在');
            } else {
                $list = Yii::$app->request->post('rules', []);
                $existArray = $authManager->getChildren($id);
                foreach ($existArray as $existItem) {
                    if (!in_array($existItem->name, $list)) {
                        $authManager->removeChild($role, $existItem);
                    }
                }
                foreach ($list as $parentName) {
                    $parentItem = $authManager->getPermission($parentName);
                    if ($parentItem && !in_array($parentItem, $existArray) && $authManager->canAddChild($role, $parentItem)) {
                        $authManager->addChild($role, $parentItem);
                    }
                }
                Yii::$app->session->setFlash('success', '操作成功');
            }
        }
        $hMenuModel = HMenu::getInstance();
        $arr = $hMenuModel->getMenuAll();
        $moudels = $hMenuModel->getMoudel();
        $result = $mlist = array();
        foreach ($arr as $m) {
            $roteArr = explode('/', trim($m['url']));
            array_pop($roteArr);
            $indexKey = join($roteArr, '/');
            $mlist[$indexKey] = $m['id'];
        }
        $permissions = $authManager->getPermissions();
        foreach ($permissions as $key => $role) {
            if ($role->name[0] === '/') {
                $pidArr = explode('/', trim($role->name, '/'));
                array_pop($pidArr);
                $pikey = join($pidArr, '/');
                if (isset($mlist[$pikey])) {
                    $result[$key]['id'] = $role->name;
                    $result[$key]['pid'] = $mlist[$pikey];
                    $result[$key]['url'] = $role->name;
                    $result[$key]["name"] = $role->description ? $role->description : $role->name;
                    $result[$key]['type'] = $role->type;
                }
            }
        }
        $arr = array_merge_recursive($arr, $result);
        $treeObj = new Tree($arr);
        $authRules = $authManager->getChildren($id);
        $authRules = array_keys($authRules);
        return $this->render('view', [
                    'treeArr' => $treeObj->getTreeArray(),
                    'authRules' => $authRules,
                    'role' => $id,
                    'moudels' => $moudels
        ]);
    }

}
