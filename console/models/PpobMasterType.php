<?php

namespace console\models;

use Yii;

/**
 * This is the model class for table "ppob_master_type".
 *
 * @property string $TYPE_ID
 * @property string $TYPE_CODE
 * @property string $TYPE_NM
 * @property int $STATUS
 * @property string $KETERANGAN
 * @property string $CREATE_BY
 * @property string $CREATE_AT
 * @property string $UPDATE_BY
 * @property string $UPDATE_AT
 */
class PpobMasterType extends \yii\db\ActiveRecord
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
        return 'ppob_master_type';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['TYPE_NM'], 'required'],
            [['STATUS'], 'integer'],
            [['KETERANGAN'], 'string'],
            [['CREATE_AT', 'UPDATE_AT'], 'safe'],
            [['TYPE_CODE', 'TYPE_NM'], 'string', 'max' => 100],
            [['CREATE_BY', 'UPDATE_BY'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'TYPE_ID' => 'Type  ID',
            'TYPE_CODE' => 'Type  Code',
            'TYPE_NM' => 'Type  Nm',
            'STATUS' => 'Status',
            'KETERANGAN' => 'Keterangan',
            'CREATE_BY' => 'Create  By',
            'CREATE_AT' => 'Create  At',
            'UPDATE_BY' => 'Update  By',
            'UPDATE_AT' => 'Update  At',
        ];
    }
}
