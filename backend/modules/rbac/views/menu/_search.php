<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use admin\models\HsdkSysmodule;

$pts = HsdkSysmodule::find()->select(['name', 'id'])->where(['status'=>1])->indexBy('id')->column();
/* @var $this yii\web\View */
/* @var $model backend\models\CioProductSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<?php
$form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => [
                'class' => 'form-inline'
            ],
        ]);
?>
<div class="form-group field-menu-name">
    <input type="text"class="form-control" name="name" placeholder="名称" value="<?=Yii::$app->request->get('name');?>">
</div>
<div class="form-group field-menu-name">
    <select class="form-control" name="type">
        <option value="">全部模块</option>
        <?php foreach($pts as $key=>$p):?>
            <option value="<?=$key?>" <?=$key==Yii::$app->request->get('type')?"selected='selected'":''?>><?=$p?></option>
        <?php endforeach;?>
    </select>
</div>

<?= Html::submitButton('搜索', ['class' => 'btn btn-primary pull-right']) ?>
<?php ActiveForm::end(); ?>

