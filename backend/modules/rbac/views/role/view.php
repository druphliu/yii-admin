<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\widgets\Alert;
use yii\helpers\Url;

$this->title = '角色授权';
$this->params['breadcrumbs'][] = '管理员设置';
$this->params['breadcrumbs'][] = $this->title;
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
                角色权限
            </li>
        </ul>
        <div class="row">
            <div class="col-md-12">
                <div class="portlet box blue-hoki ">
                    <div class="portlet-title">
                        <div class="caption">
                            <i class="fa fa-gift"></i>权限列表
                        </div>
                        <div class="tools">
                            <a href="" class="collapse">
                            </a>
                        </div>
                    </div>
                    <div class="portlet-body" style="padding-top: 30px;">
                        <?php ActiveForm::begin(); ?>
                        <?= Alert::widget() ?>

                        <table class="table table-striped table-advance table-hover">
                            <thead>
                                <tr>
                                    <th class="tablehead" colspan="2">
                                        <span class="glyphicon glyphicon-menu-left" onclick="javascript: history.go(-1);"></span>
                                        <span><?= $this->title . ': ' . $role; ?></span>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($moudels as $key => $m): ?>
                                    <tr>
                                        <td width="20%"><?= $m; ?></td>
                                        <td></td>
                                    </tr>
                                    <?php foreach ($treeArr as $tree): ?>
                                        <?php if ($tree['type'] == $key): ?>
                                            <tr>
                                                <td>
                                                    <label class="checkbox-inline">
                                                        <input type="checkbox" class="jspbox" value="<?= $tree['id']; ?>"> <?= $tree['name']; ?>
                                                    </label>
                                                </td>
                                                <td></td>
                                            </tr>
                                            <?php if (!empty($tree['_child'])): ?>
                                                <?php foreach ($tree['_child'] as $childs): ?>
                                                    <?php if ($childs['pid'] == $tree['id']): ?>
                                                        <tr>
                                                            <td style="padding-left: 50px;">
                                                                <label class="checkbox-inline">
                                                                    <input type="checkbox" value="<?= $childs['id']; ?>" class="jscbox jscheckp_<?= $tree['id'] ?>"> <?= $childs['name'] ?>
                                                                </label>
                                                            </td>
                                                            <td>
                                                                <?php if (!empty($childs['_child'])): ?>
                                                                    <?php foreach ($childs['_child'] as $child): ?>
                                                                        <?php if ($child['pid'] == $childs['id']): ?>
                                                                            <label class="checkbox-inline">
                                                                                <input type="checkbox" name="rules[]" value="<?= $child['url'] ?>" 
                                                                                <?= in_array($child['url'], $authRules) ? 'checked' : '' ?> 
                                                                                       class="jscheckp_<?= $tree['id'] ?> jscheckc<?= $childs['id'] ?>">
                                                                                <?= $child['name'] ?>
                                                                            </label>
                                                                        <?php endif; ?>
                                                                    <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </td>
                                                        </tr>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php endforeach; ?>
                                <tr>
                                    <td colspan="2">
                                        <?= Html::submitButton('保存', ['class' => 'btn btn-success']) ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function() {
            $('.jspbox').on('click', function() {
                var value = $(this).val();
                if ($(this).is(':checked')) {
                    $('.jscheckp_' + value).each(function() {
                        $(this).prop('checked', true);
                        $(this).parent().addClass('checked');
                    });
                } else {
                    $('.jscheckp_' + value).each(function() {
                        $(this).prop('checked', false);
                        $(this).parent().removeClass('checked');
                    });
                }
            })
            $('.jscbox').on('click', function() {
                var value = $(this).val();
                if ($(this).is(':checked')) {
                    $('.jscheckc' + value).each(function() {
                        $(this).prop('checked', true);
                        $(this).parent().addClass('checked');
                    });
                } else {
                    $('.jscheckc' + value).each(function() {
                        $(this).prop('checked', false);
                        $(this).parent().removeClass('checked');
                    });
                }
            })
        })
    </script>
</div>

