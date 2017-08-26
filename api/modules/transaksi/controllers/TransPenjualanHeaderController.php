<?php

namespace api\modules\transaksi\controllers;

use yii;
use yii\helpers\Json;
use yii\rest\ActiveController;
use yii\data\ActiveDataProvider;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\QueryParamAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;

use api\modules\transaksi\models\TransPenjualanHeader;

class TransPenjualanHeaderController extends ActiveController
{

	public $modelClass = 'api\modules\transaksi\models\TransPenjualanHeader';

	/**
     * Behaviors
	 * Mengunakan Auth HttpBasicAuth.
	 * Chacking kontrolgampang\login.
     */
    public function behaviors()    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => 
            [
                'class' => CompositeAuth::className(),
				'authMethods' => 
                [
                    #Hapus Tanda Komentar Untuk Autentifikasi Dengan Token               
                   // ['class' => HttpBearerAuth::className()],
                   // ['class' => QueryParamAuth::className(), 'tokenParam' => 'access-token'],
                ],
                'except' => ['options']
            ],
			'bootstrap'=> 
            [
				'class' => ContentNegotiator::className(),
				'formats' => 
                [
					'application/json' => Response::FORMAT_JSON,"JSON_PRETTY_PRINT"
				],
				
			],
			'corsFilter' => [
				'class' => \yii\filters\Cors::className(),
				'cors' => [
					// restrict access to
					'Origin' => ['*','http://localhost:810'],
					'Access-Control-Request-Method' => ['POST', 'PUT','GET'],
					// Allow only POST and PUT methods
					'Access-Control-Request-Headers' => ['X-Wsse'],
					// Allow only headers 'X-Wsse'
					'Access-Control-Allow-Credentials' => true,
					// Allow OPTIONS caching
					'Access-Control-Max-Age' => 3600,
					// Allow the X-Pagination-Current-Page header to be exposed to the browser.
					'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page'],
				]		
			],
			/* 'corsFilter' => [
				'class' => \yii\filters\Cors::className(),
				'cors' => [
					'Origin' => ['*'],
					'Access-Control-Allow-Headers' => ['X-Requested-With','Content-Type'],
					'Access-Control-Request-Method' => ['POST', 'GET', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
					//'Access-Control-Request-Headers' => ['*'],					
					'Access-Control-Allow-Credentials' => true,
					'Access-Control-Max-Age' => 3600,
					'Access-Control-Expose-Headers' => ['X-Pagination-Current-Page']
					]		 
			], */
        ]);		
    }
	
	public function actions()
	{
		$actions = parent::actions();
		unset($actions['index'], $actions['update'], $actions['create'], $actions['delete'], $actions['view'],$actions['store']);
		//unset($actions['update'], $actions['create'], $actions['delete'], $actions['view']);
		return $actions;
	}
	
	public function actionCreate()
    {        
		$paramsBody 	= Yii::$app->request->bodyParams;	
		$metode			= isset($paramsBody['METHODE'])!=''?$paramsBody['METHODE']:'';		
		//KEY
		$store_id			= isset($paramsBody['STORE_ID'])!=''?$paramsBody['STORE_ID']:'';
		$transHeaderKey1	= isset($paramsBody['TRANS_ID'])!=''?$paramsBody['TRANS_ID']:'';
		$transHeaderKey2	= isset($paramsBody['OFLINE_ID'])!=''?$paramsBody['OFLINE_ID']:'';
		$tglTrans			= isset($paramsBody['TRANS_DATE'])!=''?$paramsBody['TRANS_DATE']:'';
		$accessId			= isset($paramsBody['ACCESS_ID'])!=''?$paramsBody['ACCESS_ID']:'';
		
		if($metode=='GET'){
			/**
			* @author 		: ptrnov  <piter@lukison.com>
			* @since 		: 1.2
			* Subject		: TRANSAKSI HEADER.
			* Metode		: POST (VIEW)
			* URL			: http://production.kontrolgampang.com/transaksi/trans-penjualan-heades
			* Body Param	: METHODE=GET & TRANS_ID(Master Key) OR  OFLINE_ID(Master key) OR  STORE_ID(key) OR CREATE_AT(Filter)
			*				: STORE_ID='' All Transaksi Header and Detail.  				    (LIST ALL)
			*				: STORE_ID<>''Transaksi Header and Detail By TRANS_ID or OFLINE_ID. (SINGLE DATA)
			*				: CREATE_AT<>'' Filter By date.
			*				: TRANS_ID=(CREATE AUTOMATICLY DATABASE);OFLINE_ID=(CREATE FROM MOBILE)
			*/
			if($store_id<>''){				
				//MODEL TARNS HEADER BY STORE_ID
				if($tglCreate<>''){	
					$modelCnt= TransPenjualanHeader::find()->where(['STORE_ID'=>$store_id])->andWhere(['like','CREATE_AT',$tglCreate])->count();
					$model= TransPenjualanHeader::find()->where(['STORE_ID'=>$store_id])->andWhere(['like','CREATE_AT',$tglCreate])->all();		
					if($modelCnt){
						return array('LIST_TRANS_HEADER'=>$model);
					}else{
						return array('result'=>'data-empty');
					};
				}else{
					$modelCnt= TransPenjualanHeader::find()->where(['STORE_ID'=>$store_id])->count();
					$model= TransPenjualanHeader::find()->where(['STORE_ID'=>$store_id])->all();		
					if($modelCnt){
						return array('LIST_TRANS_HEADER'=>$model);
					}else{
						return array('result'=>'data-empty');
					};
				}
			}else{
				//TARNS HEADER BY TRANS_ID
				$modelCnt= TransPenjualanHeader::find()->where(['TRANS_ID'=>$transHeaderKey1])->orWhere(['OFLINE_ID'=>$transHeaderKey2])->count();
				$model= TransPenjualanHeader::find()->where(['TRANS_ID'=>$transHeaderKey1])->orWhere(['OFLINE_ID'=>$transHeaderKey2])->one();		
				if($modelCnt){			
					return array('LIST_TRANS_HEADER'=>$model);
				}else{
					return array('result'=>'data-empty');
				}		
			}			
		}elseif($metode=='POST'){
			/**
			* @author 		: ptrnov  <piter@lukison.com>
			* @since 		: 1.2
			* Subject		: TRANSAKSI HEADER.
			* Metode		: POST (CREATE)
			* URL			: http://production.kontrolgampang.com/transaksi/trans-penjualan-heades
			* Body Param	: METHODE=GET & TRANS_ID(Master Key) & OFLINE_ID(Master key) & STORE_ID(key) 
			*                 AND ACCESS_ID(key) & OR TRANS_DATE(Filter)
			*				: IF MERCHANT_ID=0 [CASH MANUAL] ELSE PEYMENT ONLINE
			*/
			$modelNew = new TransPenjualanHeader();
			$modelNew->scenario = "create";
			//==KEY=
			if ($store_id<>''){$modelNew->STORE_ID=$store_id;};
			if ($transHeaderKey2<>''){$modelNew->OFLINE_ID=$transHeaderKey2;};
			if ($tglTrans<>''){$modelNew->TRANS_DATE=$tglTrans;};
			if ($accessId<>''){$modelNew->ACCESS_ID=$accessId;};
			//==PROPERTIES=
			if ($store_id<>''){$modelNew->STORE_ID=$store_id;};
			if($modelNew->save()){
				$modelView=TransPenjualanHeader::find()->where(['OFLINE_ID'=>$transHeaderKey2])->orderBy(['ID' => SORT_DESC])->limit(1)->one();
				return array('LIST_TRANS_HEADER'=>$modelView);
			}else{
				return array('error'=>$modelNew->errors);
			}		
		};
	}
	
	public function actionUpdate()
    {  	
		/**
		* @author 		: ptrnov  <piter@lukison.com>
		* @since 		: 1.2
		* Subject		: TRANSAKSI HEADER.
		* Metode		: PUT (UPDATE)
		* URL			: http://production.kontrolgampang.com/transaksi/trans-penjualan-heades
		* Body Param	: TRANS_ID(Master Key) & OFLINE_ID(Master key)
		* PROPERTIES	: TOTAL_PRODUCT,SUB_TOTAL_HARGA,PPN,TOTAL_HARGA,CONSUMER_NM,CONSUMER_EMAIL,CONSUMER_PHONE,DCRP_DETIL,
		*				  MERCHANT_ID [TYPE_PAY_ID,TYPE_PAY_NM,BANK_ID,BANK_NM,MERCHANT_NM,MERCHANT_NO]->inquery
		*/
		$paramsBody 	= Yii::$app->request->bodyParams;	
		//==KEY==
		$transHeaderKey1	= isset($paramsBody['TRANS_ID'])!=''?$paramsBody['TRANS_ID']:'';
		$transHeaderKey2	= isset($paramsBody['OFLINE_ID'])!=''?$paramsBody['OFLINE_ID']:'';
		//==PROPERTIES===
		$ttlProduct			= isset($paramsBody['TOTAL_PRODUCT'])!=''?$paramsBody['TOTAL_PRODUCT']:'';
		$subTotalHarga		= isset($paramsBody['SUB_TOTAL_HARGA'])!=''?$paramsBody['SUB_TOTAL_HARGA']:'';
		$ppnPajak			= isset($paramsBody['PPN'])!=''?$paramsBody['PPN']:'';
		$totalHarga			= isset($paramsBody['TOTAL_HARGA'])!=''?$paramsBody['TOTAL_HARGA']:'';
		$cunsumerNm			= isset($paramsBody['CONSUMER_NM'])!=''?$paramsBody['CONSUMER_NM']:'';
		$cunsumerEmail		= isset($paramsBody['CONSUMER_EMAIL'])!=''?$paramsBody['CONSUMER_EMAIL']:'';
		$cunsumerPhone		= isset($paramsBody['CONSUMER_PHONE'])!=''?$paramsBody['CONSUMER_PHONE']:'';
		$note				= isset($paramsBody['DCRP_DETIL'])!=''?$paramsBody['DCRP_DETIL']:'';
		$merchantId			= isset($paramsBody['MERCHANT_ID'])!=''?$paramsBody['MERCHANT_ID']:'';
		
		$modelEdit=TransPenjualanHeader::find()->where(['TRANS_ID'=>$transHeaderKey1])->one();
		if($modelEdit){
			//==REQUIRED=
			if ($ttlProduct<>''){$modelEdit->TOTAL_PRODUCT=$ttlProduct;};
			//==PROPERTIES=
			if ($ttlProduct<>''){$modelEdit->PPN=$ttlProduct;};
			if ($ttlProduct<>''){$modelEdit->TOTAL_HARGA=$ttlProduct;};
			if ($ttlProduct<>''){$modelEdit->CONSUMER_NM=$ttlProduct;};
			if ($ttlProduct<>''){$modelEdit->CONSUMER_EMAIL=$ttlProduct;};
			if ($ttlProduct<>''){$modelEdit->CONSUMER_PHONE=$ttlProduct;};
			if ($ttlProduct<>''){$modelEdit->DCRP_DETIL=$ttlProduct;};
			if ($ttlProduct<>''){$modelEdit->MERCHANT_ID=$ttlProduct;};			
			$modelEdit->scenario = "update";
			if($modelEdit->save()){
				$modelView=TransPenjualanHeader::find()->where(['TRANS_ID'=>$transHeaderKey1])->all();
				return array('LIST_TRANS_HEADER'=>$modelView);
			}else{
				return array('error'=>$modelEdit->errors);
			}
		}else{
			return array('result'=>'save-not-exist');
		}
	}	
}
    
	
	
	
	
