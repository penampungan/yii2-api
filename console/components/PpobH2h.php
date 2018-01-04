<?php
/**
 * Created by PhpStorm.
 * User: ptr.nov
 * Date: 10/08/15
 * Time: 19:44
 */

namespace console\components;
use Yii;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\base\Component;
use Yii\base\Model;
use yii\data\ArrayDataProvider;


/** ================================================
 *  ============ H2H SIBISNIS FORMAT ===============
 * 	================================================
	 {
	   "apikey":"55c450f34a57c3160d5a8bf050f14068",
	   "page":"host2host-ppob",
	   "function":"get-info-kelompok",
	   "param":{
			"memberid":"ZON13121710",
			"tipe":"PRE"   
		}		
	 }
  * =================================================
  * Param load Path	: 	console\config\params-local
  * @author ptrnov  <piter@lukison.com>
  * @since 1.1 
*/	

class PpobH2h extends Component{		
	
	/* =========================================================
	 * =================== ALL TYPE KELOMPOK ===================
	 * [1] TYPE
	 * [2] KELOMPOK
	 * [3] KATEGORY
	 * [4] COMBINASI DATA
	 * CLI		:	Yii::$app->ppobh2h->ArrayKelompokAllType();
	 * Authorby	: 	ptr.nov@gmail.com
	 * ========================================================= 
	*/	
	public function ArrayKelompokAllType(){
		//[1] TYPE	=====================	
		$valType = [
			['ID'=>1,'TYPE_CODE'=>'POST'],	//PASCABAYAR	=> PAKAI DULU KEMUDIAN BAYAR
			['ID'=>2,'TYPE_CODE'=>'PRE'],	//PRABAYAR		=> BAYAR DULU KEMUDIAN PAKAI
		];
		$aryType=ArrayHelper::map($valType,'ID','TYPE_CODE');
		//$modelType=PpobMasterType::find()->all();
		
		//=== LOOPING TYPE (POST/PRE) => GET KELOMPOK & KATEGORI KELOMPOK
		foreach($valType as $row1 => $value1){
			//$rslt[]=$value1['TYPE_CODE'];
			$client = new \GuzzleHttp\Client();
			$dataBody = [
				"apikey"=>\Yii::$app->params['apikey'],
				"page" =>\Yii::$app->params['page'],
				"function" => \Yii::$app->params['infoKelompok'],
				"param" => [
					'memberid'=>\Yii::$app->params['memberid'],
					'tipe'=>$value1['TYPE_CODE']
				],			
			];
			$res = $client->post(\Yii::$app->params['urlApi'], ['body' => json_encode($dataBody), 'future' => true]);
			// echo $res->getStatusCode();										// RESPONE URL STATUS	| FORMAT_JSON 
			// $rsltKelompok[]=json_decode($res->getStatusCode());				// RESPONE URL STATUS	| ARRAY OBJECT
			// echo $res->getBody();											// RESPONE URL BODY		| FORMAT_JSON 			
			$stt=json_decode($res->getBody())->errcode;							// RESPONE DATA STATUS	| ARRAY OBJECT
			$data=json_decode(json_encode(json_decode($res->getBody())),true);	// RESPONE DATA BODY	| ARRAY FORMAT
			$allKelompok[]=$data;			
		}
		//=== VALIDASI MERGER DATA.
		if($allKelompok[0]['errcode']<>'406' AND $allKelompok[1]['errcode']<>'406'){
			$aryAllKelompok=array_merge($allKelompok[0]['data'],$allKelompok[1]['data']); 	// MERGE ARRAY [0]['data']=>PASCABAYAR,[1]['data']=PRABAYAR
		}elseif($allKelompok[0]['errcode']!=406 AND $allKelompok[1]['errcode']==406){		
			$aryAllKelompok=$allKelompok[0]['data'];										// NO MERGE ARRAY [0]['data']=>PASCABAYAR
		}elseif($allKelompok[0]['errcode']==406 AND $allKelompok[1]['errcode']!=406){		
			$aryAllKelompok=$allKelompok[1]['data'];										// NO MERGE ARRAY [1]['data']=PRABAYAR
		}else{
			$aryAllKelompok=['errcode'=>'406'];												// data field
		};
		return $aryAllKelompok;
	}		
		
	/* ==================================================
	 * =================== GET PTODUK ===================
	 * CLI		:	Yii::$app->ppobh2h->ArrayProduk($ktg);
	 * Authorby	: 	ptr.nov@gmail.com
	 * ==================================================
	*/	
	public function ArrayProduk($kategori_id)
    {
		$client = new \GuzzleHttp\Client();
		$dataBody = [
			"apikey"=>\Yii::$app->params['apikey'],
			"page" =>\Yii::$app->params['page'],
			"function" => \Yii::$app->params['infoProduk'],
			"param" => [
				'memberid'=>\Yii::$app->params['memberid'],
				'kategori_id'=>$kategori_id,
			],			
		];
		$res = $client->post(\Yii::$app->params['urlApi'], ['body' => json_encode($dataBody), 'future' => false]);
		// echo $res->getStatusCode();
		// echo $res->getBody();
		$data=json_decode(json_encode(json_decode($res->getBody())->data),true);	
		return $data;		
	}
	
	
	/* ==========================================================================
	 * ===============  H2H BAYAR ===============================================
	 * CLI		:	Yii::$app->ppobh2h->ArrayBayar($produkId,$msisdn,$reff_id);
	 * Authorby	: 	ptr.nov@gmail.com
	 * ==========================================================================
	*/	
	public function ArrayBayar($produkId ='',$msisdn='', $reff_id='')
    {
		$client = new \GuzzleHttp\Client();
		
		// $param=[
			// 'memberid'=>\Yii::$app->params['memberid'],
			// 'produk'=>$produkId,
		// ];
		
		$dataBody = [
			"apikey"=>\Yii::$app->params['apikey'],
			"page" =>\Yii::$app->params['page'],
			"function" => \Yii::$app->params['bayar'],
			"param" => [
				'memberid'=>\Yii::$app->params['memberid'],
				'produk'=>$produkId,
				'msisdn'=>$msisdn,
			],			
		];
		$res = $client->post(\Yii::$app->params['urlApi'], ['body' => json_encode($dataBody), 'future' => false]);
		// echo $res->getStatusCode();
		// echo $res->getBody();
		$data=json_decode(json_encode(json_decode($res->getBody())->data),true);	
		return $data;		
	}
	
	/* ==========================================================================
	 * ===============  H2H INQUIRY ===============================================
	 * CLI		:	Yii::$app->ppobh2h->ArrayInquery($produkId,$idPelanggan);
	 * Authorby	: 	ptr.nov@gmail.com
	 * ==========================================================================
	*/	
	public function ArrayInquery($produkId ='',$idPelanggan='')
    {
		$client = new \GuzzleHttp\Client();
		
		// $param=[
			// 'memberid'=>\Yii::$app->params['memberid'],
			// 'produk'=>$produkId,
		// ];
		
		$dataBody = [
			"apikey"=>\Yii::$app->params['apikey'],
			"page" =>\Yii::$app->params['page'],
			"function" => \Yii::$app->params['inquery'],
			"param" => [
				'memberid'=>\Yii::$app->params['memberid'],
				'produk'=>$produkId,
				'id_pelanggan'=>$idPelanggan,
			],			
		];
		$res = $client->post(\Yii::$app->params['urlApi'], ['body' => json_encode($dataBody), 'future' => false]);
		// echo $res->getStatusCode();
		// echo $res->getBody();
		$data=json_decode(json_encode(json_decode($res->getBody())->data),true);	
		return $data;		
	}
	
	
	public function actionKelompokPostpaid()
    {
		//\Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
		//$urlApi="http://dev.api.aptmi.com";
		// echo \Yii::$app->params['page'];
		// die();
		
		$client = new \GuzzleHttp\Client();
		//$dataHeader = ["ID"=>5,"NAMA" => "EKAX"];
		$dataBody = [
			"apikey"=>\Yii::$app->params['apikey'],
			"page" =>\Yii::$app->params['page'],
			"function" => \Yii::$app->params['infoKelompok'],
			"param" => [
				'memberid'=>\Yii::$app->params['memberid'],
				'tipe'=>'POST'
			],			
		];
		// echo json_encode($dataBody);
		$res = $client->post(\Yii::$app->params['urlApi'], ['body' => json_encode($dataBody), 'future' => true]);
		//echo $res->getStatusCode();
		echo $res->getBody();	
				
	}
	
	public function actionKelompokPrepaid()
    {
		$client = new \GuzzleHttp\Client();
		$dataBody = [
			"apikey"=>\Yii::$app->params['apikey'],
			"page" =>\Yii::$app->params['page'],
			"function" => \Yii::$app->params['infoKelompok'],
			"param" => [
				'memberid'=>\Yii::$app->params['memberid'],
				'tipe'=>'PRE'
			],			
		];
		// echo json_encode($dataBody);
		$res = $client->post(\Yii::$app->params['urlApi'], ['body' => json_encode($dataBody), 'future' => true]);
		//echo $res->getStatusCode();
		echo $res->getBody();	
				
	}
	
	
	public function actionProdukCodekelompok($kelompok)
    {
		$client = new \GuzzleHttp\Client();
		$dataBody = [
			"apikey"=>\Yii::$app->params['apikey'],
			"page" =>\Yii::$app->params['page'],
			"function" => \Yii::$app->params['infoProduk'],
			"param" => [
				'memberid'=>\Yii::$app->params['memberid'],
				'kategori_id'=>$kelompok,
			],			
		];
		// echo json_encode($dataBody);
		$res = $client->post(\Yii::$app->params['urlApi'], ['body' => json_encode($dataBody), 'future' => false]);
		echo $res->getStatusCode();
		echo $res->getBody();					
	}
	
}