<?php

/**
 * 模块功能: 新增或者更新的表单
 * Created by PhpStorm.
 * author: 徐鹏飞
 * email: 503186749@qq.com
 * Date: 2016/12/8 1:04
 * Time:
 */
use yii\widgets\ActiveForm;
use yii\bootstrap\Html;
use yii\helpers\Url;
use common\widgets\Alert;
?>
<div class="page-content-wrapper">
    <div class="page-content">
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?= Url::to(['/base/index']) ?>">首页</a><i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?= Url::to(['/rbac/role/index']) ?>">角色管理</a><i class="fa fa-circle"></i>
            </li>
            <li class="active">
                <?= $model->isNewRecord ? '创建' : '更新' ?>角色
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i><?= $model->isNewRecord ? '创建' : '更新' ?>角色
                        </div>
                        <div class="tools">
                            <a href="" class="collapse">
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body" style="padding-top: 30px;">
                        <?= Alert::widget(); ?>
                        <?php
                        $form = ActiveForm::begin([
                                    'id' => $model->formName(),
                                    // 'enableAjaxValidation' => true,
                                    'options' => [
                                        'class' => 'form-horizontal js-form'
                                    ],
                                    'fieldConfig' => [
                                        "template" => '
                        <div class="form-body">
                            <div class="form-group">
                                {label}
                                <div class="col-md-5">{input}</div>
                                {error}
                            </div>
                        </div>'
                                    ]
                        ]);
                        ?>

                        <?php
                        echo
                                $form->field($model, 'name')->label("标识", ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg',
                                ])->error();
                        ;
                        ?>
                        <?php
                        echo
                                $form->field($model, 'description')->label("角色名称", ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg',
                                ])->error();
                        ?>
                        <div class="form-group">
                            <div class="col-md-10 text-center">
                                <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
                                <?= Html::resetButton('重置', ['class' => $model->isNewRecord ? 'btn btn-default' : 'hide']) ?>
                            </div>
                        </div>

                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>