<?php

/**
 * 模块功能: 路由功能模块
 */

namespace admin\modules\rbac\components;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use admin\components\common\Common;

class Route extends \yii\base\Object {

    private $cache;
    private $cacheDuration = 1024;

    const CACHE_TAG = 'rbac.admin.route';

    private $my_Controller = ["LoginController.php", "PublicController.php", 'SiteController.php', 'BaseController.php', 'BeindexController.php']; //不需要扫描的控制权

    public function init() {
        $this->cache = Yii::$app->cache;
    }

    //获取当前项目的所有路由
    public function getAppRoutes() {

        $clearExistPermissions = true; //是否清理现有资源列表
        //为true时  会清理以 / 开头的  无有效path匹配的资源

        $permissionList = [];

        //默认模块控制器权限列表 -- Start
        $moduleControllerList = [];
        $f_list = scandir(\Yii::$app->controllerPath);
        foreach ($f_list as $f_item) {
            if (StringHelper::endsWith($f_item, 'Controller.php') && !in_array($f_item, $this->my_Controller)) {
                $fClassName = explode('.php', $f_item)[0];
                $moduleControllerList[] = \Yii::$app->controllerNamespace . '\\' . $fClassName;
            }
        }

        if ($moduleControllerList) {
            $permissions = HAction::getInstance()->getPermissionList($moduleControllerList, false);
            if ($permissions) {
                $permissionList = ArrayHelper::merge($permissionList, $permissions);
            }
        }
        //默认模块控制器权限列表 -- End
        //自定义模块 -- Start
        $modules = \Yii::$app->getModules(); //配置中的模块

        $excludeModules = ['gii', 'debug', 'ajaxsdk', 'sdkerror']; //排除模块
        foreach ($modules as $moduleId => $module) {
            if (in_array($moduleId, $excludeModules)) {
                continue;
            }
            if (!$module instanceof Module) {
                $module = \Yii::$app->getModule($moduleId);
            }
            unset($module->module);
            $moduleControllerList = [];
            $f_list = scandir($module->controllerPath);
            foreach ($f_list as $f_item) {
                //var_dump($f_item);
                if (StringHelper::endsWith($f_item, 'Controller.php') && !in_array($f_item, $this->my_Controller)) {
                    $fClassName = explode('.php', $f_item)[0];
                    $moduleControllerList[] = $module->controllerNamespace . '\\' . $fClassName;
                }
            }
            $permissions = [];
            if ($moduleControllerList) {
                $permissions = HAction::getInstance()->getPermissionList($moduleControllerList, false);
            }
            $permissionList = ArrayHelper::merge($permissionList, $permissions);
        }
        // var_dump($permissionList);
        $configPermissionList = Common::getVirtualMenu();
        $configPermissonData = array();
        if ($configPermissionList) {
            foreach ($configPermissionList as $k => $c) {
                $cpdata = array(
                    'path' => trim($k, '.html'),
                    'description' => $c
                );
                array_push($configPermissonData, $cpdata);
            }
        }
        $permissionList = ArrayHelper::merge($permissionList, $configPermissonData);

        $auth = \Yii::$app->getAuthManager();

        $existPermissions = $auth->getPermissions();

        if ($clearExistPermissions && isset($existPermissions)) {
            //清理符合初始化规则的无效权限资源
            $existPermissionNames = ArrayHelper::getColumn($existPermissions, 'name');
            $permissionNames = ArrayHelper::getColumn($permissionList, 'path');
            foreach ($existPermissionNames as $existPermissionName) {
                if (StringHelper::startsWith($existPermissionName, '/') && !in_array($existPermissionName, $permissionNames)) {
                    $auth->remove($existPermissions[$existPermissionName]);
                }
            }
        }

        foreach ($permissionList as $permission) {
            if (isset($existPermissions[$permission['path']])) {
                //更新已有资源
                if ($existPermissions[$permission['path']]->description != $permission['description']) {
                    $existPermissions[$permission['path']]->description = $permission['description'];
                    $auth->update($permission['path'], $existPermissions[$permission['path']]);
                }
            } else {
                //添加新资源
                $newPermission = $auth->createPermission($permission['path']);
                $newPermission->description = $permission['description'];
                $auth->add($newPermission);
            }
        }
        //父子关系表，数据判断
        file_put_contents(\Yii::getAlias('@runtime/jrbac-permission-init.lock'), time());
    }

}
