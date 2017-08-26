<?php

namespace api\modules\transaksi\models;

use Yii;

/**
 * This is the model class for table "trans_penjualan_header".
 *
 * @property string $ID
 * @property string $ACCESS_GROUP
 * @property string $STORE_ID
 * @property string $ACCESS_ID
 * @property string $TRANS_ID
 * @property string $TRANS_DATE
 * @property double $TOTAL_PRODUCT
 * @property string $SUB_TOTAL_HARGA
 * @property string $PPN
 * @property string $TOTAL_HARGA
 * @property integer $TYPE_PAY_ID
 * @property string $TYPE_PAY_NM
 * @property integer $BANK_ID
 * @property string $BANK_NM
 * @property string $MERCHANT_NM
 * @property string $MERCHANT_NO
 * @property string $CONSUMER_NM
 * @property string $CONSUMER_EMAIL
 * @property string $CONSUMER_PHONE
 * @property string $CREATE_AT
 * @property string $UPDATE_BY
 * @property string $UPDATE_AT
 * @property integer $STATUS
 * @property string $DCRP_DETIL
 * @property integer $YEAR_AT
 * @property integer $MONTH_AT
 */
class TransPenjualanHeader extends \yii\db\ActiveRecord
{
	const SCENARIO_CREATE = 'create';
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trans_penjualan_header';
    }

    /**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('production_api');
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['STORE_ID','ACCESS_ID','TRANS_DATE'], 'required','on'=>self::SCENARIO_CREATE],
			[['TRANS_DATE', 'CREATE_AT', 'UPDATE_AT','MERCHANT_ID'], 'safe'],
            [['TOTAL_PRODUCT', 'SUB_TOTAL_HARGA', 'PPN', 'TOTAL_HARGA'], 'number'],
            [['TYPE_PAY_ID', 'BANK_ID', 'STATUS', 'YEAR_AT', 'MONTH_AT'], 'integer'],
            [['DCRP_DETIL'], 'string'],
            [['ACCESS_GROUP', 'ACCESS_ID'], 'string', 'max' => 15],
            [['STORE_ID'], 'string', 'max' => 20],
            [['TRANS_ID', 'UPDATE_BY'], 'string', 'max' => 50],
            [['TYPE_PAY_NM', 'BANK_NM', 'CONSUMER_PHONE'], 'string', 'max' => 150],
            [['MERCHANT_NM', 'MERCHANT_NO','OFLINE_ID'], 'string', 'max' => 255],
            [['CONSUMER_NM'], 'string', 'max' => 100],
            [['CONSUMER_EMAIL'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'ACCESS_GROUP' => 'Access  Group',
            'STORE_ID' => 'Store  ID',
            'ACCESS_ID' => 'Access  ID',
            'TRANS_ID' => 'Trans  ID',
            'OFLINE_ID' => 'Ofline ID',
            'TRANS_DATE' => 'Trans  Date',
            'TOTAL_PRODUCT' => 'Total  Product',
            'SUB_TOTAL_HARGA' => 'Sub  Total  Harga',
            'PPN' => 'Ppn',
            'TOTAL_HARGA' => 'Total  Harga',
            'TYPE_PAY_ID' => 'Type  Pay  ID',
            'TYPE_PAY_NM' => 'Type  Pay  Nm',
            'BANK_ID' => 'Bank  ID',
            'BANK_NM' => 'Bank  Nm',
            'MERCHANT_ID' => 'Store Merchant',
            'MERCHANT_NM' => 'Merchant  Nm',
            'MERCHANT_NO' => 'Merchant  No',
            'CONSUMER_NM' => 'Consumer  Nm',
            'CONSUMER_EMAIL' => 'Consumer  Email',
            'CONSUMER_PHONE' => 'Consumer  Phone',
            'CREATE_AT' => 'Create  At',
            'UPDATE_BY' => 'Update  By',
            'UPDATE_AT' => 'Update  At',
            'STATUS' => 'Status',
            'DCRP_DETIL' => 'Dcrp  Detil',
            'YEAR_AT' => 'Year  At',
            'MONTH_AT' => 'Month  At',
        ];
    }
	
	public function fields()
	{
		return [			
			'ID'=>function($model){
				return $model->ID;
			},
			'ACCESS_GROUP'=>function($model){
				return $model->ACCESS_GROUP;
			},
			'STORE_ID'=>function($model){
				return $model->STORE_ID;
			},
			'ACCESS_ID'=>function($model){
				return $model->ACCESS_ID;
			},
			'TRANS_ID'=>function($model){
				return $model->TRANS_ID;
			},
			'OFLINE_ID'=>function($model){
				return $model->OFLINE_ID;
			},
			'TRANS_DATE'=>function($model){
				return $model->TRANS_DATE;
			},					
			'TOTAL_PRODUCT'=>function($model){
				return $model->TOTAL_PRODUCT;
			},
			'SUB_TOTAL_HARGA'=>function($model){
				return $model->SUB_TOTAL_HARGA;
			},
			'PPN'=>function($model){
				return $model->PPN;
			},
			'TOTAL_HARGA'=>function($model){
				return $model->TOTAL_HARGA;
			},
			'MERCHANT_ID'=>function($model){
				return $model->MERCHANT_ID;
			},
			'TYPE_PAY_ID'=>function($model){
				return $model->TYPE_PAY_ID;
			},
			'TYPE_PAY_NM'=>function($model){
				return $model->TYPE_PAY_NM;
			},
			'BANK_ID'=>function($model){
				return $model->BANK_ID;
			},
			'BANK_NM'=>function($model){
				return $model->BANK_NM;
			},
			'MERCHANT_NM'=>function($model){
				return $model->MERCHANT_NM;
			},
			'MERCHANT_NO'=>function($model){
				return $model->MERCHANT_NO;
			},
			'CONSUMER_NM'=>function($model){
				return $model->CONSUMER_NM;
			},
			'CONSUMER_EMAIL'=>function($model){
				return $model->CONSUMER_EMAIL;
			},
			'CONSUMER_PHONE'=>function($model){
				return $model->CONSUMER_PHONE;
			},
			'STATUS'=>function($model){
				return $model->STATUS;
			},					
			'DCRP_DETIL'=>function($model){
				if($model->DCRP_DETIL){
					return $model->DCRP_DETIL;
				}else{
					return 'none';
				}
			}
		];
	}
}
