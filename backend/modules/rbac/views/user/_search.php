<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

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
<?=

$form->field($model, 'username')->label(false)->input("text", [
    'placeholder' => $model->getAttributeLabel('username')
])->error(false);
?>

<?= Html::submitButton('搜索', ['class' => 'btn btn-primary pull-right']) ?>
<?php ActiveForm::end(); ?>

