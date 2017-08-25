<?php

namespace api\modules\transaksi\models;

use Yii;

/**
 * This is the model class for table "trans_storan".
 *
 * @property string $ID
 * @property string $ACCESS_GROUP
 * @property string $STORE_ID
 * @property string $ACCESS_ID
 * @property string $OPENCLOSE_ID
 * @property string $TGLWAKTU
 * @property string $TOTALCASH
 * @property string $NOMINAL_STORAN
 * @property string $SISA_STORAN
 * @property string $CREATE_BY
 * @property string $CREATE_AT
 * @property string $UPDATE_BY
 * @property string $UPDATE_AT
 * @property integer $STATUS
 * @property string $DCRP_DETIL
 * @property integer $YEAR_AT
 * @property integer $MONTH_AT
 */
class TransStoran extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'trans_storan';
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
            [['OPENCLOSE_ID', 'YEAR_AT', 'MONTH_AT'], 'required'],
            [['TGLWAKTU', 'CREATE_AT', 'UPDATE_AT'], 'safe'],
            [['TOTALCASH', 'NOMINAL_STORAN', 'SISA_STORAN'], 'number'],
            [['STATUS', 'YEAR_AT', 'MONTH_AT'], 'integer'],
            [['DCRP_DETIL'], 'string'],
            [['ACCESS_GROUP', 'ACCESS_ID'], 'string', 'max' => 15],
            [['STORE_ID'], 'string', 'max' => 20],
            [['OPENCLOSE_ID', 'CREATE_BY', 'UPDATE_BY'], 'string', 'max' => 50],
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
            'OPENCLOSE_ID' => 'Openclose  ID',
            'TGLWAKTU' => 'Tglwaktu',
            'TOTALCASH' => 'Totalcash',
            'NOMINAL_STORAN' => 'Nominal  Storan',
            'SISA_STORAN' => 'Sisa  Storan',
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
			'ACCESS_GROUP'=>function($model){
				return $model->ACCESS_GROUP;
			},
			'STORE_ID'=>function($model){
				return $model->STORE_ID;
			},
			'ACCESS_ID'=>function($model){
				return $model->ACCESS_ID;
			},
			'OPENCLOSE_ID'=>function($model){
				return $model->OPENCLOSE_ID;
			},
			'TGLWAKTU'=>function($model){
				return $model->TGLWAKTU;
			},					
			'TOTALCASH'=>function($model){
				return $model->TOTALCASH;
			},
			'NOMINAL_STORAN'=>function($model){
				return $model->NOMINAL_STORAN;
			},
			'SISA_STORAN'=>function($model){
				return $model->SISA_STORAN;
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
