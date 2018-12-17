<?php

/**
 * 模块功能:
 * author: 徐鹏飞
 * email: 503186749@qq.com
 * Date: 2017/1/2 0002 上午 2:23
 */
use common\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "用户列表";
?>
<div class="page-content-wrapper">
    <div class="page-content">
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a id="home" href="<?= Url::to(['/base/index']) ?>">首页</a><i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?= Url::to(['/rbac/user/index']) ?>">用户管理</a><i class="fa fa-circle"></i>
            </li>
            <li class="active">
                管理员列表
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>管理员列表
                        </div>
                        <div class="actions">
                            <a class="btn btn-default btn-sm" href="<?= Url::toRoute(['create']); ?>">
                                <i class="fa fa-pencil"></i>
                                新增
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar" sf-controller="app">
                            <?php echo $this->render('_search', ['model' => $filterModel]); ?>
                        </div>
                        <div class="table-scrollable">
                            <?php
                            echo GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    'username',
                                    [
                                        'attribute' => 'status',
                                        'format' => 'raw',
                                        'value' => function($model) {
                                    $status = [
                                        '10' => '<span class="label label-success">正常</span>',
                                        '0' => '<span class="label label-danger">禁用</span>',
                                    ];
                                    return $status[$model->status];
                                }
                                    ],
                                    [
                                        'header' => '<a>角色名称</a>',
                                        'value' => function($model) {
                                    $auth = Yii::$app->getAuthManager();
                                    $keyValue = array_keys($auth->getRolesByUser($model->id));
                                    $jname = array();
                                    foreach($keyValue as $k){
                                        $n = $auth->getRole($k)->description;
                                        array_push($jname,$n);
                                    }
                                    return join(' | ',$jname);
                                }
                                    ],
                                    [
                                        'attribute' => 'created_at',
                                        'value' => function($model) {
                                    return date('Y-m-d H:i:s', $model->created_at);
                                }
                                    ],
                                    [
                                        'attribute' => 'updated_at',
                                        'value' => function($model) {
                                    return date('Y-m-d H:i:s', $model->updated_at);
                                }
                                    ],
                                    ['class' => 'common\grid\ActionColumn',
                                        'header' => '<a>操作</a>',
                                        'template' => '{modpwd} {update} {delete} {games}',
                                        'buttons' => [
                                            'modpwd' => function($url, $model) {
                                        return Html::a('<i class="fa fa-lock"></i> 修改密码', $url, ['class' => 'btn blue btn-xs']);
                                    },
                                            'games' => function($url, $model) {
                                        return Html::a('<i class="fa fa-gamepad"></i> 游戏', $url, ['class' => 'btn green btn-xs']);
                                    },
                                        ]
                                    ],
                                ],
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
