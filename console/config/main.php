<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'console\controllers',
    'controllerMap' => [
        'fixture' => [
            'class' => 'yii\console\controllers\FixtureController',
            'namespace' => 'common\fixtures',
          ],
    ],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.99.99;dbname=kg',
            'username' => 'api_cronjob',
            'password' => 'ssdjaja81',
            'charset' => 'utf8',
        ],
		'db_labtest' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=10.10.99.99;dbname=kg_demo',
            'username' => 'api_cronjob',
            'password' => 'ssdjaja81',
            'charset' => 'utf8',
        ],
    ],
    'params' => $params,
];
