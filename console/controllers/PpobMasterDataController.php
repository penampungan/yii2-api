<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;		
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use yii\helpers\ArrayHelper;
use console\models\PpobMasterType;
use console\models\PpobMasterKelompok;
use console\models\PpobMasterKtg;
use console\models\PpobMasterFungsi;
use console\models\PpobMasterData;

class PpobMasterDataController extends Controller
{	
	
	/*  
	 * ACTION	: Uji Coba
	*/
	public function actionCoba(){
		//$result=Yii::$app->ppobh2h->ArrayBayar($produkId,$msisdn,$reff_id);
		// $result=Yii::$app->ppobh2h->ArrayBayar('39','085883319929','');
		// print_r($result);
		//$result=Yii::$app->ppobh2h->ArrayBayar('50','14020296076');
		$result=Yii::$app->ppobh2h->ArrayInquery('163','12345678');
		print_r($result);
		
		//$allDataKelpmpok=Yii::$app->ppobh2h->ArrayKelompokAllType();
		//print_r($allDataKelpmpok);
		//[2]==== GET ARRAY KELOMPOK ====
		// foreach($allDataKelpmpok as $row2 => $value2){
			// $groupKelompok[]=$row2;
		// } 		
	}
	
	/*  
	 * ACTION	: Update Group Kelompok 
	*/
	public function actionUpdateGroupKelompok(){
		$allDataKelpmpok=Yii::$app->ppobh2h->ArrayKelompokAllType();
		$rslt=self::simpanGroupKelompok($allDataKelpmpok);
		print_r($rslt);
		// return $rslt;
	}
	
	/*  
	 * ACTION	: Update Kategori Kelompok
	*/
	public function actionUpdateKategoriKelompok(){
		$allDataKelpmpok=Yii::$app->ppobh2h->ArrayKelompokAllType();
		$rslt=self::simpanKategoriKelompok($allDataKelpmpok);
		// print_r($rslt);
		return $rslt;
	}
	
	/*  
	 * ACTION	: Update Produk
	*/
	public function actionUpdateProduk(){
		$allDataKelpmpok=Yii::$app->ppobh2h->ArrayKelompokAllType();
		$rslt=self::simpanDataProduk($allDataKelpmpok);
		print_r($rslt);
		//return $rslt;
	}
	
	/*  
	 * ACTION	: Update Harga Produck
	*/
	public function actionUpdateHargaProduk(){
		$rslt=self::UpdateHargaProduk();
		return $rslt;
	}
	
	
	/*  TITTLE	: GROUP KELOMPOK
	 *  ACTION	: SIMPAN/UPDATE
	 *  TABLE	: ppob_master_kelompok
	 *  @author ptrnov  <piter@lukison.com>
	 *  @since 1.1 
	*/
	private function simpanGroupKelompok($allDataKelpmpok=[]){		
		foreach($allDataKelpmpok as $row2 => $value2){
			$modalValidasi=PpobMasterKelompok::find()->where(['KELOMPOK'=>$row2])->one();
			if(!$modalValidasi){
				$model=new PpobMasterKelompok();
				$model->KELOMPOK=$row2;
				$model->save();				
			}
			$rslt[]= $row2;
		}
		return $rslt;
	}	
	
		
	/*  TITTLE	: KATEGORY KELOMPOK
	 *  ACTION	: SIMPAN/UPDATE
	 *  TABLE	: ppob_master_ktg
	 *  @author ptrnov  <piter@lukison.com>
	 *  @since 1.1 
	*/
	private function simpanKategoriKelompok($allDataKelpmpok=[]){
		$dataKtg='';
		foreach($allDataKelpmpok as $row1 => $value1){
			foreach($allDataKelpmpok[$row1] as $row2 => $value2){	
				$modalKtgValidasi=PpobMasterKtg::find()->where(['KTG_ID'=>$value2['id']])->one();
				if(!$modalKtgValidasi){
					$modelKtg= new PpobMasterKtg();
					$modelKtg->KTG_ID=(string)$value2['id'];
					$modelKtg->KTG_NM=(string)$value2['kategori'];
					$modelKtg->KELOMPOK=(string)$value2['kelompok'];
					$modelKtg->save();
					$dataKtg[]=$value2['id'];
					$dataKtg[]=$value2['kategori'];
					$dataKtg[]=$value2['kelompok'];
				}
			}
		}		
		return $dataKtg;
	}	
	
	/*  TITTLE	: TYPE/GROUP/KATEGORY PRODUK
	 *  ACTION	: SIMPAN/UPDATE
	 *  TABLE	: ppob_master_data
	 *  @author ptrnov  <piter@lukison.com>
	 *  @since 1.1 
	*/
	private function simpanDataProduk($allDataKelpmpok=[]){			
		foreach($allDataKelpmpok as $row1 => $value1){
			foreach($allDataKelpmpok[$row1] as $row2 => $value2){				
				//$dataProduk[]=$value2['id'];
				// if ($value2['id']=='96'){											// ==CHECK ONLY ONE KATEGORY
					// $dataProduk=Yii::$app->ppobh2h->ArrayProduk('96');				// ==CHECK ONLY ONE KATEGORY
					$dataProduk=Yii::$app->ppobh2h->ArrayProduk($value2['id']);
					foreach($dataProduk as $row3 => $value3){						
						//== TYPE KELOMPOK ==
						$dataProdukDetail['TYPE_NM']=$value2['kelompok']=='PASCABAYAR'?$value2['kelompok']:'PRABAYAR';
						//== GROUP KELOMPOK ==
						$dataProdukDetail['KELOMPOK']=$value2['kelompok'];
						//== KELOMPOK KATEGORY==
						$dataProdukDetail['KTG_ID']=$value2['id'];
						$dataProdukDetail['KTG_NM']=$value2['kategori'];						
						//== DETAIL PRODUK ==
						$dataProdukDetail['ID_CODE']=$value3['id'];
						$dataProdukDetail['CODE']=$value3['kode'];
						$dataProdukDetail['NAME']=$value3['nama'];
						$dataProdukDetail['DENOM']=$value3['denom'];
						$dataProdukDetail['HARGA']=$value3['harga'];
						$dataProdukDetail['PERMIT']=$value3['permit'];
						
						$modalValidasi=PpobMasterData::find()->where([
							'KTG_ID'=>$value2['id'],
							'ID_CODE'=>$value3['id']
						])->one();
						
						if($modalValidasi){
							//== GROUP KELOMPOK ==
							$modalValidasi->KELOMPOK=(string)$value2['kelompok'];
							//== KELOMPOK KATEGORY==
							$modalValidasi->KTG_NM=(string)$value2['kategori'];
							//== DETAIL PRODUK ==
							$modalValidasi->CODE=(string)$value3['kode'];
							$modalValidasi->NAME=(string)$value3['nama'];
							$modalValidasi->DENOM=(string)$value3['denom'];
							$modalValidasi->HARGA=(string)$value3['harga'];
							$modalValidasi->PERMIT=(string)$value3['permit'];
							$modalValidasi->save();
						}else{
							$model= new PpobMasterData();
							//==GENERATE ID_PRODUK H2H-SIBISNIS==
							$model->ID_PRODUK=(string)('h2h.'.$value2['id'].'.'.$value3['id']);
							//== TYPE KELOMPOK ==
							$model->TYPE_NM=(string)$value2['kelompok']=='PASCABAYAR'?$value2['kelompok']:'PRABAYAR';
							//== GROUP KELOMPOK ==
							$model->KELOMPOK=(string)$value2['kelompok'];
							//== KELOMPOK KATEGORY==
							$model->KTG_ID=(string)$value2['id'];
							$model->KTG_NM=(string)$value2['kategori'];						
							//== DETAIL PRODUK ==
							$model->ID_CODE=(string)$value3['id'];
							$model->CODE=(string)$value3['kode'];
							$model->NAME=(string)$value3['nama'];
							$model->DENOM=(string)$value3['denom'];
							$model->HARGA=(string)$value3['harga'];
							$model->PERMIT=(string)$value3['permit'];
							$model->FUNGSI=(string)$value3['permit'];
							$model->save();
						}
							
					}
				//} // CHECK ONLY ONE KATEGORY
			}
		}		
		// return $dataProduk;
		return $dataProdukDetail;
	}

	/*  TITTLE	: SECNARIO HARGA
	 *  ACTION	: SIMPAN/UPDATE
	 *  TABLE	: ppob_master_data
	 *  	- DENOM		: Nominal pembelian
	 *		- HARGA		: Harga PT.SIBINSIS.
	 *		- PERMIT	: 0=Layanan Tidak  tersedia/deactve; 1=Layanan Tersedia 
	 *  TABLE	: => ppob_master_harga
	 *		- DENOM				: Nominal pembelian
	 *		- HARGA_BARU		: Harga update dari PT.SIBINSIS.
	 *		- HARGA_DASAR		: Harga yang akan diupdate dari harga beru, berdasarkan tanggal aktiv.
     *						  	  jika masuk ke Tanggal Aktive, maka HARGA_BARU dicopy ke HARGA
     *		- HARGA_JUAL		: Harga yang sudah di tetapkan oleh PT.KONTROLGAMPANG, menyesuaikan harga pasar.
     *		- MARGIN_FEE_KG		: PENETAPKAN KEUNTUNGAN DARI (HARGA JUAL - HARGA), SHARE (FEE_KG & FEE_MEMBER).
	 *		- MARGIN_FEE_MEMBER	: PENETAPKAN KEUNTUNGAN DARI (HARGA JUAL - HARGA), SHARE (FEE_KG & FEE_MEMBER).	
	 *		- STATUS			: 0=Deactivate; 1=Active; 2=New Price
	 * ACTION FORM :
	 * - Jika ada perbedaan harga-baru dan harga maka					: ppob_master_harga.STATUS=2
	 * - Jika sudah di update berdasarkan Tanggal Active maka			: ppob_master_harga.STATUS=1				=> TRIGER POLLING
	 * - MARGIN_FEE_KG & MARGIN_FEE_MEMBER berdasarkan nominal rupiah	: Pembagian dari sisa (HARGA JUAL - HARGA_DASAR)
	 * - MARGIN_FEE_KG & MARGIN_FEE_MEMBER berdasarkan nominal rupiah	: Pembagian dari sisa (HARGA JUAL - HARGA_DASAR)
	 * - Ketersediaan Layanan produk									: Copy PERMIT to ppob_master_harga.STATUS	=> TRIGET POLLING
	 *  @author ptrnov  <piter@lukison.com>
	 *  @since 1.1 
	*/
	private function UpdateHargaProduk(){
		$sqlstr="
			INSERT INTO ppob_master_harga(
				ID_PRODUK,TYPE_NM,KELOMPOK,KTG_ID,KTG_NM,ID_CODE,CODE,NAME,DENOM,HARGA_DASAR,HARGA_BARU,FUNGSI,PERMIT,STATUS
			)
			SELECT 
				ID_PRODUK,TYPE_NM,KELOMPOK,KTG_ID,KTG_NM,ID_CODE,CODE,NAME,DENOM,HARGA_DASAR,HARGA_BARU,FUNGSI,PERMIT,PERMIT AS STATUS	
			FROM
			(	SELECT 
				(CASE WHEN x2.ID_PRODUK IS NOT NULL THEN x2.ID_PRODUK ELSE x1.ID_PRODUK END) AS ID_PRODUK,
			    x1.TYPE_NM,x1.KELOMPOK,x1.KTG_ID,x1.KTG_NM,x1.ID_CODE,x1.CODE,x1.NAME,x1.DENOM,
				x1.HARGA AS HARGA_DASAR,x1.HARGA AS HARGA_BARU,
				x1.FUNGSI,x1.PERMIT,x1.PERMIT AS STATUS		
				FROM ppob_master_data x1 LEFT JOIN ppob_master_harga x2 ON x2.ID_PRODUK=x1.ID_PRODUK
			) a1
			ON DUPLICATE KEY UPDATE
				DENOM=a1.DENOM,HARGA_BARU=a1.HARGA_BARU,PERMIT=a1.PERMIT,STATUS=a1.STATUS
		";
		$rslt = Yii::$app->ppob_cronjob->createCommand($sqlstr)->execute();
		return $rslt;
	}		

	/*
	1. Uapdate harga, apakah langsung update di api? jika iya apakah ada periode/manual confirmasi ?
    2. Apakah upadate harga secara keseluruhan/per-produck.
    3. Apakah POSTPAID/PASCABAYAR saja yang inquery.
    4. Keuntungan/margin dari paskabayar ?	
	5. PLN pulsa, tidak ada nama dari pelanggan.
	6. Status code, berhasil/tidak?  pulsa atau paket sampai ke konsumen langsung.
	7. apakah history bisa di panggil per-transaksi, untuk validasi (berhasil/tidak).
    8. VSN /Virtual Serial Number/ Token, apa masksudunya ?	
	
	*/
}

?>