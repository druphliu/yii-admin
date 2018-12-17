<?php

namespace admin\modules\rbac\components;

use admin\models\HsdkSysmodule;
use admin\modules\rbac\models\Menu;
use Yii;

/**
 * Description of Hsysmodule
 *
 * @author Administrator
 */
class Hsysmodule
{

    private static $instance;
    private $sessionKey = 'sys:module';

    public static function getInstance()
    {
        if (empty(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    private function getAllMoudle()
    {
        return HsdkSysmodule::find()->where(['status' => HsdkSysmodule::STATUS_ON])->asArray()->all();
    }

    /**
     * 根据权限加载模块
     */
    public function getMoudel()
    {
        $userId = Yii::$app->user->id;
        $sessionKey = sprintf('%s:%s', $this->sessionKey, $userId);
        //如果session存在就读取session
        if (Yii::$app->session->get($sessionKey)) {
            return Yii::$app->session->get($sessionKey);
        } else {
            if (Yii::$app->user->identity->username == 'admin') {//管理员返回全部模块
                $data = $this->getAllMoudle();
            } else {
                $authManager = Yii::$app->getAuthManager();
                $role = $authManager->getRolesByUser($userId);
                //var_dump($role);exit;
                $permissions = $authManager->getPermissionsByUser($userId);//获取用户权限
                if (empty($permissions)) {
                    return [];
                }
                
                $permissionsArr = array_unique(array_keys($permissions));
                $route = [];
                foreach ($permissionsArr as $p) {
                    $en = explode('/', trim($p, '/'));
                    array_pop($en);
                    $route[] = join($en, '/') . '/';
                }
                $route = array_unique($route);
                $permissionQuery = Menu::find()->where(['status' => 1]);
                $permissionQuery->andFilterWhere(['or like', 'url', $route]); //模糊查找所有符合权限菜单
                $list = $permissionQuery->asArray()->all(); //有权限的菜单
                if (!$list) {
                    return [];
                }
                $type = array_unique(array_column($list, 'type'));//菜单所属模块

                $query = HsdkSysmodule::find()->where(['status' => HsdkSysmodule::STATUS_ON, 'id' => $type]);
                $data = $query->asArray()->all();
            }
            Yii::$app->session->set($sessionKey, $data);
            return $data;
        }
    }

}
