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

class ChartDetailSalesHarianTunai extends DynamicModel
{
	public function rules()
    {
        return [
            [['ACCESS_GROUP','STORE_ID','PERANGKAT','TGL','PILIH'], 'safe'],
		];	
    }

	public function fields()
	{
		return [			
			'chart'=>function($model){
				return self::chartlabel();
			},
			'data'=>function($model){
				return self::dataChart();
			}
		];
	}
	
	private function getData(){
		$valAccessGoup=$this->ACCESS_GROUP!=''?$this->ACCESS_GROUP:'';
		
		if($this->STORE_ID=='' OR $this->ACCESS_GROUP==$this->STORE_ID){
			$sql="
				#==GROUPING===
				SELECT	ACCESS_GROUP,STORE_ID,BULAN,TAHUN,DATE(TRANS_DATE) AS TGL,
						sum(PRODUK_TUNAI_JUALPPNDISCOUNT) AS PENDAPATAN_TUNAI,
						sum(PRODUK_NONTUNAI_JUALPPNDISCOUNT) AS PENDAPATAN_NONTUNAI	
				FROM ptr_kasir_td3a
				WHERE ACCESS_GROUP='".$valAccessGoup."'  
					  AND TAHUN=YEAR('".$this->TGL."')
					  AND BULAN=MONTH('".$this->TGL."')
				GROUP BY ACCESS_GROUP,TAHUN,BULAN
				ORDER BY ACCESS_GROUP,TAHUN,BULAN ASC;		
			";	
		}else{
			$sql="
				#==STORE_ID===
				SELECT	ACCESS_GROUP,STORE_ID,BULAN,TAHUN,DATE(TRANS_DATE) AS TGL,
						sum(PRODUK_TUNAI_JUALPPNDISCOUNT) AS PENDAPATAN_TUNAI,
						sum(PRODUK_NONTUNAI_JUALPPNDISCOUNT) AS PENDAPATAN_NONTUNAI	
				FROM ptr_kasir_td3a
				WHERE ACCESS_GROUP='".$valAccessGoup."'  
					  AND STORE_ID='".$this->STORE_ID."'
					  AND TAHUN=YEAR('".$this->TGL."')
					  AND BULAN=MONTH('".$this->TGL."')
				GROUP BY ACCESS_GROUP,TAHUN,BULAN
				ORDER BY ACCESS_GROUP,TAHUN,BULAN ASC;
			";	
		}
				
		
		$qrySql= Yii::$app->production_api->createCommand($sql)->queryAll(); 		
		$dataProvider= new ArrayDataProvider([	
			'allModels'=>$qrySql,	
			'pagination' => [
				'pageSize' =>1000,
			],			
		]);
		$modelMonth=$dataProvider->getModels();		
		return $modelMonth;
	}
	
	public function dataChart(){
		
		$modelMonth=self::getData();
		if($modelMonth){			
			$dataval1=[];			
			foreach ($modelMonth as $row => $val){				
				$data[]=["label"=> "TUNAI",'value'=>$val['PENDAPATAN_TUNAI']];
				$data[]=["label"=> "NON-TUNAI",'value'=>$val['PENDAPATAN_NONTUNAI']];
			};
		}else{
			$data[]=["label"=> "NONE",'value'=>"0"];
		}	
		return $data;
	}
	
	private function chartlabel(){
		$nmBulan		= date('F', strtotime($this->TGL)); // Nama Bulan
		$varTahun		= date('Y', strtotime($this->TGL));				
		$chart=[
			"caption"=> "Pendapatan",
			"subCaption"=> "Periode ".$this->TGL,
			"captionFontSize"=>"12",
			"subcaptionFontSize"=>"10",
			"subcaptionFontBold"=>"0",
			"numberPrefix"=> "$",
			"paletteColors"=> "#0075c2,#1aaf5d,#f2c500,#f45b00,#8e0000",
			"bgColor"=> "#ffffff",
			"showBorder"=> "0",
			"use3DLighting"=> "0",
			"showShadow"=> "0",
			"enableSmartLabels"=> "0",
			"startingAngle"=> "310",
			"showLabels"=> "0",
			"showPercentValues"=> "1",
			"showLegend"=> "1",
			"legendShadow"=> "0",
			"legendBorderAlpha"=> "0",                                
			"decimals"=> "0",
			"toolTipColor"=> "#ffffff",
			"toolTipBorderThickness"=> "0",
			"toolTipBgColor"=> "#000000",
			"toolTipBgAlpha"=> "80",
			"toolTipBorderRadius"=> "2",
			"toolTipPadding"=> "5",
		];
		return $chart;
	}	
}
