<?php

/**
 * 修改密码页
 */
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use common\widgets\Alert;
?>

<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
    <div class="page-content">
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?= Url::to(['/base/index']) ?>">首页</a><i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?= Url::to(['/rbac/user/index']) ?>">用户管理</a><i class="fa fa-circle"></i>
            </li>
            <li class="active">
                修改密码
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>修改密码
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
                                        'class' => 'form-horizontal'
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
                        <div class="form-group">
                            <label class="col-md-3 control-label">用户名</label>
                            <div class="col-md-5">
                                <p class="form-control-static"><strong><?= $model->username ?></strong></p>
                            </div>
                        </div>
                        <?php
                        echo
                                $form->field($model, 'password_hash')->label('密码', ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg',
                                    "value" => ""
                                ])->error();
                        ?>
                        <?php
                        echo
                                $form->field($model, 'rep_password_hash')->label('确认密码', ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg',
                                    "value" => ""
                                ])->error();
                        ?>
                        <div class="form-group">
                            <div class="col-md-10 text-center">
                                <?= Html::submitButton('保存', ['class' => 'btn btn-primary']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
