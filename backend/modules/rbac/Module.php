<?PHP

namespace backend\modules\rbac;

class Module extends \yii\base\Module
{

    public $controllerNamespace = 'backend\modules\rbac\controllers';

    public function init()
    {
        parent::init();
        $this->params['foo'] = 'bar';
    }

}

?>