<?php

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
                <?= $model->isNewRecord ? '创建' : '更新' ?>用户
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i><?= $model->isNewRecord ? '创建' : '更新' ?>用户
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
                        <?php
                        echo
                                $form->field($model, 'username')->label($model->getAttributeLabel("username"), ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg',
                                ])->error();
                        ?>
                        <?php
                        if ($model->isNewRecord) {
                            echo
                                $form->field($model, 'phone')->label($model->getAttributeLabel("phone"), ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg',
                                    'id'    => 'phone',
                                ])->error();

                        } else {
                            echo
                                $form->field($model, 'phone')->label($model->getAttributeLabel("phone"), ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg',
                                    'id'    => 'phone',
                                    'disabled' => 'true',
                                ])->error();
                        }
                        
                        ?>
                        <?php
                        if ($model->isNewRecord) {
                            echo
                                    $form->field($model, 'password_hash')->label('密码', ['class' => "col-md-3 control-label"])
                                    ->input("text", [
                                        'class' => 'form-control input-lg',
                                        "value" => ""
                                    ])->error();
                        }
                        ?>
                        <?php
                        echo
                                $form->field($model, 'myrole')->label('角色列表', ['class' => "col-md-3 control-label"])
                                ->checkboxList($roles,['class'=>'checkbox'])->error(false);
                        ?>                       
                        <div class="form-group">
                            <div class="col-md-10 text-center">
                                <?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary','id'=>'submit']) ?>
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
<script type="text/javascript">
    $("#phone").blur(function(){
      var str = $("#phone").val();
      var ret = "/^(?![0-9]+$)(?![a-zA-Z]+$)[0-9A-Za-z]{8,10}$/";
      if(!ret.test(str)){
        $('#submit').attr('disabled',true);
      } else {
        $('#submit').removeAttr("disabled");
      }
    });
</script>