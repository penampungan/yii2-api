<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;		
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Stream\Stream;
use GuzzleHttp\Psr7\Response;	
use yii\helpers\ArrayHelper;
use console\models\PpobMasterType;
use console\models\PpobMasterKelompok;
use console\models\PpobMasterKtg;
use console\models\PpobMasterFungsi;
use console\models\PpobMasterData;
use console\models\PpobTransaksi;
use console\models\PpobTransaksiValidasi;

class TestDataController extends Controller
{	
	
	
	public function actionLoketData(){
		//$url="http://rest.loket.com?client_id=kontrolapi&client_secret=80e5381177e9c495fda442359c33cbbf";
		$url="http://rest.sandbox.loket.com/authentication/generate?client_id=kontrolapi&client_secret=80e5381177e9c495fda442359c33cbbf";
		$client = new \GuzzleHttp\Client();
			$dataBody = [
				'ACCESS_GROUP'=>'170726220936',
				'TGL'=>date("2018-02-01"),
				// "apikey"=>\Yii::$app->params['apikey'],
				// "page" =>\Yii::$app->params['page'],
				// "function" => \Yii::$app->params['infoKelompok'],
				// "param" => [
					// 'memberid'=>\Yii::$app->params['memberid'],
					// 'tipe'=>$value1['TYPE_CODE']
				// ],			
			];
			$res = $client->get($url);
			//$res = $client->post($url, ['body' => json_encode($dataBody), 'future' => true]);
			// echo $res->getStatusCode();										// RESPONE URL STATUS	| FORMAT_JSON 
			// $rsltKelompok[]=json_decode($res->getStatusCode());				// RESPONE URL STATUS	| ARRAY OBJECT
			// echo $res->getBody();											// RESPONE URL BODY		| FORMAT_JSON 			
			$stt=json_decode($res->getBody());							// RESPONE DATA STATUS	| ARRAY OBJECT
			// $data=json_decode(json_encode(json_decode($res->getBody())),true);	// RESPONE DATA BODY	| ARRAY FORMAT
			// $allKelompok[]=$data;			
			//$stt = $res->send();
		print_r($stt);
	}	
	
	public function actionLoketDataEvent(){
		//$url="http://rest.loket.com?client_id=kontrolapi&client_secret=80e5381177e9c495fda442359c33cbbf";
		$url="http://rest.sandbox.loket.com/";
		//$headers = ['Authorization' => 'HMAC-SHA256 ClientId=kontrolapi, Timestamp=1523069223, Signature=PkIb+DVl5cBOF5A9tbzDTwdIrwTm99XTZruzAbTSGno='];	
		$headers = ['Authorization' =>'HMAC-SHA256 ClientId=kontrolapi, Timestamp=1523405351, Signature=4HbNgmKh1ke2GLMEOv1MnLYGF4BNzdnuR9o78MHX25E='];	
		//$client = new \GuzzleHttp\Client([ 'base_uri'  =>$url]);
		$client = new \GuzzleHttp\Client(
			//['headers' =>$headers]
		);
		
		// $res=$client->request('GET','http://rest.loket.com/event/list',[ 'allow_redirects'  =>  false]);
		$res=$client->get('http://rest.sandbox.loket.com/event/list',['headers' =>$headers]);
/* 			$dataBody = [
				'ACCESS_GROUP'=>'170726220936',
				'TGL'=>date("2018-02-01"),
				// "apikey"=>\Yii::$app->params['apikey'],
				// "page" =>\Yii::$app->params['page'],
				// "function" => \Yii::$app->params['infoKelompok'],
				// "param" => [
					// 'memberid'=>\Yii::$app->params['memberid'],
					// 'tipe'=>$value1['TYPE_CODE']
				// ],			
			];
			*/
			// $dataHeader=[				
				// 'headers' => [
					 // 'Authorization' =>'HMAC-SHA256 ClientId=kontrolapi, Timestamp=1523405351, Signature=4HbNgmKh1ke2GLMEOv1MnLYGF4BNzdnuR9o78MHX25E='
				// ],
			// ]; 
			//$auth_header = sprintf("HMAC-SHA256 ClientId=%s, Timestamp=%d, Signature=%s", $client_id, $unix_timestamp, $signature);
			//$res = $client->get($url);
			
			//$request = new Request('GET','/event/list',['headers'=>$dataHeader]);
			//$res = $client->request('GET',$url,['headers' =>$dataHeader]);
			//$request->addHeader('Authorization',' HMAC-SHA256 ClientId=kontrolapi, Timestamp=1523064185, Signature=tSyhKvZvPgsMjot3JGoWiRBC44Hr2P3gwCbePl6tVBU=');
			// $res = $client->get($url,[
				// 'Authorization' =>'HMAC-SHA256 ClientId=kontrolapi, Timestamp=1523062031, Signature=8GnebkmolfBka8ePA+Etoz33CNlkA4PioTqnH5hd/RM='
			// ]);
			//$res = $client->post($url, ['body' => json_encode($dataBody), 'future' => true]);
			//$stt=$res->getStatusCode();										// RESPONE URL STATUS	| FORMAT_JSON 
			//$stt=$res->getHeaderLine ( 'X-Guzzle-Redirect-History' ); 									// RESPONE URL STATUS	| FORMAT_JSON 
			// $rsltKelompok[]=json_decode($res->getStatusCode());				// RESPONE URL STATUS	| ARRAY OBJECT
			// echo $res->getBody();											// RESPONE URL BODY		| FORMAT_JSON 			
			//$stt=json_decode($res->getBody());							// RESPONE DATA STATUS	| ARRAY OBJECT
			// $data=json_decode(json_encode(json_decode($res->getBody())),true);	// RESPONE DATA BODY	| ARRAY FORMAT
			// $allKelompok[]=$data;			
			//$stt = $res->send();
			//$stt =$request->getMessage(); 
			//$stt =json_decode($res->getBody());  
			 
			// $res=$client->send($request);
			//$stt =$res->getBody(); 
			$stt=json_decode($res->getBody());	
			print_r($stt);
			// while (!$stt->eof()) {
				// echo $stt->read(1024);
			// }
	}	
	
	public function actionPostData(){
		$url="https://production.kontrolgampang.com/laporan/sales-charts/detail-sales-bulanan";
		$client = new \GuzzleHttp\Client([
			'Content-type'=>'application/x-www-form-urlencoded'
		]);
			$dataBody = [
				'ACCESS_GROUP'=>'170726220936',
				'TGL'=>"2018-02-01"//date("Y-m-d"),//date("2018-02-01"),
				// 'ACCESS_GROUP'=>'170726220936',
				// 'TGL'=>date("Y-m-d"),//date("2018-02-01"),
				// "apikey"=>\Yii::$app->params['apikey'],
				// "page" =>\Yii::$app->params['page'],
				// "function" => \Yii::$app->params['infoKelompok'],
				/* "param" => [
					// 'memberid'=>\Yii::$app->params['memberid'],
					// 'tipe'=>$value1['TYPE_CODE']
					'ACCESS_GROUP'=>'170726220936',
					'TGL'=>date("Y-m-d"),//date("2018-02-01"),
				],		 */	
			];
			$res = $client->post($url,['form_params'=>[
							'ACCESS_GROUP'=>'170726220936',
							'TGL'=>'2018-02-01',
						
						]
					]
				);
			//$res->setPostField('ACCESS_GROUP', '170726220936');
			//$res->getPostFields()->add('TGL', '2018-02-01');
			// echo $res->getStatusCode();										// RESPONE URL STATUS	| FORMAT_JSON 
			// $rsltKelompok[]=json_decode($res->getStatusCode());				// RESPONE URL STATUS	| ARRAY OBJECT
			// echo $res->getBody();											// RESPONE URL BODY		| FORMAT_JSON 			
			$stt=json_decode($res->getBody());							// RESPONE DATA STATUS	| ARRAY OBJECT
			// $data=json_decode(json_encode(json_decode($res->getBody())),true);	// RESPONE DATA BODY	| ARRAY FORMAT
			// $allKelompok[]=$data;			
		print_r($stt);
	}	
	
}

?>