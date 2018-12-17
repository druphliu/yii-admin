<?php
/* @var $this yii\web\View */
use yii\helpers\Url;

$this->title = "创建角色";
$this->params['breadcrumbs'][] = ["label"=>'权限管理','url'=>Url::to(["rbac/role/index"])];
$this->params['breadcrumbs'][] = ["label"=>$this->title,'url'=>Url::current()];
?>
<?php
echo $this->render('_form',[
    'model'=>$model,
]);
?>