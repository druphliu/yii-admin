<?php


define('CORE_FOLDER',dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'vendor');
define('COMMON_FOLDER',dirname(dirname(dirname(__FILE__))).DIRECTORY_SEPARATOR.'common');
//当前应用层目录
define('APP_FOLDER',dirname(dirname(__FILE__)).DIRECTORY_SEPARATOR);


require_once(COMMON_FOLDER.'/config/backend/environment.php');

$environment = new Environment(Environment::DEVELOPMENT);
defined('YII_ENV') or define('YII_ENV',$environment->getEnv());
defined('YII_DEBUG') or define('YII_DEBUG',$environment->getDebug());
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL', $environment->getTraceLevel());
require_once(CORE_FOLDER.'/autoload.php');
require_once(CORE_FOLDER.'/yiisoft/yii2/Yii.php');
//启动项
require_once(COMMON_FOLDER.'/config/bootstrap.php');
require_once(APP_FOLDER.'/config/bootstrap.php');


//环境配置
$envConfig = $environment->getConfig();

//后台独立配置
$appConfig = require_once(APP_FOLDER.'/config/main.php');
$config = yii\helpers\ArrayHelper::merge($envConfig, $appConfig);


$application = new yii\web\Application($config);
$application->run();




