<?php

/**
 * 模块功能: RBAC后台基础类
 */

namespace admin\modules\rbac\components;

use admin\components\RbacBaseController;
use Yii;

class RbacController extends RbacBaseController
{

    public $layout = '/main.php';

    //设置标题
    protected function setTitle($title)
    {
        $this->getView()->title = $title;
    }

}
