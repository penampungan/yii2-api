<?php

namespace console\models;

use Yii;
use console\models\PpobTransaksiValidasi;
/*
 * INPUT	 (TYPE_NM,TRANS_ID,TRANS_DATE,STORE_ID,ID_PRODUK,MSISDN,ID_PELANGGAN,PEMBAYARAN)
 * RESPON 	 (NAMA_PELANGGAN,ADMIN_BANK,TAGIHAN,TOTAL_BAYAR,MESSAGE,STRUK,TOKEN,STATUS)
 */
class PpobTransaksi extends \yii\db\ActiveRecord
{
	const SCENARIO_PASCABAYAR = 'pascabayar';
	const SCENARIO_PRABAYAR = 'prabayar';
    
	/**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('api_cronjob');
    }	
	/**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ppob_transaksi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
			// [['TRANS_ID','TRANS_DATE','STORE_ID','ID_PRODUK','ID_PELANGGAN','PEMBAYARAN'], 'required','on'=>self::SCENARIO_PASCABAYAR],
            // [['TRANS_ID','TRANS_DATE','STORE_ID','ID_PRODUK','MSISDN','PEMBAYARAN'], 'required','on'=>self::SCENARIO_PRABAYAR],   
			
			// [['TRANS_ID','TRANS_DATE','STORE_ID','ID_PRODUK','ID_PELANGGAN'], 'required','on'=>self::SCENARIO_PASCABAYAR],
            // [['TRANS_ID','TRANS_DATE','STORE_ID','ID_PRODUK','MSISDN','PEMBAYARAN'], 'required','on'=>self::SCENARIO_PRABAYAR],		
            [['TRANS_DATE', 'TGL', 'JAM', 'CREATE_AT', 'UPDATE_AT','TRANS_UNIK','validasiStatus','RESPON_OPERATOR','RESPON_KODE_VOUCHER','RESPON_NOMINAL'], 'safe'],
            [['NAME', 'RESPON_MESSAGE', 'RESPON_STRUK','RESPON_SN'], 'string'],
            [['DENOM', 'HARGA_DASAR', 'MARGIN_FEE_KG', 'MARGIN_FEE_MEMBER', 'HARGA_JUAL', 'PEMBAYARAN', 'RESPON_ADMIN_BANK', 'RESPON_TAGIHAN', 'RESPON_TOTAL_BAYAR'], 'number'],
            [['PERMIT', 'STATUS','DEV_STT'], 'integer'],
            [['TRANS_ID', 'KTG_ID', 'ID_CODE', 'CODE', 'CREATE_BY', 'UPDATE_BY'], 'string', 'max' => 50],
            [['ACCESS_GROUP'], 'string', 'max' => 15],
            [['STORE_ID'], 'string', 'max' => 25],
            [['ID_PRODUK', 'FUNGSI'], 'string', 'max' => 100],
            [['TYPE_NM', 'KELOMPOK', 'KTG_NM', 'MSISDN', 'ID_PELANGGAN', 'RESPON_REFF_ID', 'RESPON_NAMA_PELANGGAN', 'RESPON_TOKEN'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'TRANS_ID' => 'Trans  ID',
            'TRANS_DATE' => 'Trans  Date',
            'TGL' => 'Tgl',
            'JAM' => 'Jam',
            'ACCESS_GROUP' => 'Access  Group',
            'STORE_ID' => 'Store  ID',
            'ID_PRODUK' => 'Id  Produk',
            'TYPE_NM' => 'Type  Nm',
            'KELOMPOK' => 'Kelompok',
            'KTG_ID' => 'Ktg  ID',
            'KTG_NM' => 'Ktg  Nm',
            'ID_CODE' => 'Id  Code',
            'CODE' => 'Code',
            'NAME' => 'Name',
            'DENOM' => 'Denom',
            'HARGA_DASAR' => 'Harga  Dasar',
            'MARGIN_FEE_KG' => 'Margin  Fee  Kg',
            'MARGIN_FEE_MEMBER' => 'Margin  Fee  Member',
            'HARGA_JUAL' => 'Harga  Jual',
            'PERMIT' => 'Permit',
            'FUNGSI' => 'Fungsi',
            'MSISDN' => 'Msisdn',
            'ID_PELANGGAN' => 'Id  Pelanggan',
            'PEMBAYARAN' => 'Pembayaran',
            'RESPON_REFF_ID' => 'Respon  Reff  ID',
            'RESPON_NAMA_PELANGGAN' => 'Respon  Nama  Pelanggan',
            'RESPON_ADMIN_BANK' => 'Respon  Admin  Bank',
            'RESPON_TAGIHAN' => 'Respon  Tagihan',
            'RESPON_TOTAL_BAYAR' => 'Respon  Total  Bayar',
            'RESPON_MESSAGE' => 'Respon  Message',
            'RESPON_STRUK' => 'Respon  Struk',
            'RESPON_TOKEN' => 'Respon  Token',
            'RESPON_SN' => 'Respon  SN',
            'RESPON_OPERATOR' => 'Operator',
            'RESPON_KODE_VOUCHER' => 'Kode Voucher',
            'RESPON_NOMINAL' => 'Nominal',
            'STATUS' => 'Status',
            'CREATE_BY' => 'Create  By',
            'CREATE_AT' => 'Create  At',
            'UPDATE_BY' => 'Update  By',
            'UPDATE_AT' => 'Update  At',
        ];
    }
	
	public function fields()
	{
		return [			
			'TRANS_UNIK'=>function($model){
				return $model->TRANS_ID;				// INPUT = NO-TRANSAKSI KASIR
			},
			'TRANS_ID'=>function($model){
				return $model->TRANS_ID;				// INPUT = NO-TRANSAKSI KASIR
			},
			'TRANS_DATE'=>function($model){
				return $model->TRANS_DATE;				// INPUT = TANGGAL-TRANSAKSI KASIR
			},
			'KTG_NM'=>function($model){
				return $model->KTG_NM;
			},
			'ACCESS_GROUP'=>function($model){				
				return $model->ACCESS_GROUP;
			},
			'STORE_ID'=>function($model){
				return $model->STORE_ID;				// INPUT = STORE_ID
			},
			'ID_PRODUK'=>function($model){
				return $model->ID_PRODUK;				// INPUT = ID_PRODUK = KODE PRODUK
			},
			'TYPE_NM'=>function($model){				// TRIGER OTOMATIS
				return $model->TYPE_NM;
			},
			'KELOMPOK'=>function($model){				// TRIGER OTOMATIS
				return $model->KELOMPOK;
			},
			'KTG_ID'=>function($model){					// TRIGER OTOMATIS
				return $model->KTG_ID;
			},
			'KTG_NM'=>function($model){					// TRIGER OTOMATIS
				return $model->KTG_NM;
			},
			// 'ID_CODE'=>function($model){				// TRIGER OTOMATIS
				// return $model->ID_CODE;
			// },
			'CODE'=>function($model){					// TRIGER OTOMATIS
				return $model->CODE;	
			},
			'NAME'=>function($model){					// TRIGER OTOMATIS
				return $model->NAME;
			},
			'DENOM'=>function($model){					// TRIGER OTOMATIS
				return $model->DENOM;
			},
			'HARGA_JUAL'=>function($model){				// TRIGER OTOMATIS
				return $model->HARGA_JUAL;
			},												
			'MSISDN'=>function($model){
				return $model->MSISDN;					// INPUT = MSISDN = NO TELEPHON ()
			},
			'ID_PELANGGAN'=>function($model){
				return $model->ID_PELANGGAN;			// INPUT = ID_PELANGGAN (PASCABAYAR) | INPUT = MSISDN FOR NOTIFICATION
			},
			'PEMBAYARAN'=>function($model){
				return $model->PEMBAYARAN;				// INPUT = PEMBAYARAN (Manual/RESPON_TOTAL_BAYAR untuk PASCABAYAR), Untuk PRABAYAR from HARGA_JUAL
			},
			'RESPON_REFF_ID'=>function($model){
				return $model->RESPON_REFF_ID;			// RESPON = REFF_ID     
			},
			'RESPON_NAMA_PELANGGAN'=>function($model){
				return $model->RESPON_NAMA_PELANGGAN;	// RESPON = NAMA_PELANGGAN (PASCABAYAR) 
			},
			'RESPON_ADMIN_BANK'=>function($model){
				return $model->RESPON_ADMIN_BANK;		// RESPON = ADMIN_BANK (PASCABAYAR) 
			},
			'RESPON_TAGIHAN'=>function($model){
				return $model->RESPON_TAGIHAN;			// RESPON = ADMIN_BANK (PASCABAYAR) 
			},
			'RESPON_TOTAL_BAYAR'=>function($model){
				return $model->RESPON_TOTAL_BAYAR;		// RESPON = TOTAL_BAYAR (PASCABAYAR)
			},
			'RESPON_MESSAGE'=>function($model){
				return $model->RESPON_MESSAGE;			// RESPON = MESSAGE (PASCABAYAR)
			},
			'RESPON_STRUK'=>function($model){
				return $model->RESPON_STRUK;			// RESPON = STRUK (PASCABAYAR)
			},
			'RESPON_TOKEN'=>function($model){
				return $model->RESPON_TOKEN;			// RESPON = TOKEN (PASCABAYAR)
			},
			'STATUS'=>function($model){					// RESPON = STATUS (0=(first transaksi); 1=(success B to B to A to C); 2=Panding; 3=Gagal)
				return $model->STATUS;					
				// sudah mendapatkan update respon, status 0 pmenjadi status=1
				// Status=1, maka tidak akan bisa di kembalikan ke Status=0
			},
			'RESPON_OPERATOR'=>function($model){
				return $model->RESPON_OPERATOR;			// RESPON_OPERATOR 
			},
			'RESPON_KODE_VOUCHER'=>function($model){
				return $model->RESPON_KODE_VOUCHER;		// RESPON_KODE_VOUCHER
			},
			'RESPON_NOMINAL'=>function($model){
				return $model->RESPON_NOMINAL;			// RESPON_NOMINAL
			}
		];
	}
	
	public function getTransaksiValidasi()
    {
        return $this->hasOne(PpobTransaksiValidasi::className(),['TRANS_UNIK'=>'TRANS_UNIK']);         
    }
	
	//VALIDASI PPOB POST PEMBAYARAN
    public function getValidasiStatus(){
        $result=$this->transaksiValidasi;
        return $result!=''?1:0;
		//TAMBAHKAN TABEL LOCK STORE BERMASALAH, TRANSAKSI DI GAGALKAN
    }
}


