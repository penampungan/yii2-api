<?php

namespace api\modules\master\models;

use Yii;

/**
 * This is the model class for table "product_image".
 *
 * @property string $ID
 * @property string $PRODUCT_ID
 * @property string $PRODUCT_IMAGE
 * @property string $CREATE_BY
 * @property string $CREATE_AT
 * @property string $UPDATE_BY
 * @property string $UPDATE_AT
 * @property integer $STATUS
 * @property string $DCRP_DETIL
 * @property integer $YEAR_AT
 * @property integer $MONTH_AT
 */
class ProductImage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product_image';
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
            [['PRODUCT_ID', 'YEAR_AT', 'MONTH_AT'], 'required'],
            [['PRODUCT_IMAGE', 'DCRP_DETIL'], 'string'],
            [['CREATE_AT', 'UPDATE_AT'], 'safe'],
            [['STATUS', 'YEAR_AT', 'MONTH_AT'], 'integer'],
            [['PRODUCT_ID'], 'string', 'max' => 35],
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
            'PRODUCT_ID' => 'Product  ID',
            'PRODUCT_IMAGE' => 'Product  Image',
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
			'PRODUCT_ID'=>function($model){
				return $model->PRODUCT_ID;
			},
			'PRODUCT_IMAGE'=>function($model){
				return $model->PRODUCT_IMAGE;
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
