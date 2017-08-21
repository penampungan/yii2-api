<?php

namespace api\modules\master\models;

use Yii;
use api\modules\master\models\ProductGroup;
/**
 * This is the model class for table "product".
 *
 * @property string $ID
 * @property string $ACCESS_GROUP
 * @property string $STORE_ID
 * @property integer $GROUP_ID
 * @property string $PRODUCT_ID
 * @property string $PRODUCT_QR
 * @property string $PRODUCT_NM
 * @property string $PRODUCT_WARNA
 * @property string $PRODUCT_SIZE
 * @property string $PRODUCT_SIZE_UNIT
 * @property string $PRODUCT_HEADLINE
 * @property integer $UNIT_ID
 * @property double $STOCK_LEVEL
 * @property integer $INDUSTRY_ID
 * @property string $INDUSTRY_NM
 * @property string $INDUSTRY_GRP_NM
 * @property string $CREATE_BY
 * @property string $CREATE_AT
 * @property string $UPDATE_BY
 * @property string $UPDATE_AT
 * @property integer $STATUS
 * @property string $DCRP_DETIL
 * @property integer $YEAR_AT
 * @property integer $MONTH_AT
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'product';
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
            [['ACCESS_GROUP', 'STORE_ID', 'PRODUCT_ID', 'YEAR_AT', 'MONTH_AT'], 'required'],
            [['UNIT_ID', 'INDUSTRY_ID', 'STATUS', 'YEAR_AT', 'MONTH_AT'], 'integer'],
            [['PRODUCT_SIZE', 'STOCK_LEVEL'], 'number'],
            [['CREATE_AT', 'UPDATE_AT'], 'safe'],
            [['DCRP_DETIL'], 'string'],
            [['ACCESS_GROUP'], 'string', 'max' => 15],
            [['STORE_ID'], 'string', 'max' => 20],
            [['PRODUCT_ID'], 'string', 'max' => 35],
            [['PRODUCT_QR', 'PRODUCT_NM', 'PRODUCT_HEADLINE','GROUP_ID'], 'string', 'max' => 100],
            [['PRODUCT_WARNA', 'PRODUCT_SIZE_UNIT', 'CREATE_BY', 'UPDATE_BY'], 'string', 'max' => 50],
            [['INDUSTRY_NM', 'INDUSTRY_GRP_NM'], 'string', 'max' => 255],
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
            'GROUP_ID' => 'Group  ID',
            'PRODUCT_ID' => 'Product  ID',
            'PRODUCT_QR' => 'Product  Qr',
            'PRODUCT_NM' => 'Product  Nm',
            'PRODUCT_WARNA' => 'Product  Warna',
            'PRODUCT_SIZE' => 'Product  Size',
            'PRODUCT_SIZE_UNIT' => 'Product  Size  Unit',
            'PRODUCT_HEADLINE' => 'Product  Headline',
            'UNIT_ID' => 'Unit  ID',
            'STOCK_LEVEL' => 'Stock  Level',
            'INDUSTRY_ID' => 'Industry  ID',
            'INDUSTRY_NM' => 'Industry  Nm',
            'INDUSTRY_GRP_NM' => 'Industry  Grp  Nm',
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
            'GROUP_NM'=>function($model){
				return $this->groupNm;
			},	
            'PRODUCT_ID'=>function($model){
				return $model->PRODUCT_ID;
			},	
            'PRODUCT_QR'=>function($model){
				return $model->PRODUCT_QR;
			},	
            'PRODUCT_NM'=>function($model){
				return $model->PRODUCT_NM;
			},	
            'CURRENT_STOCK'=>function($model){
				return 'on progress';
			},	
            'HARGA_JUAL'=>function($model){
				return 'on progress';
			},	 
			'DISCOUNT'=>function($model){
				return 'on progress';
			},	
            'PRODUCT_WARNA'=>function($model){
				return $model->PRODUCT_WARNA;
			},	
            'PRODUCT_SIZE'=>function($model){
				return $model->PRODUCT_SIZE;
			},	
            'PRODUCT_SIZE_UNIT'=>function($model){
				return $model->PRODUCT_SIZE_UNIT;
			},	
            'PRODUCT_HEADLINE'=>function($model){
				return $model->PRODUCT_HEADLINE;
			},	
            'UNIT_NM'=>function($model){
				return 'on progress';
			},	
            'STOCK_LEVEL'=>function($model){
				return $model->STOCK_LEVEL;
			},	
            'INDUSTRY_ID'=>function($model){
				return $model->INDUSTRY_ID;
			},	
             'INDUSTRY_NM'=>function($model){
				return $model->INDUSTRY_NM;
			},	
            'INDUSTRY_GRP_NM'=>function($model){
				return $model->INDUSTRY_GRP_NM;
			},	
            'STATUS'=>function($model){
				$rslt=$model->STATUS;
				return $rslt==0?'disable':'enable';
			},	
            'DCRP_DETIL'=>function($model){
				return $model->DCRP_DETIL;
			}
        ];		
	}
	
	public function getProductGroupTbl(){
		return $this->hasOne(ProductGroup::className(), ['GROUP_ID' => 'GROUP_ID']);
	}
	
	public function getGroupNm(){
		$rslt = $this->productGroupTbl['GROUP_NM'];
		if ($rslt){
			return $rslt;
		}else{
			return "None";
		}; 
	}
}
