<?php


class Environment
{

    //开发环境
    const DEVELOPMENT = 100;
    //测试环境
    const TEST = 200;
    //预发布环境
    const STAGE = 300;
    //生产环境
    const PRODUCTION = 400;

    private $_mode = 0;
    private $_debug;
    private $_trace_level;
    private $_config;
    private $_env;

    /**
     * 公用配置
     * 使用说明:
     *
     */
    private function _main()
    {
        return [
            'vendorPath' => CORE_FOLDER,
            'components' => [
                'cache' => [
                    'class' => 'yii\caching\FileCache',
                ],
                'session' => [
                    'timeout' => 86400,
                ],
                'request' => [
                    'cookieValidationKey' => 'kPMwIp7dN1F7tDBo1dPv2hhYH8koqTgN2323',
                ],
                'errorHandler' => [
                    'errorAction' => 'site/error',
                ],
            ],
            'aliases' => [
                '@bower' => '@vendor/bower-asset',
                '@npm'   => '@vendor/npm-asset',
            ],
            'modules' => [
            ]
        ];
    }

    /**
     * 开发环境配置
     * 使用说明:
     * - 本地网址
     * - 本地数据库
     * - 显示所有的详细错误信息
     * - 打开GII模块
     */
    private function _development()
    {
        define('STATIC_DOMAIN', 'http://dev.static.yingxiong.com/yiicmsbackend/');
        return [
            'modules' => [
                'debug' => [
                    'class' => 'yii\debug\Module',
                ],
                'gii' => [
                    'class' => 'yii\gii\Module',
                    'allowedIPs' => ['10.0.6.199', '127.0.0.1', '::1']
                ],
            ],

            //Application components
            'components' => [
                // Database
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=10.0.6.199;dbname=admin',
                    'username' => 'root',
                    'password' => 'yx_pwd',
                    'charset' => 'utf8',
                    'tablePrefix' => 'c_',

                    //配置从服务器
                    'slaveConfig' => [
                        'username' => 'root',
                        'password' => 'yx_pwd',
                        'charset' => 'utf8',
                        'tablePrefix' => 'cms_',
                        'attributes' => [
                            // use a smaller connection timeout
                            PDO::ATTR_TIMEOUT => 10,
                        ],
                    ],

                    // 配置从服务器组
                 /*   'slaves' => [
                        ['dsn' => 'mysql:host=10.0.6.199;dbname=hsdk'],
                    ],*/
                ],
                'redis' => [
                    'class' => 'yii\redis\Cache',
                    'redis' => [
                        'hostname' => '127.0.0.1',
                        'port' => 6379,
                        'database' => 1,
                    ],
                    'keyPrefix' => 'chu_',
                    'serializer' => false, //null|array|false
                ],

                'log' => [
                    'traceLevel' => $this->_trace_level,
                    'targets' => [
                        [
                            'class' => 'yii\log\FileTarget',
                            'levels' => ['error', 'warning','info'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * 测试环境配置
     * 使用说明:
     * - 本地网址
     * - 本地数据库
     * - 标准的错误日志打印 (404,500, etc.)
     * @var array
     */
    private function _test()
    {
        define('STATIC_DOMAIN', 'http://dev.static.yingxiong.com/yiicmsbackend/');
        return [
            'modules' => [
                'debug' => [
                    'class' => 'yii\debug\Module',
                ],
                'gii' => [
                    'class' => 'yii\gii\Module',
                    'allowedIPs' => ['10.0.6.199', '127.0.0.1', '::1']
                ],
            ],
            //Application components
            'components' => [
                // Database
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=10.0.6.199;dbname=hsdk',
                    'username' => 'root',
                    'password' => 'yx_pwd',
                    'charset' => 'utf8',
                    'tablePrefix' => 'cms_',

                    //配置从服务器
                    'slaveConfig' => [
                        'username' => 'root',
                        'password' => 'yx_pwd',
                        'charset' => 'utf8',
                        'tablePrefix' => 'cms_',
                        'attributes' => [
                            // use a smaller connection timeout
                            PDO::ATTR_TIMEOUT => 10,
                        ],
                    ],

                    // 配置从服务器组
                    'slaves' => [
                        ['dsn' => 'mysql:host=10.0.6.199;dbname=hsdk'],
                    ],
                ],
                'redis' => [
                    'class' => 'yii\redis\Cache',
                    'redis' => [
                        'hostname' => '10.0.6.199',
                        'port' => 6379,
                        'database' => 1,
                    ],
                    'keyPrefix' => 'chu_',
                    'serializer' => false, //null|array|false
                ],
                'log' => [
                    'traceLevel' => $this->_trace_level,
                    'targets' => [
                        [
                            'class' => 'yii\log\FileTarget',
                            'levels' => ['error', 'warning'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * 预发布环境配置
     * 使用说明:
     * - 线上的网址
     * - 线上的数据库
     * - 打印错误日志
     */
    private function _stage()
    {
        define('STATIC_DOMAIN', 'http://dev.static.yingxiong.com/yiicmsbackend/');
        return [
            'modules' => [
                'debug' => [
                    'class' => 'yii\debug\Module',
                ],
                'gii' => [
                    'class' => 'yii\gii\Module',
                    'allowedIPs' => ['10.0.6.199', '127.0.0.1', '::1']
                ],
            ],
            //Application components
            'components' => [
                // Database
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=10.0.6.199;dbname=hsdk',
                    'username' => 'root',
                    'password' => 'yx_pwd',
                    'charset' => 'utf8',
                    'tablePrefix' => 'cms_',

                    //配置从服务器
                    'slaveConfig' => [
                        'username' => 'root',
                        'password' => 'yx_pwd',
                        'charset' => 'utf8',
                        'tablePrefix' => 'cms_',
                        'attributes' => [
                            // use a smaller connection timeout
                            PDO::ATTR_TIMEOUT => 10,
                        ],
                    ],

                    // 配置从服务器组
                    'slaves' => [
                        ['dsn' => 'mysql:host=10.0.6.199;dbname=hsdk'],
                    ],
                ],
                'redis' => [
                    'class' => 'yii\redis\Cache',
                    'redis' => [
                        'hostname' => '10.0.6.199',
                        'port' => 6379,
                        'database' => 1,
                    ],
                    'keyPrefix' => 'chu_',
                    'serializer' => false, //null|array|false
                ],
                'log' => [
                    'traceLevel' => $this->_trace_level,
                    'targets' => [
                        [
                            'class' => 'yii\log\FileTarget',
                            'levels' => ['error'],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * 生产环境配置
     * 使用说明:
     * - 线上的网址
     * - 线上的数据库
     * - 标准的错误日志打印 (404,500, etc.)
     */
    private function _production()
    {
        define('STATIC_DOMAIN', '//static.yingxiong.com/yiicmsbackend/');
        return [
            'bootstrap' => ['log'],
            'modules' => [],

            //Application components
            'components' => [
                // Database
                'db' => [
                    'class' => 'yii\db\Connection',
                    'dsn' => 'mysql:host=10.30.52.195;port=8904;dbname=hsdk',
                    'username' => 'gangzhangyx',
                    'password' => 'phVOWPhJTJVITH9GangZhang',
                    'charset' => 'utf8',
                    'tablePrefix' => 'cms_',

                    //配置从服务器
                    'slaveConfig' => [
                        'username' => 'gangzhangyx',
                        'password' => 'phVOWPhJTJVITH9GangZhang',
                        'charset' => 'utf8',
                        'tablePrefix' => 'cms_',
                        'attributes' => [
                            // use a smaller connection timeout
                            PDO::ATTR_TIMEOUT => 10,
                        ],
                    ],

                    // 配置从服务器组
                    'slaves' => [
                        ['dsn' => 'mysql:host=10.30.52.195;port=8904;dbname=hsdk'],
                    ],
                ],
                'redis' => [
                    'class' => 'yii\redis\Cache',
                    'redis' => [
                        'hostname' => '10.25.198.238',
                        'port' => 6381,
                        'database' => 1,
                    ],
                    'keyPrefix' => 'sdk_',
                    'serializer' => false, //null|array|false
                ],
                'log' => [
                    'traceLevel' => $this->_trace_level,
                    'targets' => [
                       /* [
                            'class' => 'yii\log\FileTarget',
                            'levels' => ['error', 'warning'],
                        ],*/
                        'file' => [
                            'class' => 'yii\log\FileTarget',
                            'levels' => ['warning', 'error'],
                            'categories' => ['yii\*']
                        ],
                        'db' => [
                            'class' => 'admin\components\DbLog',
                            'levels' => ['info'],
                            'categories' => ['yii\db\Command::*'],
                        ],
                    ],
                ]
            ],
        ];
    }

    /**
     * 返回错误的模式
     * @return Bool
     */
    public function getDebug()
    {
        return $this->_debug;
    }

    public function getEnv()
    {
        return $this->_env;
    }

    /**
     * 返回跟踪日志 YII_TRACE_LEVEL
     * @return int
     */
    public function getTraceLevel()
    {
        return $this->_trace_level;
    }

    /**
     * 根据选择返回配置数组
     *
     * @return array
     */
    public function getConfig()
    {
        return $this->_config;
    }

    /**
     * 初始化环境配置模式
     * @param constant $mode
     */
    function __construct($mode)
    {
        $this->_mode = $mode;
        $this->setConfig();
    }

    /**
     * 设置选择的模式
     * @param constant $mode
     */
    private function setConfig()
    {
        switch ($this->_mode) {
            case self::DEVELOPMENT:
                $this->_config = array_merge_recursive($this->_main(), $this->_development());
                $this->_debug = TRUE;
                $this->_trace_level = 3;
                $this->_env = 'dev';
                break;
            case self::TEST:
                $this->_config = array_merge_recursive($this->_main(), $this->_test());
                $this->_debug = TRUE;
                $this->_trace_level = 3;
                $this->_env = 'dev';
                break;
            case self::STAGE:
                $this->_config = array_merge_recursive($this->_main(), $this->_stage());
                $this->_debug = TRUE;
                $this->_trace_level = 0;
                $this->_env = 'pro';
                break;
            case self::PRODUCTION:
                $this->_config = array_merge_recursive($this->_main(), $this->_production());
                $this->_debug = FALSE;
                $this->_trace_level = 0;
                $this->_env = 'pro';
                break;
            default:
                $this->_config = $this->_main();
                $this->_debug = TRUE;
                $this->_trace_level = 3;
                $this->_env = 'dev';
                break;
        }
    }
}
