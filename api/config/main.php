<?php
$params = array_merge(
    require(__DIR__ . '/../config/params.php')
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
		'login' => [
            'basePath' => '@app/modules/login',
            'class' => 'api\modules\login\Module',
        ],
		'master' => [
            'basePath' => '@app/modules/master',
            'class' => 'api\modules\master\Module',
        ],
		'transaksi' => [
            'basePath' => '@app/modules/transaksi',
            'class' => 'api\modules\transaksi\Module',
        ],
		'hirs' => [
            'basePath' => '@app/modules/hirs',
            'class' => 'api\modules\hirs\Module',
        ]
    ],
    'components' => [
        'user' => [
            'identityClass' => 'api\modules\login\models\User',
            'enableAutoLogin' => false,
            'enableSession' => false,
			'loginUrl' => null,
        ],
		// 'formatter' => [
           // 'dateFormat' => 'd-M-Y',
           // 'datetimeFormat' => 'd-M-Y H:i:s',
           // 'timeFormat' => 'H:i:s',
		 // ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
		'statusCode' =>[
            'class'=>'common\components\StatusCode',
        ],
        // 'errorHandler' => [
            // 'errorAction' => 'site/error',
        // ],
		
		/*input Json -ptr.nov- */
		// 'request' => [
			// 'class' => '\yii\web\Request',
            // 'enableCookieValidation' => false,
			// 'parsers' => [
				// 'application/json' => 'yii\web\JsonParser',
			// ]
		// ],
		/*
		'errorHandler' => [
			'errorAction' => ''v1/country',
		],
		*/
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => true,
            'showScriptName' => false,
            'rules' => [
				[
					'class' => 'yii\rest\UrlRule',
					'controller' =>[						
						'login/user-signup-owner', 		//Signup OWNER=(Atuh Login & Manual Login & Aktivasi Manual Login)
						'login/user-signup-opr',		//Signup OPERATIONAL
						'login/user-reset',
						'login/user-login',
						'login/user-link',
						'login/user-profile',
						'login/user-operational',
						'login/user-permission',
						'login/user-modul'
					],						
					'patterns' => [
						'PUT,PATCH' => 'update',
						'DELETE' => 'delete',
						'GET,HEAD' => 'view',
						'POST' => 'create',
						'GET,HEAD' => 'index',
						'{id}' => 'options',
						'' => 'options',
					]
                ],
				[
					'class' => 'yii\rest\UrlRule',
					'controller' =>[
						'master/kota',
						'master/provinsi',
						'master/product',
						'master/store',							
						'master/customer',							
						'master/merchant',							
						'master/merchant-type',							
						'master/merchant-bank',							
						// 'master/storetest',
						// 'master/item-image',
						// 'master/customer',							
					],
					'patterns' => [
						'PUT' => 'update',
						'DELETE' => 'delete',
						'GET,HEAD {id}' => 'view',
						'POST' => 'create',
						'GET,HEAD' => 'index',
						'{id}' => 'options',
						'' => 'options',
					]
                ],
				[
                        'class' => 'yii\rest\UrlRule',
                        'controller' =>[
							'master/product-store',
						],
						'patterns' => [
							'PUT,PATCH {id}' => 'update',
							'DELETE {id}' => 'delete',
							'GET,HEAD {id}' => 'view',
							'POST' => 'create',
							'GET,HEAD' => 'index',
							'{id}' => 'options',
							'' => 'options',
						]
                ],
				[
                        'class' => 'yii\rest\UrlRule',
                        'controller' =>[
							'transaksi/penjualan-header',
							'transaksi/penjualan-detail',
							'transaksi/penjualan-closing',
							'transaksi/penjualan-closing-bukti'
						],
						'patterns' => [
							'PUT,PATCH {id}' => 'update',
							'DELETE {id}' => 'delete',
							'GET,HEAD {id}' => 'view',
							'POST' => 'create',
							'GET,HEAD' => 'index',
							'{id}' => 'options',
							'' => 'options',
						]
                        //'extraPatterns' => ['GET search' => 'search'],

                        //'except' => ['view', 'create', 'update'],
                ],
				[
                        'class' => 'yii\rest\UrlRule',
                        'controller' =>[
							'hirs/employe-data',
							'hirs/employe-image',
							'hirs/employe-absensi'
						],
						'patterns' => [
							'PUT,PATCH {id}' => 'update',
							'DELETE {id}' => 'delete',
							'GET,HEAD {id}' => 'view',
							'POST' => 'create',
							'GET,HEAD' => 'index',
							'{id}' => 'options',
							'' => 'options',
						]
                        //'extraPatterns' => ['GET search' => 'search'],

                        //'except' => ['view', 'create', 'update'],
                ],
				/* [
					'class' => 'yii\rest\UrlRule',
                    'controller' =>
                    [   //ptr,.nov penting buat API
							'login/user',
							'login/password',
							'login/signature',
							'login/profile',
							'login/user-token',
							'master/store',
								
					],

                    //Ini dibutuhkan jika ID primary key bukan digital dalam bentuk regex
                    'tokens' =>
                    [
                      '{id}' => '<id:\\w+>',
                    ],
                ] */
            ],
        ],
		// 'db' => [
            // 'class' => 'yii\db\Connection',
            // 'dsn' => 'mysql:host=localhost;dbname=kasir',
            // 'username' => 'root',
            // 'password' => '',
            // 'charset' => 'utf8',
        // ],
		/*SERVER CACHED -ptr.nov-*/
		/* 'cache' => [
			'class' => 'yii\caching\MemCache',
			'servers' => [
				[
					'host' => 'localhost',
					'port' => 11211,
					'weight' => 100,
				],
				// [
					// 'host' => 'server2',
					// 'port' => 11211,
					// 'weight' => 50,
				// ],
			],
		], */
		// 'errorHandler' => [
            // 'maxSourceLines' => 20,
        // ],
		/**
		 * Handle Ajax content parsing & _CSRF
		 * @author ptrnov  <piter@lukison.com>
		 * @since 1.1
		 */
		'request' => [
			'enableCsrfValidation'=>false,
            'cookieValidationKey' => 'dWut4SrmYAaXg0NfqpPwnJa23RMIUG7j_kgapi',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser', // required for POST input via `php://input`
            ]
        ],
		'response' => [
			'format' => yii\web\Response::FORMAT_JSON,
			'charset' => 'UTF-8',
			// ...
		]
    ],
    // 'params' => $params,
];



