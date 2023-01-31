<?php

use yii\db\Connection;
use yii\rbac\DbManager;
use yii\caching\FileCache;
use yii\helpers\ArrayHelper;
use modules\main\Module as MainModule;
use modules\users\Module as UserModule;
use modules\rbac\Module as RbacModule;
use dominus77\maintenance\interfaces\StateInterface;
use dominus77\maintenance\states\FileState;
use \kartik\datecontrol\Module;

$params = ArrayHelper::merge(
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'name' => 'Aplicación',
    'timeZone' => 'Europe/Madrid',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset'
    ],
    'bootstrap' => [],
    'container' => [
        'singletons' => [
            StateInterface::class => [
                'class' => FileState::class,
                'dateFormat' => 'd-m-Y H:i:s',
                'directory' => '@frontend/runtime'
            ]
        ]
    ],
    'modules' => [
        'dynagrid'=> [
            'class'=>'\kartik\dynagrid\Module',
            'defaultPageSize'=>20, //this line

            // other module settings
            'dbSettings'=> [
                'tableName'=> 'tbl_dynagrid',
                'idAttr' => 'id',
                'filterAttr'=> 'filter_id',
                'sortAttr'=>'sort_id',
                'dataAttr'=>'data'
            ],
            'dbSettingsDtl' => [
                'tableName' => 'tbl_dynagrid_dtl',
                'idAttr' => 'id',
                'categoryAttr' => 'category',
                'nameAttr' => 'name',
                'dataAttr' => 'data',
                'dynaGridId' => 'dynagrid_id'
            ],
        ],
        'gridview'=> [
            'class'=>'\kartik\grid\Module',
            // other module settings
        ],
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',
            // set your display timezone
            'displayTimezone' => 'Europe/Madrid',

            // format settings for displaying each date attribute (ICU format example)
            'displaySettings' => [
                Module::FORMAT_DATE => 'dd/MM/yyyy',
                Module::FORMAT_TIME => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'dd/MM/yyyy H:i:s',
            ],

            // format settings for saving each date attribute (PHP format example)
            'saveSettings' => [
                //Module::FORMAT_DATE => 'php:U', // saves as unix timestamp
                Module::FORMAT_DATE => 'php:Y-m-d', // saves as unix timestamp
                Module::FORMAT_TIME => 'php:H:i:s',
                Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
            ],


            // set your timezone for date saved to db
            'saveTimezone' => 'Europe/Madrid',


            // automatically use kartik\widgets for each of the above formats
            'autoWidget' => true,

            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                Module::FORMAT_DATE => ['type'=>2,
                    'pluginOptions'=>[
                        'autoclose'=>true,
                        'todayHighlight'=>true
                    ]
                ], // example
                Module::FORMAT_DATETIME => [], // setup if needed
                Module::FORMAT_TIME => [], // setup if needed
            ],
        ],
        'main' => [
            'class' => MainModule::class
        ],
        'users' => [
            'class' => UserModule::class
        ],
        'rbac' => [
            'class' => RbacModule::class
        ]
    ],
    'components' => [
        'db' => [
            'class' => Connection::class,
            'dsn' => 'mysql:host=localhost;dbname=yii2_advanced_start',
            'username' => '',
            'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'tbl_',
            'enableSchemaCache' => true
        ],
        'authManager' => [
            'class' => DbManager::class,
            'cache' => 'cache'
        ],
        'cache' => [
            'class' => FileCache::class,
            'cachePath' => '@frontend/runtime/cache'
        ],
        'mailer' => [
            'useFileTransport' => false
        ],
        'assetManager' => [
            'appendTimestamp' => true,
            'basePath' => '@app/web/assets'
        ]
    ]
];
