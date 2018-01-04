<?php

namespace console\models;
use Yii;

/**
 * This is the model class for table "ppob_master_kelompok".
 *
 * @property string $ID
 * @property string $KELOMPOK
 * @property int $STATUS
 * @property string $KETERANGAN
 * @property string $CREATE_BY
 * @property string $CREATE_AT
 * @property string $UPDATE_BY
 * @property string $UPDATE_AT
 */
class PpobMasterKelompok extends \yii\db\ActiveRecord
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
        return 'ppob_master_kelompok';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['KELOMPOK'], 'required'],
            [['STATUS'], 'integer'],
            [['KETERANGAN'], 'string'],
            [['CREATE_AT', 'UPDATE_AT'], 'safe'],
            [['KELOMPOK'], 'string', 'max' => 255],
            [['CREATE_BY', 'UPDATE_BY'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => 'ID',
            'KELOMPOK' => 'Kelompok',
            'STATUS' => 'Status',
            'KETERANGAN' => 'Keterangan',
            'CREATE_BY' => 'Create  By',
            'CREATE_AT' => 'Create  At',
            'UPDATE_BY' => 'Update  By',
            'UPDATE_AT' => 'Update  At',
        ];
    }
}
