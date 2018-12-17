<?php

/**
 * 模块功能: 菜单功能模块
 * author: 徐鹏飞
 * email: 503186749@qq.com
 * Date: 2017/1/1 0001 下午 10:57
 */

namespace backend\modules\rbac\components;

use admin\modules\rbac\models\Menu;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\StringHelper;
use admin\models\HsdkSysmodule;

class HMenu {

    private static $instance;

    public static function getInstance() {
        if (empty(self::$instance))
            self::$instance = new self();
        return self::$instance;
    }

    //jrbac模块中的样式前缀
    public $iconPrefix = ' '; //bootstrap.css 可修改为font-awesome字体,$icons列表中为公共样式
    public $icons = [
        'fa fa-cogs', 'fa fa-group', 'fa fa-hdd-o', 'fa fa-home',
        'fa fa-shopping-cart', 'fa fa-suitcase', 'fa fa-gear', 'fa fa-filter',
        'fa fa-rss', 'fa fa-credit-card', 'fa fa-truck', 'fa fa-archive',
        "fa fa-bug", "fa fa-angle-right", "fa fa-calendar-o",
    ];
    public $defaultIcon = 'record';

    public function getMenuIconOptionItems() {
        $iconArray = [];
        foreach ($this->icons as $icon) {
            $iconArray[$icon] = '<i class="' . $this->iconPrefix . $icon . '">&nbsp;&nbsp;</i>';
        }
        return $iconArray;
    }

    public function getMenu($type) {
        return $this->getItemsList($type);
    }

    /**
     * 系统模块
     * @return type
     */
    public function getMoudel() {
        return HsdkSysmodule::find()->select(['name', 'id'])->where(['status' => HsdkSysmodule::STATUS_ON])->indexBy('id')->column();
    }

    /**
     * 获取菜单
     * @return type
     */
    public function getItemsList($type) {
        if (Yii::$app->user->identity->username == "admin") {
            return $this->getAdminRole($type);
        } else {
            return $this->getChildRole($type);
        }
    }

    /**
     * 拼装父级菜单的url（子节点的控制器）
     * @param type $url
     * @param type $curl
     * @return type
     */
    private function makePmenuUrl($url, $curl) {
        if (!$curl) {
            return $url;
        }
        $curlArr = explode('/', trim($curl, '/'));
        if (count($curlArr) == 3) {
            $controllerId = $curlArr[1];
        } else {
            $controllerId = $curlArr[0];
        }
        $urlArr = explode('/', trim($url, '/'));
        if (!in_array($controllerId, $urlArr)) {
            array_push($urlArr, $controllerId);
        }
        return join($urlArr, '/');
    }

    /**
     * 超管菜单
     * @return type
     */
    public function getAdminRole($type) {
        $menuAll = $this->getMenuAll($type);
        //var_dump($menuAll);exit;
        return $this->makeTreeArray($menuAll);
    }

    protected function makeTreeArray($array) {
        $list = array();
        $array = array_values($array);
        foreach ($array as $key => $value) {
            if ($value['pid'] == 0) {
                $array[$key] = $this->makeItem($value);
                $list[] = &$array[$key];
            }
            foreach ($array as $k => $v) {
                if ($v['pid'] == $value['id']) {
                    $array[$k] = $this->makeItem($v);
                    $array[$key]['url'][0] = $this->makePmenuUrl($array[$key]['url'][0], $array[$k]['url'][0]);
                    $array[$key]['children'][] = &$array[$k];
                }
            }
        }
        return $list;
    }

    /**
     * 子管理角色菜单
     * @return type
     */
    public function getChildRole($type) {
        $auth = Yii::$app->getAuthManager();
        $role = $auth->getPermissionsByUser(Yii::$app->user->id); //获取登录用户的许可权限
        $permission = []; //权限队列（首页自动赋予权限）
        if (!empty($role)) {
            foreach ($role as $r) {
                $routeArr = explode('/', trim($r->name, '/'));
                array_pop($routeArr);
                $permission[] = join($routeArr, '/');
            }
        }
        $permission = array_unique($permission);
        //var_dump($permission);exit;
        $permissionQuery = Menu::find()->where(['status' => 1, 'type' => $type]);
        $permissionQuery->andFilterWhere(['or like', 'url', $permission]); //模糊查找所有符合权限菜单
        $list = $permissionQuery->asArray()->all(); //有权限的菜单
        $permissionMenuIds = array_column($list, 'id'); //有权限的菜单id

        $menuAll = $this->getMenuAll($type);
        $ids = array(); //创建权限菜单队列
        foreach ($permissionMenuIds as $mid) {
            $ids = array_merge($ids, $this->getMenuPids($mid, $menuAll)); //根据拥有的权限获取所有菜单的id集合
        }
        $ids = array_unique($ids); //去重
        $item = [];
        //拼装数组结构并对菜单节点进行处理
        foreach ($menuAll as $key => $m) {
            if ($m['pid'] == 0 && in_array($m['id'], $ids)) {//只取pid=0即顶级菜单
                $menuAll[$key] = $this->makeItem($m);
                $item[] = &$menuAll[$key];
            }
            foreach ($menuAll as $ck => $cm) {//添加子菜单
                if ($cm['pid'] == $m['id'] && in_array($cm['id'], $ids)) {
                    $menuAll[$ck] = $this->makeItem($menuAll[$ck]);
                    $menuAll[$key]['url'][0] = $this->makePmenuUrl($menuAll[$key]['url'][0], $menuAll[$ck]['url'][0]);
                    $menuAll[$key]['children'][] = &$menuAll[$ck];
                }
            }
        }
        //var_dump($item);
        return $item;
    }

    /**
     * 处理菜单（兼容视图页面结构）
     * @param type $item
     * @return string
     */
    private function makeItem($item) {
        if (strpos($item['url'], '://')) { //外链判断
            $item['icon'] = $this->iconPrefix . " " . (isset($item['icon']) && $item['icon'] ? $item['icon'] : $this->defaultIcon);
        } else {
            $item['url'] = [$item['url']];
            $item['icon'] = $this->iconPrefix . " " . (isset($item['icon']) && $item['icon'] ? $item['icon'] : $this->defaultIcon);
        }
        return $item;
    }

    /**
     * 子节点获取父级方法（返回一列id集合）
     * @param type $id
     * @param array $menu
     * @return array
     */
    private function getMenuPids($id, Array $menu) {
        $pids = [$id];
        do {
            if (isset($menu[$id])) {
                $pid = $menu[$id]['pid'];
                array_push($pids, $pid);
                $id = $pid;
            }
        } while (isset($menu[$id]));
        return $pids;
    }

    public function getMenuAll($type = '') {
        $query = Menu::find()->where(['status' => 1]);
        $query->andFilterWhere(['type' => $type]);
        $query->indexBy('id')->orderBy('type asc,pid asc,sort asc')->asArray();
        return $query->all();
    }

    public function getMenuByType($type) {
        $query = Menu::find()->where(['status' => 1]);
        $query->andFilterWhere(['type' => $type]);
        $query->orderBy('type asc,pid asc,sort asc')->asArray();
        return $query->all();
    }

    public function getOptionList($pid = 0, $level = 0, $depth = 0) {
        $query = Menu::find()->where('`status`=1 and `pid`=:pid', [
                    ':pid' => $pid
                ])->orderBy('`sort` asc');
        $items = $query->asArray()->all();
        $list = [];
        $subPrefix = '';
        for ($i = 0; $i < $level; $i++) {
            $subPrefix .= '--';
        }
        foreach ($items as $k => $item) {
            $list[] = [
                'id' => $item['id'],
                'name' => $subPrefix . $item['name']
            ];
            if (!$depth || ($level + 1) < $depth) {
                $sub = $this->getOptionList($item['id'], $level + 1, $depth);
                foreach ($sub as $subItem) {
                    $list[] = $subItem;
                }
            }
        }
        return $list;
    }

    public function getPidFilter($pid = 0, $level = 0) {
        $pMenuItems = $this->getOptionList($pid, $level, 1);
        $pMenuList = [];
        foreach ($pMenuItems as $item) {
            $pMenuList[$item['id']] = $item['name'];
        }
        return $pMenuList;
    }

    //是否允许访问url
    private function checkAllow($user) {
        if (Yii::$app->user->identity->username == "admin") {
            return true;
        }
        $website_id = Yii::$app->session->get("website_id");
        if ($website_id) {
            if ($user) {
                $auth = Yii::$app->getAuthManager();
                return $auth->checkAccess(Yii::$app->user->id, $user);
            }
            return true;
        } else {
            return false;
        }
    }

    /**
     * 左侧菜单的展开与高亮显示(子节点)
     * @param type $uri
     * @param type $controllerId
     */
    public static function checkChild($uri, $controllerId) {
        $uriArr = explode('/', trim($uri, '/'));
        if (count($uriArr) == 3) {
            $c = $uriArr[1];
        } else {
            $c = $uriArr[0];
        }

        $action = substr(strrchr(trim($uri, '/'), '/'), 1);
        if ($c == $controllerId) {
            if ($action == Yii::$app->controller->action->id) {
                return true;
            }
            return false;
        }
        return false;
    }

    /**
     * 检测父节点
     * @param type $contrllerId
     * @param type $url
     * @return boolean
     */
    public static function checkParent($contrllerId, $url) {
        $urlArr = explode('/', trim($url, '/'));
        if (in_array($contrllerId, $urlArr)) {
            return true;
        }
        return false;
    }

}
