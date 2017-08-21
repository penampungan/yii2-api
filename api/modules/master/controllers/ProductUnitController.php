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

use api\modules\master\models\ProductUnit;


/**
  * @author 	: ptrnov  <piter@lukison.com>
  * @since 		: 1.2
  * Subject		: PRODUCT UNIT ALL APP.
  * URL			: http://production.kontrolgampang.com/master/product-units
  * Body Param	: UNIT_ID(key Master)
 */
class ProductUnitController extends ActiveController
{	

    public $modelClass = 'api\modules\login\models\ProductUnit';

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
		$unitID			= isset($paramsBody['UNIT_ID'])!=''?$paramsBody['UNIT_ID']:'';
		$unitIdGrp		= isset($paramsBody['UNIT_ID_GRP'])!=''?$paramsBody['UNIT_ID_GRP']:'';
		//PROPERTY
		$unitNm			= isset($paramsBody['UNIT_NM'])!=''?$paramsBody['UNIT_NM']:'';
		$unitNote		= isset($paramsBody['DCRP_DETIL'])!=''?$paramsBody['NOTE']:'';
		
		if($metode=='GET'){
			/**
			  * @author 	: ptrnov  <piter@lukison.com>
			  * @since 		: 1.2
			  * Subject		: ALL UNIT PRODUCT.
			  * Metode		: POST (VIEW)
			  * URL			: http://production.kontrolgampang.com/master/product-units
			  * Body Param	: METHODE=GET & UNIT_ID(key Master),UNIT_ID_GRP(key Master)
			  *				 UNIT_ID_GRP<>'' AND UNIT_ID<>'' THEN  SHOW UNIT PROPERTIES WHERE UNIT_ID
			  *				 UNIT_ID_GRP<>'' AND UNIT_ID=='' THEN  SHOW ALL UNIT PROPERTIES  ON  UNIT_ID_GRP
			  *				 UNIT_ID_GRP=='' AND UNIT_ID=='' THEN  SHOW ALL UNIT.
			  *				 UNIT_ID_GRP=='' AND UNIT_ID<>'' THEN  SHOW UNIT PROPERTIES WHERE UNIT_ID.
			  *				 http://production.kontrolgampang.com/master/product-unit-groups	(UNIT_ID_GRP)-> ALL APP
			 */
			if($unitIdGrp<>''){	 
				if($unitID<>''){				
					//Model Per-UNIT
					$modelCnt= ProductUnit::find()->where(['UNIT_ID'=>$unitID,'UNIT_ID_GRP'=>$unitIdGrp])->count();
					$model= ProductUnit::find()->where(['UNIT_ID'=>$unitID,'UNIT_ID_GRP'=>$unitIdGrp])->one();		
					
					if($modelCnt){
						return array('LIST_PRODUCT_UNIT'=>$model);
					}else{
						return array('result'=>'data-empty');
					}		
				}else{
					//Model All UNIT
					//Model
					$modelCnt= ProductUnit::find()->where(['UNIT_ID_GRP'=>$unitIdGrp])->count();
					$model= ProductUnit::find()->where(['UNIT_ID_GRP'=>$unitIdGrp])->all();		
					
					if($modelCnt){			
						return array('LIST_PRODUCT_UNIT'=>$model);
					}else{
						return array('result'=>'data-empty');
					}		
				}
			}else{
				if($unitID<>''){				
					//Model Per-UNIT
					$modelCnt= ProductUnit::find()->where(['UNIT_ID'=>$unitID])->count();
					$model= ProductUnit::find()->where(['UNIT_ID'=>$unitID])->one();		
					
					if($modelCnt){
						return array('LIST_PRODUCT_UNIT'=>$model);
					}else{
						return array('result'=>'data-empty');
					}		
				}else{
					//Model All UNIT
					//Model
					$modelCnt= ProductUnit::find()->count();
					$model= ProductUnit::find()->all();		
					
					if($modelCnt){			
						return array('LIST_PRODUCT_UNIT'=>$model);
					}else{
						return array('result'=>'data-empty');
					}		
				}
			}
		}elseif($metode=='POST'){
			/**
			* @author 	: ptrnov  <piter@lukison.com>
			* @since 		: 1.2
			* Subject		: ALL UNIT PRODUCT.
			* Metode		: POST (CREATE)
			* URL			: http://production.kontrolgampang.com/master/product-units
			* Body Param	: METHODE=POST & UNIT_ID(key Master),UNIT_ID_GRP(key)
			* PROPERTY		: UNIT_NM,DCRP_DETIL
			*				  http://production.kontrolgampang.com/master/product-unit-groups	(UNIT_ID_GRP)-> ALL APP
			*/
			if($unitIdGrp<>''){
				 $modelNew = new ProductUnit();
				 if ($unitNm<>''){$modelNew->UNIT_NM=$unitNm;};
				 if ($unitNote<>''){$modelNew->DCRP_DETIL=$unitNote;};
				 if ($unitIdGrp<>''){$modelNew->UNIT_ID_GRP=$unitIdGrp;};
				 if($modelNew->save()){
					$rsltMax=ProductUnit::find()->max(UNIT_ID);
					$modelView=ProductUnit::find()->where(['UNIT_ID'=>$rsltMax])->one();
					return array('LIST_PRODUCT_UNIT'=>$modelView);
				 }else{
					return array('result'=>$modelNew->errors);
				 }
			}else{
				return array('result'=>'UNIT_ID_GRP-cannot-be-blank');
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
		* Subject		: ALL UNIT PRODUCT.
		* Metode		: PUT (UPDATE)
		* URL			: http://production.kontrolgampang.com/master/product-units
		* Body Param	: UNIT_ID(key Master)
		* PROPERTY		: UNIT_NM,DCRP_DETIL,STATUS
		*				 http://production.kontrolgampang.com/master/product-unit-groups	(UNIT_ID_GRP)-> ALL APP
		*/
		$paramsBody 	= Yii::$app->request->bodyParams;
		//KEY
		$unitID			= isset($paramsBody['UNIT_ID'])!=''?$paramsBody['UNIT_ID']:'';
		$unitIdGrp		= isset($paramsBody['UNIT_ID_GRP'])!=''?$paramsBody['UNIT_ID_GRP']:'';
		//PROPERTY
		$unitNm			= isset($paramsBody['UNIT_NM'])!=''?$paramsBody['UNIT_NM']:'';
		$unitNote		= isset($paramsBody['DCRP_DETIL'])!=''?$paramsBody['NOTE']:'';
		$stt			= isset($paramsBody['STATUS'])!=''?$paramsBody['STATUS']:'';
		
		if($unitID<>''){
			$modelEdit = ProductUnit::find()->where(['UNIT_ID'=>$unitID])->one();
			if($modelEdit){
				 if ($unitNm<>''){$modelEdit->UNIT_NM=$unitNm;};
				 if ($unitNote<>''){$modelEdit->DCRP_DETIL=$unitNote;};
				 if ($unitIdGrp<>''){$modelEdit->UNIT_ID_GRP=$unitIdGrp;};
				 if ($stt<>''){$modelEdit->STATUS=$stt;};
				 if($modelEdit->save()){
					$modelView=ProductUnit::find()->where(['UNIT_ID'=>$unitID])->one();
					return array('LIST_PRODUCT_UNIT'=>$modelView);
				 }else{
					return array('result'=>$modelEdit->errors);
				 }
			 }else{
				 return array('result'=>'UNIT_ID-not-exist');
			 }
		}else{
			return array('result'=>'UNIT_ID-cannot-be-blank');
		}
	}
}


