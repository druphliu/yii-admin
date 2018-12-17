<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\modules\rbac\components\Tree;
use yii\helpers\Url;
use common\widgets\Alert;

$hmenu = admin\modules\rbac\components\HMenu::getInstance();
?>
<div class="page-content-wrapper">
    <div class="page-content">
        <ul class="page-breadcrumb breadcrumb">
            <li>
                <a href="<?= Url::to(['/base/index']) ?>">首页</a><i class="fa fa-circle"></i>
            </li>
            <li>
                <a href="<?= Url::to(['/rbac/menu/index']) ?>">菜单管理</a><i class="fa fa-circle"></i>
            </li>
            <li class="active">
                <?= $model->isNewRecord ? '创建' : '更新' ?>菜单
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i><?= $model->isNewRecord ? '创建' : '更新' ?>菜单
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
                        $form->field($model, 'type')->label('类型', ['class' => "col-md-3 control-label"])->dropdownList(
                                $hmenu->getMoudel(), [
                            'encode' => false,
                            'class' => 'form-control input-lg js-type-btn',
                            'placeholder' => ''
                        ])->error();
                        ?>
                        <?php
                        echo
                        $form->field($model, 'pid')->label('父级菜单', ['class' => "col-md-3 control-label"])->dropdownList(
                                [], [
                            'encode' => false,
                            'class' => 'form-control input-lg js-menu-list',
                            'placeholder' => ''
                        ])->error();
                        ?>
                        <?php
                        echo
                                $form->field($model, 'name')->label($model->getAttributeLabel("name"), ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg',
                                ])->error();
                        ?>
                        <?php
                        echo
                                $form->field($model, 'url')->label($model->getAttributeLabel("url"), ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg',
                                ])->error();
                        ?>
                        <?php
                        echo
                                $form->field($model, 'icon')->label($model->getAttributeLabel("icon"), ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg jsIconInput',
                                ])->error();
                        ?>
                        <div class="form-group">
                            <label class="col-md-3 control-label">常用图标</label>
                            <div class="col-md-5">
                                <p class="form-control-static" style="display:block;">
                                    <?php foreach ($hmenu->icons as $icon): ?>
                                        <span class="label label-default jsIconSpan" style="display:inline-block;margin-bottom:5px"><i class="<?= $icon ?>"></i></span>
                                    <?php endforeach; ?>
                                </p>
                            </div>
                        </div>

                        <?php
                        echo
                                $form->field($model, 'sort')->label($model->getAttributeLabel("sort"), ['class' => "col-md-3 control-label"])
                                ->input("text", [
                                    'class' => 'form-control input-lg',
                                ])->error();
                        ?>
                        <div class="alert hide" id="js-form-alert" role="alert"></div>

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
    <script>
        $(function() {
            var defaultIcon = $('.jsIconInput').val();
            if (defaultIcon) {
                $('.jsIconSpan').each(function() {
                    if ($(this).children('i').attr('class') == defaultIcon) {
                        $(this).removeClass('label-default');
                        $(this).addClass('label-info');
                    }
                })
            }
            $('.jsIconSpan').on('click', function() {
                $('.jsIconSpan').each(function() {
                    $(this).removeClass('label-info');
                    $(this).addClass('label-default');
                })
                $(this).removeClass('label-default');
                $(this).addClass('label-info');
                var icon = $(this).children('i').attr('class');
                $('.jsIconInput').val(icon);
            });

            $('.js-type-btn').on('change', function() {
                var type = $(this).val();
                getMenu(type);
            });
            var type = "<?=$model->type?$model->type:1?>";
            var val = "<?=$model->pid?$model->pid:0?>";
            getMenu(type,val);
        })

        function getMenu(type,val) {
            var url = "<?= Url::to(['/rbac/menu/index']) ?>";
            $.get(url, {type: type,is_type:1}, function(data) {
                if(data.code>0){
                    layer.msg('没有数据');
                }else{
                    var op = '';
                    $('.js-menu-list').html(data.list);
                    if(val){
                        $('.js-menu-list').val(val);
                    }
                }
            }, 'json');
        }
    </script>
</div>


