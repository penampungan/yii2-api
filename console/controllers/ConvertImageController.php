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
class ConvertImageController extends Controller
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

	/**===================
	 *  STOCK OPNAME AHKIR BULAN 
	 * ===================
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
			}
			
		}
		
		
		// print_r($rslt);
		// die();	
	}
	
	
	/**
	*  CONVERT BASE64 TO IMAGE
	*  Create by: ptr.nov@gmail.com
	*/
	private function base64toimage($string ='',$fileNm){
		$rootPathImageZip='/var/www/KG_IMAGE/produk/';
		$dataImgScr=$string;
		$namefile=$fileNm;
		$img = str_replace('data:image/*;charset=utf-8;base64,', '', $dataImgScr);
		$img = str_replace(' ', '+', $img);
		$data = imagecreatefromstring(base64_decode($img));
		$file = $rootPathImageZip.$namefile.'.jpg';
		
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
		imagejpeg($image_p,$file);
		// Free up memory
		imagedestroy($image_p);

		//$success = file_put_contents($file, $image_p);
		return true;	
	}
}
?>