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
		//==PPOB Component
		'ppobh2h' =>[
            'class'=>'console\components\PpobH2h',
        ],
		//==DATABASE-SERVER CRONJOB [db] => db_cronjob
		'db_cronjob' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=kg',
            'username' => 'kg',
            'password' => '4dm1n15tr41t0R',
            'charset' => 'utf8',
        ],		
		//==PPOB-SERVER CRONJOB [db] => ppob_cronjob
		'ppob_cronjob' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.212.100;dbname=kg',
            'username' => 'ppob',
            'password' => 'ppobssdjaja81',
            'charset' => 'utf8',
        ],
		//==API-SERVER CRONJOB [db] => api_cronjob
		'api_cronjob' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.212.100;dbname=kg',
            'username' => 'api_cronjob',
            'password' => 'ssdjaja81',
            'charset' => 'utf8',
        ],
		//==LAB-API-SERVER CRONJOB [db_labtest] => labapi_cronjob
		'labapi_cronjob' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=192.168.212.100;dbname=kg_demo',
            'username' => 'api_cronjob',
            'password' => 'ssdjaja81',
            'charset' => 'utf8',
        ],
    ],
    'params' => $params,
];
