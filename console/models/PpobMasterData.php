<?php

namespace console\models;
use Yii;

/**
 * This is the model class for table "ppob_master_data".
 *
 * @property string $ID
 * @property int $TYPE_ID
 * @property string $TYPE_NM
 * @property string $KELOMPOK
 * @property string $KTG_ID
 * @property string $KTG_NM
 * @property string $ID_CODE
 * @property string $CODE
 * @property string $NAME
 * @property string $DENOM
 * @property string $HARGA
 * @property string $FUNGSI
 * @property int $PERMIT
 * @property int $STATUS
 * @property string $KETERANGAN
 * @property string $CREATE_BY
 * @property string $CREATE_AT
 * @property string $UPDATE_BY
 * @property string $UPDATE_AT
 */
class PpobMasterData extends \yii\db\ActiveRecord
{
	/**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('ppob_cronjob');
    }
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'ppob_master_data';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['PERMIT', 'STATUS'], 'integer'],
            //[['TYPE_NM'], 'required'],
            [['ID_PRODUK','NAME', 'KETERANGAN'], 'string'],
            [['DENOM', 'HARGA'], 'number'],
            [['CREATE_AT', 'UPDATE_AT'], 'safe'],
            [['TYPE_NM', 'KELOMPOK', 'KTG_NM'], 'string', 'max' => 255],
            [['KTG_ID', 'ID_CODE', 'CODE', 'CREATE_BY', 'UPDATE_BY'], 'string', 'max' => 50],
            [['FUNGSI'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID_PRODUK' => 'ID_PRODUK',
            'TYPE_NM' => 'Type  Nm',
            'KELOMPOK' => 'Kelompok',
            'KTG_ID' => 'Ktg  ID',
            'KTG_NM' => 'Ktg  Nm',
            'ID_CODE' => 'Id  Code',
            'CODE' => 'Code',
            'NAME' => 'Name',
            'DENOM' => 'Denom',
            'HARGA' => 'Harga',
            'FUNGSI' => 'Fungsi',
            'PERMIT' => 'Permit',
            'STATUS' => 'Status',
            'KETERANGAN' => 'Keterangan',
            'CREATE_BY' => 'Create  By',
            'CREATE_AT' => 'Create  At',
            'UPDATE_BY' => 'Update  By',
            'UPDATE_AT' => 'Update  At',
        ];
    }
}
