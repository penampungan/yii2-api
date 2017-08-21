<?php

namespace api\modules\master\controllers;

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

use api\modules\master\models\Product;


/**
  * @author 	: ptrnov  <piter@lukison.com>
  * @since 		: 1.2
  * Subject		: ONE PRODUCT PER-STORE.
  * Metode		: POST (update)
  * URL			: http://production.kontrolgampang.com/master/products
  * Body Param	: PRODUCT_ID(key)
 */
class ProductController extends ActiveController
{	

    public $modelClass = 'api\modules\login\models\Product';

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
		$productID		= isset($paramsBody['PRODUCT_ID'])!=''?$paramsBody['PRODUCT_ID']:'';
		$store_id		= isset($paramsBody['STORE_ID'])!=''?$paramsBody['STORE_ID']:'';
		//PROPERTY
		$prdNm			= isset($paramsBody['PRODUCT_NM'])!=''?$paramsBody['PRODUCT_NM']:'';
		$prdQr			= isset($paramsBody['PRODUCT_QR'])!=''?$paramsBody['PRODUCT_QR']:'';
		$prdGrp			= isset($paramsBody['GROUP_ID'])!=''?$paramsBody['GROUP_ID']:'';
		$prdWarna		= isset($paramsBody['PRODUCT_WARNA'])!=''?$paramsBody['PRODUCT_WARNA']:'';
		//--ukuran dalam desimal
		$prdUkuran		= isset($paramsBody['PRODUCT_SIZE'])!=''?$paramsBody['PRODUCT_SIZE']:'';
		$prdUkuranUnit	= isset($paramsBody['PRODUCT_SIZE_UNIT'])!=''?$paramsBody['PRODUCT_SIZE_UNIT']:'';		
		//--ukuran dalam desimal
		$prdUnitJual	= isset($paramsBody['UNIT_ID'])!=''?$paramsBody['UNIT_ID']:'';
		$prdHeadline	= isset($paramsBody['PRODUCT_HEADLINE'])!=''?$paramsBody['PRODUCT_HEADLINE']:'';
		$prdLevelStock	= isset($paramsBody['STOCK_LEVEL'])!=''?$paramsBody['STOCK_LEVEL']:'';
		//Industri
		$prdIndustriId	= isset($paramsBody['INDUSTRY_ID'])!=''?$paramsBody['INDUSTRY_ID']:'';
		//Releatiship (check by date)
        //-harga;-Discount;-stock;-Promo
		
		if($metode=='GET'){
			/**
			  * @author 	: ptrnov  <piter@lukison.com>
			  * @since 		: 1.2
			  * Subject		: ALL PRODUCT PER-STORE ATAU ONE PRODUCT (Select Key).
			  * Metode		: POST (VIEW)
			  * URL			: http://production.kontrolgampang.com/master/products
			  * Body Param	: METHODE=GET & PRODUCT_ID(key) & STORE_ID(Key)
			  *				: PRODUCT_ID='' maka semua prodak pada STORE_ID di tampilkan.
			  *				: PRODUCT_ID<>'' maka yang di tampilkan satu product.
			 */
			if($productID<>''){				
				//Model Per-Product
				$modelCnt= Product::find()->where(['PRODUCT_ID'=>$productID])->count();
				$model= Product::find()->where(['PRODUCT_ID'=>$productID])->one();		
				
				if($modelCnt){
					return array('LIST_PRODUCT'=>$model);
				}else{
					return array('result'=>'data-empty');
				}		
			}else{
				//Model All Product per-STORE
				//Model
				$modelCnt= Product::find()->where(['STORE_ID'=>$store_id])->count();
				$model= Product::find()->where(['STORE_ID'=>$store_id])->all();		
				
				if($modelCnt){			
					return array('LIST_PRODUCT'=>$model);
				}else{
					return array('result'=>'data-empty');
				}		
			}			
		}elseif($metode=='POST'){
			/**
			* @author 	: ptrnov  <piter@lukison.com>
			* @since 		: 1.2
			* Subject		: ADD PRODUCT PER-STORE.
			* Metode		: POST (CREATE)
			* URL			: http://production.kontrolgampang.com/master/products
			* Body Param	: METHODE=POST & STORE_ID(Primary Key), GROUP_ID(key),UNIT_ID(Key),INDUSTRY_ID(key)
			* PROPERTY		: PRODUCT_NM,PRODUCT_QR,PRODUCT_WARNA,PRODUCT_SIZE,PRODUCT_SIZE_UNIT,PRODUCT_HEADLINE,STOCK_LEVEL
			* SUPPORT		: http://production.kontrolgampang.com/master/product-groups (GROUP_ID)
			*				  http://production.kontrolgampang.com/master/product-units	 (UNIT_ID)
			*				  http://production.kontrolgampang.com/master/product-industris (INDUSTRY_ID)
			*				  http://production.kontrolgampang.com/master/product-stocks 	(releationship by date)
			*				  http://production.kontrolgampang.com/master/product-discounts (releationship by date)
			*				  http://production.kontrolgampang.com/master/product-hargas    (releationship by date)
			*				  http://production.kontrolgampang.com/master/product-promos    (releationship by date)
			*/
			if($store_id<>''){
				 $modelNew = new Product();
				 $modelNew->CREATE_AT=date('Y-m-d H:i:s');
				 $modelNew->STORE_ID=$store_id;
				 if ($prdNm<>''){$modelNew->PRODUCT_NM=$prdNm;};
				 if ($prdQr<>''){$modelNew->PRODUCT_QR=$prdQr;};
				 if ($prdWarna<>''){$modelNew->PRODUCT_WARNA=$prdWarna;};
				 if ($prdUkuran<>''){$modelNew->PRODUCT_SIZE=$prdUkuran;};
				 if ($prdUkuranUnit<>''){$modelNew->PRODUCT_SIZE_UNIT=$prdUkuranUnit;};
				 if ($prdHeadline<>''){$modelNew->PRODUCT_HEADLINE=$prdHeadline;};
				 if ($prdLevelStock<>''){$modelNew->STOCK_LEVEL=$prdLevelStock;};
				 if ($prdGrp<>''){$modelNew->GROUP_ID=$prdGrp;};
				 if ($prdUnitJual<>''){$modelNew->UNIT_ID=$prdUnitJual;};
				 if ($prdIndustriId<>''){$modelNew->INDUSTRY_ID=$prdIndustriId;};
				 if($modelNew->save()){
					$rsltMax=Product::find()->where(['STORE_ID'=>$store_id])->max(PRODUCT_ID);
					$modelView=Product::find()->where(['PRODUCT_ID'=>$rsltMax])->one();
					return array('LIST_PRODUCT'=>$modelView);
				 }else{
					return array('result'=>$modelNew->errors);
				 }
			}else{
				return array('result'=>'STORE_ID-cannot-be-blank');
			}
		}else{
			return array('result'=>'Methode-Unknown');
		}		
	}
	
	public function actionUpdate()
    {  	
		/**
		* @author 	: ptrnov  <piter@lukison.com>
		* @since 		: 1.2
		* Subject		: UPDATE PRODUCT.
		* Metode		: PUT (UPDATE)
		* URL			: http://production.kontrolgampang.com/master/products
		* Body Param	: PRODUCT_ID(Primary Key), GROUP_ID(key),UNIT_ID(Key),INDUSTRY_ID(key)
		* PROPERTY		: PRODUCT_NM,PRODUCT_QR,PRODUCT_WARNA,PRODUCT_SIZE,PRODUCT_SIZE_UNIT,PRODUCT_HEADLINE,STOCK_LEVEL,STATUS
		* SUPPORT		: http://production.kontrolgampang.com/master/product-groups (GROUP_ID)
		*				  http://production.kontrolgampang.com/master/product-units	 (UNIT_ID)
		*				  http://production.kontrolgampang.com/master/product-industris (INDUSTRY_ID)
		*				  http://production.kontrolgampang.com/master/product-stocks 	(releationship by date)
		*				  http://production.kontrolgampang.com/master/product-discounts (releationship by date)
		*				  http://production.kontrolgampang.com/master/product-hargas    (releationship by date)
		*				  http://production.kontrolgampang.com/master/product-promos    (releationship by date)
		*/
		$paramsBody 	= Yii::$app->request->bodyParams;
		//KEY
		$productID		= isset($paramsBody['PRODUCT_ID'])!=''?$paramsBody['PRODUCT_ID']:'';
		//PROPERTY
		$prdNm			= isset($paramsBody['PRODUCT_NM'])!=''?$paramsBody['PRODUCT_NM']:'';
		$prdQr			= isset($paramsBody['PRODUCT_QR'])!=''?$paramsBody['PRODUCT_QR']:'';
		$prdGrp			= isset($paramsBody['GROUP_ID'])!=''?$paramsBody['GROUP_ID']:'';
		$prdWarna		= isset($paramsBody['PRODUCT_WARNA'])!=''?$paramsBody['PRODUCT_WARNA']:'';
		//--ukuran dalam desimal
		$prdUkuran		= isset($paramsBody['PRODUCT_SIZE'])!=''?$paramsBody['PRODUCT_SIZE']:'';
		$prdUkuranUnit	= isset($paramsBody['PRODUCT_SIZE_UNIT'])!=''?$paramsBody['PRODUCT_SIZE_UNIT']:'';		
		//--ukuran dalam desimal
		$prdUnitJual	= isset($paramsBody['UNIT_ID'])!=''?$paramsBody['UNIT_ID']:'';
		$prdHeadline	= isset($paramsBody['PRODUCT_HEADLINE'])!=''?$paramsBody['PRODUCT_HEADLINE']:'';
		$prdLevelStock	= isset($paramsBody['STOCK_LEVEL'])!=''?$paramsBody['STOCK_LEVEL']:'';
		//Industri
		$prdIndustriId	= isset($paramsBody['INDUSTRY_ID'])!=''?$paramsBody['INDUSTRY_ID']:'';
		$stt			= isset($paramsBody['STATUS'])!=''?$paramsBody['STATUS']:'';
		//Releatiship (check by date)
        //-harga;-Discount;-stock;-Promo
		if($productID<>''){
			$modelEdit = Product::find()->where(['PRODUCT_ID'=>$productID])->one();
			if($modelEdit){
				 if ($prdNm<>''){$modelEdit->PRODUCT_NM=$prdNm;};
				 if ($prdQr<>''){$modelEdit->PRODUCT_QR=$prdQr;};
				 if ($prdWarna<>''){$modelEdit->PRODUCT_WARNA=$prdWarna;};
				 if ($prdUkuran<>''){$modelEdit->PRODUCT_SIZE=$prdUkuran;};
				 if ($prdUkuranUnit<>''){$modelEdit->PRODUCT_SIZE_UNIT=$prdUkuranUnit;};
				 if ($prdHeadline<>''){$modelEdit->PRODUCT_HEADLINE=$prdHeadline;};
				 if ($prdLevelStock<>''){$modelEdit->STOCK_LEVEL=$prdLevelStock;};
				 if ($prdGrp<>''){$modelEdit->GROUP_ID=$prdGrp;};
				 if ($prdUnitJual<>''){$modelEdit->UNIT_ID=$prdUnitJual;};
				 if ($prdIndustriId<>''){$modelEdit->INDUSTRY_ID=$prdIndustriId;};
				 if ($stt<>''){$modelEdit->STATUS=$stt;};
				 if($modelEdit->save()){
					$modelView=Product::find()->where(['PRODUCT_ID'=>$productID])->one();
					return array('LIST_PRODUCT'=>$modelView);
				 }else{
					return array('result'=>$modelEdit->errors);
				 }
			 }else{
				 return array('result'=>'product-not-exist');
			 }
		}else{
			return array('result'=>'productID-cannot-be-blank');
		}
	}
}


