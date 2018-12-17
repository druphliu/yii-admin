<?php

use common\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = "角色列表";
?>
<div class="page-content-wrapper">
    <div class="page-content">
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a id="home" href="<?= Url::to(['/base/index']) ?>">首页</a><i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?= Url::to(['/rbac/role/index']) ?>">角色管理</a><i class="fa fa-circle"></i>
            </li>
            <li class="active">
                角色列表
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
                            <?=
                            Html::a(' <i class="fa fa-send"></i> 初始化权限', 'javascript:;', ['class' => 'btn btn-default btn-sm jsinitRole',
                                'data-url' => Url::toRoute(['init']),
                                'data-csrf' => \Yii::$app->request->getCsrfToken()])
                            ?>

                            <a class="btn btn-default btn-sm" href="<?= Url::toRoute(['create']); ?>">
                                <i class="fa fa-pencil"></i>
                                新增
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body">
                        <div class="table-toolbar" sf-controller="app">

                        </div>
                        <div class="table-scrollable">
                            <?php
                            echo GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    [
                                        'header' => '<a>标识</a>',
                                        'value' => 'name'
                                    ],
                                    [
                                        'header' => '<a>角色名称</a>',
                                        'value' => 'description'
                                    ],
                                    ['class' => 'common\grid\ActionColumn',
                                        'header' => '<a>操作</a>',
                                        'template' => '{view} {update} {delete}',
                                        'buttons' => [
                                            'view' => function($url, $model) {
                                        return Html::a('<i class="fa fa-lock"></i> 权限', $url, ['class' => 'btn blue btn-xs']);
                                    }
                                        ]
                                    ],
                                ]
                            ]);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>



    </div>
</div>

<script>
    $(function() {
        $(".jsinitRole").click(function() {
            if (confirm("初始化时间可能较长\n确认开始自动扫描并添加资源项? ")) {
                $.ajax({
                    url: $(this).data('url'),
                    type: "post",
                    data: {'_csrf': $(this).data('csrf')},
                    success: function(data) {
                        if (data) {
                            alert("操作成功");
                        } else {
                            alert("失败,请联系管理员");
                        }
                    }
                });
            }
            return false;
        });
    })
</script>