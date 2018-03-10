<?php

namespace api\modules\laporan\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Response;
use yii\data\ArrayDataProvider;
use yii\base\Model;
use \yii\base\DynamicModel;
use yii\debug\components\search\Filter;
use yii\debug\components\search\matchers;
use api\modules\laporan\models\Store;

class FrkProdukHarianStore extends DynamicModel
{
	// public $ACCESS_GROUP;
	// public $STORE_ID;
	
	public function rules()
    {
        return [
            [['ACCESS_GROUP','STORE_ID','TGL'], 'safe'],
		];	

    }

	public function fields()
	{
		return [			
			'chart'=>function($model){
				return self::chartlabel();
			},
			// 'categories'=>function(){
				// return [
					// self::categorieslabel()
				// ];
			// },
			'dataset'=>function($model){
				return self::frekuensiTransaksiHarianToko();
			}
		];
	}
	
	public function frekuensiTransaksiHarianToko(){
		$varTgl=$this->TGL!=''?$this->TGL:date('Y-m-d');
		$valAccessGoup=$this->ACCESS_GROUP!=''?$this->ACCESS_GROUP:'1';
		$modelStore=Store::find()->where(['ACCESS_GROUP'=>$valAccessGoup])->all();
		foreach($modelStore as $rowStore => $valStore){
			$sql="
				SELECT 
					a1.ACCESS_GROUP,a1.STORE_ID,a1.TAHUN,a1.BULAN,a1.TGL,
					a1.PRODUCT_ID,a2.PRODUCT_NM,a1.PRODUK_SUBTTL_QTY
				FROM 
				(
					SELECT 
						ACCESS_GROUP,STORE_ID,TAHUN,BULAN,TGL,
						PRODUCT_ID,SUM(PRODUK_SUBTTL_QTY) AS PRODUK_SUBTTL_QTY
					FROM ptr_kasir_td1a
					#WHERE ACCESS_GROUP='170726220936' AND STORE_ID='170726220936.0001' AND TGL='2018-01-26'	
					WHERE ACCESS_GROUP='".$valAccessGoup."' AND STORE_ID='".$valStore['STORE_ID']."' AND TGL='2018-01-26'						
					GROUP BY ACCESS_GROUP,STORE_ID,PRODUCT_ID
				) a1 LEFT JOIN product a2 
				ON a2.ACCESS_GROUP=a1.ACCESS_GROUP AND 
				   a2.STORE_ID=a1.STORE_ID AND 
				   a2.PRODUCT_ID=a1.PRODUCT_ID
			";					
			$qrySql= Yii::$app->production_api->createCommand($sql)->queryAll(); 		
			$dataProvider= new ArrayDataProvider([	
				'allModels'=>$qrySql,	
				'pagination' => [
					'pageSize' =>1000,
				],			
			]);
			
			// $filter = new Filter();
			// $this->addCondition($filter, 'ACCESS_GROUP', true);	
			// $this->addCondition($filter, 'STORE_ID', true);	
			// $dataProvider->allModels = $filter->filter($qrySql);
		   // return ['Frekuensi_Transaksi_Harian'=>$dataProvider->getModels()];
		   
			$modelProduk=$dataProvider->getModels();
			if ($modelProduk){	
				foreach ($modelProduk as $row => $val){
					$rslt1['seriesname']=$valStore['STORE_NM'];
					//$dataval1=[];
					//=[3]==LOOPING 24 hour
					//for( $i= 1 ; $i <= 24 ; $i++ ) {
						$dataval1[]=['label'=>$val['PRODUCT_NM'],'value'=>$val['PRODUK_SUBTTL_QTY'],'anchorBgColor'=>'#00fd83'];
					//}
				
					//=[4]==SETTING ARRAY
					
					//$rsltDataSet1['data']=$dataval1;
				}
				$rslt1['data']=$dataval1;	
			}
			$datasetRslt[]=$rslt1;
			
		}
		//return $datasetRslt;
		
		return $datasetRslt;
	} 

	private function chartlabel(){
		$varTahun		= $this->TGL!=''?date('Y',strtotime($this->TGL)):date('Y');
		$varBulan		= $this->TGL!=''?date('m',strtotime($this->TGL)):date('m');
		$varTgl			= $this->TGL!=''?date('Y-m-d',strtotime($this->TGL)):date('Y-m-d');		
		$nmBulan=date('F', strtotime($varTahun.'-'.'0'.$varBulan.'-01')); // Nama Bulan
		$chart=[
			"caption"=> "Jumlah Produk Terjual",
			"subCaption"=> "Tanggal ".$varTgl,
			"captionFontSize"=> "12",
			"subcaptionFontSize"=> "10",
			"subcaptionFontBold"=> "0",
			"showShadow"=> "0",
			"enableSmartLabels"=> "0",
			"startingAngle"=> "180",
			"showLabels"=> "1",
			"pieRadius"=> "80",
			"theme"=> "0",
		    "useDataPlotColorForLabels"=> "0",	
			"bgcolor"=> "#ffffff",
			"showBorder"=> "0",
			"showShadow"=> "0",
			"usePlotGradientColor"=> "0",					
			"plotHighlightEffect"=> "fadeout|color=#f6f5fd, alpha=60",
			//===Tools Tips ==
			"showValues"=> "1",
			"rotateValues"=> "1",
			"placeValuesInside"=> "1",
			"formatNumberScale"=> "0",
			"decimalSeparator"=> ",",
			"thousandSeparator"=> ".",
			"numberPrefix"=> "",
			"ValuePadding"=> "0",
			//==TITLE==
			"xAxisName"=> "Produk",
			"yAxisName"=> "Qty Transaksi Produk",
			"yAxisNameBorderColor"=> "#6666FF",
			"yAxisNameBorderAlpha"=> "50",
			"yAxisNameBorderPadding"=> "6",
			"yAxisNameBorderRadius"=> "3",
			"yAxisNameBorderThickness"=> "2",
			"yAxisNameBorderDashed"=> "1",
			"yAxisNameBorderDashLen"=> "4",
			"yAxisNameBorderDashGap"=> "2",
			"xAxisNameBorderColor"=> "#6666FF",
			"xAxisNameBorderAlpha"=> "50",
			"xAxisNameBorderPadding"=> "6",
			"xAxisNameBorderRadius"=> "3",
			"xAxisNameBorderThickness"=> "2",
			"xAxisNameBorderDashed"=> "1",
			"xAxisNameBorderDashLen"=> "4",
			"xAxisNameBorderDashGap"=> "2",	
			"yAxisValuesStep"=> "1",
			"xAxisValuesStep"=> "0",
			"yAxisMinValue"=> "0",
			//== grid dalam ==
			"numDivLines"=> "1",
			"showAxisLines"=> "1",
			"showAlternateHGridColor"=> "1",
			"divlineThickness"=> "0",
			"divLineIsDashed"=> "0",
			"divLineDashLen"=> "0",
			"divLineGapLen"=> "0",
			"vDivLineDashed"=> "0",
			"numVDivLines"=> "0",
			"vDivLineThickness"=> "0",	
			"xAxisNamePadding"=> "0",			
			//==LEGEND==
			//"legendBorderAlpha"=> "0",
			"legendShadow"=> "0",
			 //"legendAllowDrag"=> "0",			//=== DRAG POSITION LEGEND
			 "legendPosition"=>"right",			//=== POSISI LEGEND
			// "legendIconSides"=> "5",
			// "legendIconStartAngle"=> "60",
			//"legendCaption"=> "per-Toko",
			//"legendCaptionBold"=> "1",
			//"legendCaptionFont"=> "Arial",
			//"legendCaptionFontSize"=> "10",
			//"legendCaptionFontColor"=> "#333333"
		];
		return $chart;
	}
	
	private function categorieslabel(){
		$categories=[
			"category"=>[
				[
					"label"=> "01"
				],
				[
					"label"=> "02"
				],
				[
					"label"=> "03"
				],
				[
					"label"=> "04"
				],
				[
					"label"=> "05"
				],
				[
					"label"=> "06"
				],
				[
					"label"=> "07"
				],
				[
					"label"=> "08"
				],
				[
					"label"=> "09"
				],
				[
					"label"=> "10"
				],
				[
					"label"=> "11"
				],
				[
					"label"=> "12"
				],
				[
					"label"=> "13"
				],
				[
					"label"=> "14"
				],
				[
					"label"=> "15"
				],
				[
					"label"=> "16"
				],
				[
					"label"=> "17"
				],
				[
					"label"=> "18"
				],
				[
					"label"=> "19"
				],
				[
					"label"=> "20"
				],
				[
					"label"=> "21"
				],
				[
					"label"=> "22"
				],
				[
					"label"=> "23"
				],
				[
					"label"=> "24"
				]						
			]
		 ];
		 return $categories;
	}
	
	private function addCondition(Filter $filter, $attribute, $partial = false)
    {
        $value = $this->$attribute;

        if (mb_strpos($value, '>') !== false) {
            $value = intval(str_replace('>', '', $value));
            $filter->addMatcher($attribute, new matchers\GreaterThan(['value' => $value]));

        } elseif (mb_strpos($value, '<') !== false) {
            $value = intval(str_replace('<', '', $value));
            $filter->addMatcher($attribute, new matchers\LowerThan(['value' => $value]));
        } else {
            $filter->addMatcher($attribute, new matchers\SameAs(['value' => $value, 'partial' => $partial]));
        }
    }
}
