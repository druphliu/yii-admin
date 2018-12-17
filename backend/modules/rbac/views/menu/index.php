<?php

use yii\helpers\Html;
use common\grid\GridView;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\widgets\Alert;

$this->title = "菜单列表";
?>
<div class="page-content-wrapper">
    <div class="page-content">
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a id="home" href="<?= Url::to(['/base/index']) ?>">首页</a><i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?= Url::to(['/rbac/menu/index']) ?>">菜单管理</a><i class="fa fa-circle"></i>
            </li>
            <li class="active">
                菜单列表
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
                <div class="portlet box blue-hoki">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-globe"></i>菜单列表
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
                            <?php echo $this->render('_search', ['model' => $searchModel]); ?>                          
                        </div>
                        <div class="table-scrollable">
                            <?= Alert::widget(); ?>
                            <?php
                            $form = ActiveForm::begin([
                                        'action' => ['index'],
                                        'method' => 'post',
                            ]);
                            ?>
                            <?php
                            echo GridView::widget([
                                'dataProvider' => $dataProvider,
                                'columns' => [
                                    'id',
                                    [
                                        'attribute' => 'icon',
                                        'format' => 'raw',
                                        'header' => '<a>图标</a>',
                                        'value' => function($v) {
                                    return sprintf("<i class='%s'></i>", $v['icon']);
                                }
                                    ],
                                    [
                                        'attribute' => 'name',
                                        'format' => 'raw',
                                        'header' => '<a>菜单名称</a>'
                                    ],
                                    [
                                        'attribute' => 'url',
                                        'header' => '<a>路由</a>'
                                    ],
                                    [
                                        'attribute' => 'smname',
                                        'header' => '<a>模块</a>'
                                    ],
                                    [
                                        'attribute' => 'sort',
                                        'format' => 'raw',
                                        'header' => '<a>排序</a>',
                                        'value' => function($v) {
                                    return '<div style="max-width:50px;">'
                                            . '<input type="hidden" name="id[]" value="' . $v['id'] . '">'
                                            . '<input type="text" class="form-control" name="sort[]" value="' . $v['sort'] . '">'
                                            . '</div>';
                                }
                                    ],
                                    ['class' => 'common\grid\ActionColumn',
                                        'header' => '<a>操作</a>',
                                        'template' => '{update} {delete}',
                                    ],
                                ],
                                'layout' => '{items}'
                                            . '<div class=""><div class="col-md-2">'. Html::submitButton('排序', ['class' => 'btn btn-primary']).'</div>'
                                            . '<div class="col-md-10 text-right" style="padding-right:15px;">{pager}</div></div>'
                            ]);
                            ?>
                            <?php ActiveForm::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


