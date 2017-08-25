<?php

namespace api\modules\hirs\models;

use Yii;

/**
 * This is the model class for table "hrd_absen".
 *
 * @property string $ID
 * @property string $ABSEN_ID
 * @property string $ACCESS_GROUP
 * @property string $STORE_ID
 * @property string $KARYAWAN_ID
 * @property string $TGL
 * @property string $WAKTU
 * @property string $CREATE_BY
 * @property string $CREATE_AT
 * @property string $UPDATE_BY
 * @property string $UPDATE_AT
 * @property integer $STATUS
 * @property string $DCRP_DETIL
 * @property integer $YEAR_AT
 * @property integer $MONTH_AT
 */
class HrdAbsen extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'hrd_absen';
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
            [['ABSEN_ID', 'STATUS', 'YEAR_AT', 'MONTH_AT'], 'integer'],
            [['STORE_ID', 'KARYAWAN_ID', 'YEAR_AT', 'MONTH_AT'], 'required'],
            [['TGL', 'WAKTU', 'CREATE_AT', 'UPDATE_AT'], 'safe'],
            [['DCRP_DETIL'], 'string'],
            [['ACCESS_GROUP'], 'string', 'max' => 15],
            [['STORE_ID'], 'string', 'max' => 25],
            [['KARYAWAN_ID'], 'string', 'max' => 30],
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
            'ABSEN_ID' => 'Absen  ID',
            'ACCESS_GROUP' => 'Access  Group',
            'STORE_ID' => 'Store  ID',
            'KARYAWAN_ID' => 'Karyawan  ID',
            'TGL' => 'Tgl',
            'WAKTU' => 'Waktu',
            'CREATE_BY' => 'Create  By',
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
			'ABSEN_ID'=>function($model){
				return $model->ABSEN_ID;
			},
			'ACCESS_GROUP'=>function($model){
				return $model->ACCESS_GROUP;
			},
			'STORE_ID'=>function($model){
				return $model->STORE_ID;
			},
			'KARYAWAN_ID'=>function($model){
				return $model->KARYAWAN_ID;
			},
			'TGL'=>function($model){
				return $model->TGL;
			},
			'WAKTU'=>function($model){
				return $model->WAKTU;
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
