<?php

/* @var $this yii\web\View */
$this->title = "编辑用户";
?>
<?php

echo $this->render('_form', [
    'model' => $model,
    'roles' => $roles
]);
?>