<?php

namespace backend\modules\rbac\controllers;

use backend\modules\rbac\models\AdminMenu;
use backend\modules\rbac\components\Tree;
use backend\modules\rbac\components\HMenu;
use backend\components\AdminController;
use Yii;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

/** 菜单管理
 * MenuController implements the CRUD actions for Menu model.
 */
class MenuController extends AdminController {

    /** 展示菜单
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex() {
        $searchModel = new AdminMenu();
        $query = $searchModel->find()->alias('m')->select('m.name as title,m.icon,m.url as jump,m.id,m.pid')
                        ->where(['m.status' => 1]);
        $name = Yii::$app->request->get('name');
        if($name){
            $query->andFilterWhere(['like','m.name',$name]);
        }
        $query->orderBy('pid asc,sort asc,id asc');
        $arr = $query->asArray()->all();
        $treeObj = new Tree(\yii\helpers\ArrayHelper::toArray($arr));
        $treeObj->nbsp = '&nbsp;&nbsp;&nbsp;';
        $list = $treeObj->getTreeArray('id','pid','list');
        return $this->success($list);
    }

    /**
     * 菜单列表
     */
    public function actionList() {
        $searchModel = new AdminMenu();
        $query = $searchModel->find()->alias('m')
            ->where(['m.status' => 1]);
        $name = Yii::$app->request->get('name');
        if($name){
            $query->andFilterWhere(['like','m.name',$name]);
        }
        $query->orderBy('pid asc,sort asc,id asc');
        $arr = $query->asArray()->all();
        $treeObj = new Tree(\yii\helpers\ArrayHelper::toArray($arr));
        $treeObj->nbsp = '&nbsp;&nbsp;&nbsp;';
        $list = $treeObj->getGridTree();
        return $this->success($list);
    }

    /** 添加菜单
     * Creates a new Menu model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Menu();

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '添加成功');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', array_shift(array_shift(array_values($model->errors))));
            }
        }
        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /** 编辑菜单
     * Updates an existing Menu model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '保存成功');
                return $this->redirect(['index']);
            } else {
                Yii::$app->session->setFlash('error', array_shift(array_shift(array_values($model->errors))));
            }
        }
        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /** 删除菜单
     * Deletes an existing Menu model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $subCount = Menu::find()->where('`pid`=:id', [':id' => $id])->count();
        if ($subCount == 0) { //不包含子菜单时 方可删除
            $model = $this->findModel($id);
            $model->status = 0;
            $model->save(false);
        }
        return $this->redirect(['index']);
    }

    protected function findModel($id) {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
