<?php

/* @var $this yii\web\View */

use yii\helpers\Url;

$this->title = "创建菜单";
?>

<?php

echo $this->render('_form', [
    'model' => $model,
    'roles' => $roles,
    'my_role' => $my_role,
]);
?>