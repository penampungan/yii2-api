<?php
namespace console\controllers;

use Yii;
use yii\base\DynamicModel;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Request;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;
use yii\console\Controller;			
use yii\filters\VerbFilter;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use console\models\Product;
use console\models\ProductImage;
use console\models\Cronjob;

class ApiConvertImageController extends Controller
{
    public function behaviors()
    {
        return [
			'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ]
        ];
    }

	/**=============================
	 *  START MANUAL BUILD ALL IMAGE
	 * =============================
	*/
	public function actionStartAll(){
		
		//$model = Store::find()->OrderBy(['ACCESS_GROUP'=>SORT_ASC,'STORE_ID'=>SORT_ASC])->all();		
		$modelProduk = Product::find()->all();	
		foreach ($modelProduk as $row => $val){
			// $rslt[]=$val['PRODUCT_ID'];
			$modelImage = ProductImage::find()->where(['PRODUCT_ID'=>$val['PRODUCT_ID']])->One();
			if ($modelImage['PRODUCT_ID']!=''){
				$rslt =self::base64toimage($modelImage['PRODUCT_IMAGE'],$modelImage['PRODUCT_ID']);
				//$rslt[]=$modelImage['PRODUCT_ID'];
				$produkUpdate = Product::find()->where(['PRODUCT_ID'=>$val['PRODUCT_ID']])->One();
				$produkUpdate->IMG_FILE=$rslt;
				$produkUpdate->save();
			}
			
		}
		
		
		// print_r($rslt);
		// die();	
	}
	
	/**============================================================
	 * == START OTOMATIS EXPORT IMAGE PRODUCT => TRDIGER CRONJOB ==
	 * ============================================================
	*/
	public function actionStartTrigerImage(){
		$modelCronjob = Cronjob::find()->where(['TABEL'=>'PRODUK_IMAGE','STT'=>1])->all();	
		 foreach ($modelCronjob as $row => $val){
			 //print_r($rslt[]=$val['STORE_ID']);
			 $modelProduk = Product::find()->where(['STORE_ID'=>$val['STORE_ID'],'IMG_FILE'=>0])->all();	
			
			  foreach ($modelProduk as $row1 => $val1){
				//$rslt[]=$val1['PRODUCT_ID'];
				$modelImage = ProductImage::find()->where(['PRODUCT_ID'=>$val1['PRODUCT_ID']])->One();
				if ($modelImage['PRODUCT_ID']!=''){
					$rslt =self::base64toimage($modelImage['PRODUCT_IMAGE'],$modelImage['PRODUCT_ID']);
					//$rslt[]=$modelImage['PRODUCT_IMAGE'];
					$produkUpdate = Product::find()->where(['PRODUCT_ID'=>$val1['PRODUCT_ID']])->One();
					$produkUpdate->IMG_FILE=$rslt;
					$produkUpdate->save();
					/* if ($rslt<>0){
						//==SEND TO POLLING
						
					} */
				}		 	
			}
			$modelUpdate = Cronjob::find()->where(['TABEL'=>$val['TABEL'],'ACCESS_GROUP'=>$val['ACCESS_GROUP'],'STORE_ID'=>$val['STORE_ID']])->one();
			$modelUpdate->STT=0;
			$modelUpdate->UPDATE_CRONJOB=$val['UPDATE_TABEL'];
			$modelUpdate->save();
			
			//print_r($rslt);
		 }
	}
	/**
	*  CONVERT BASE64 TO IMAGE
	*  Create by: ptr.nov@gmail.com
	*/
	private function base64toimage($string64 ='',$fileNm){		
	
		$rootPathImageZip='/var/www/KG_IMAGE/produk/';
		// $dataImgScr=$string;
		// $namefile=$fileNm;			
		// $img = str_replace('data:image/jpeg;base64,','',trim($dataImgScr));   //PR OTHER EXTENTION [png,bmp]
		// $img1 = str_replace('charset=utf-8','',$img);
		// $img2 = str_replace(' ', '+', $img1);
		$dataImgScr=str_replace('charset=utf-8;','',$string64);//$string64;//str_replace(' ', '+', $$string64);//$string64;
		$namefile=$fileNm;			
		 // $img = str_replace('data:image/jpeg;base64,','',trim($dataImgScr));   //PR OTHER EXTENTION [png,bmp]
		 // $img1 = str_replace('charset=utf-8','',$img);
		 // $img2 = str_replace(' ', '+', $img1);
		 //----------------- cara2 ---
		list($type, $dataImgScr1) = explode(';', $dataImgScr);
		list(,$extension) = explode('/',$type);
		//list(,$dataImgScr2)      = explode(',', $dataImgScr);
		$img = str_replace($type.';base64,','',trim($dataImgScr));   //PR OTHER EXTENTION [png,bmp]
		//$fileName = uniqid().'.'.$extension;
		//$dataImgScr = base64_decode($dataImgScr);
		if(self::is_base64_encoded($img)){
			$data = imagecreatefromstring(base64_decode($img));
			// header('Content-Type: image/jpeg');
			
			 //RESIZE RATIO
			$width = 100;
			$height = 100;
			
			//GET IMAGE ORIGINAL SIZE
			list($width_orig, $height_orig) = getimagesizefromstring(base64_decode($img));
			$ratio_orig = $width_orig/$height_orig;
			//Calculete ratio Image
			if ($width/$height > $ratio_orig) {
			   $width = $height*$ratio_orig;
			} else {
			   $height = $width/$ratio_orig;
			}

			//Resize comment
			$image_p = imagecreatetruecolor($width, $height);
			//imagecopyresampled($image_p, $data, 0, 0, 0, 0, $new_w, $new_h, $org_w, $org_h);
			//imagecopyresampled($image_p, $data, 0, 0, 0, 0, 300, 150, 600,400);
			imagecopyresampled($image_p, $data, 0, 0, 0, 0, $width, $height, $width_orig, $height_orig); 
			
			//run convert
			 $extention='.jpeg';
			$file = $rootPathImageZip.$namefile.$extention;
			imagejpeg($data,$file);
			// Free up memory
			imagedestroy($data);

			//$success = file_put_contents($file, $image_p);
			return $namefile.$extention;	 
			//return self::is_base64_encoded($img2);  #check Base64
		}else{
			return $fileNm;
		}
		
	}
	
	private function is_base64_encoded($data)
	{
		if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
		   return 1;//TRUE;
		} else {
		   return 0;//FALSE;
		}
	}
}
?>