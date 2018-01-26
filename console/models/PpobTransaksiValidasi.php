<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "ppob_transaksi_validasi".
 *
 * @property string $TRANS_UNIK
 * @property string $ACCESS_GROUP
 * @property string $STORE_ID
 * @property string $TGL diambil dari Tbl ptr_ppob_lpts4->current_date
 * @property string $WAKTU diambil dari Tbl ptr_ppob_lpts4->current_date
 * @property int $FREKUENSI Jumlah looping
 * @property string $CREATE_BY
 * @property string $CREATE_AT
 * @property string $UPDATE_BY
 * @property string $UPDATE_AT
 */
class PpobTransaksiValidasi extends \yii\db\ActiveRecord
{
	
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
        return 'ppob_transaksi_validasi';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TRANS_UNIK'], 'required'],
            [['TGL', 'WAKTU', 'CREATE_AT', 'UPDATE_AT','SN_NUMBER','status','errcode','remarks'], 'safe'],
            [['FREKUENSI'], 'integer'],
            [['TRANS_UNIK'], 'string', 'max' => 150],
            [['ACCESS_GROUP'], 'string', 'max' => 15],
            [['STORE_ID'], 'string', 'max' => 25],
            [['CREATE_BY', 'UPDATE_BY'], 'string', 'max' => 50],
            [['TRANS_UNIK'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TRANS_UNIK' => 'Trans  Unik',
            'ACCESS_GROUP' => 'Access  Group',
            'STORE_ID' => 'Store  ID',
            'TGL' => 'Tgl',
            'WAKTU' => 'Waktu',
            'FREKUENSI' => 'Frekuensi',
            'SN_NUMBER' => 'SN_NUMBER',
            'status' => 'status',
            'errcode' => 'errcode',
            'remarks' => 'remarks',
            'CREATE_BY' => 'Create  By',
            'CREATE_AT' => 'Create  At',
            'UPDATE_BY' => 'Update  By',
            'UPDATE_AT' => 'Update  At',
        ];
    }
}
