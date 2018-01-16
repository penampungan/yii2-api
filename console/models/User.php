<?php

namespace console\models;


use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends \yii\db\ActiveRecord
//class Userlogintest extends ActiveRecord implements IdentityInterface
{
	
	/**
     * @return \yii\db\Connection the database connection used by this AR class.
     */
    public static function getDb()
    {
        return Yii::$app->get('api_cronjob');
    }
	
	public static function tableName()
    {
        return '{{user}}';
    }

    public function rules()
    {
        return [
			//[['username', 'ACCESS_ID','ACCESS_GROUP'], 'required'],
            [['auth_key'], 'string'],
            [['status', 'ACCESS_SITE', 'ONLINE', 'lft', 'rgt', 'lvl', 'icon_type', 'YEAR_AT', 'MONTH_AT'], 'integer'],
            [['create_at', 'updated_at'], 'safe'],
            [['username', 'password_hash', 'password_reset_token', 'email', 'icon'], 'string', 'max' => 255],
            [['ACCESS_ID', 'ACCESS_GROUP'], 'string', 'max' => 15],
            [['ACCESS_LEVEL'], 'string', 'max' => 50],
            [['ID_GOOGLE','ID_FB','ID_TWITTER','ID_LINKEDIN','ID_YAHOO'], 'string', 'max' => 255],
		];
    }

	public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'User Name'),
			'password_hash' => Yii::t('app', 'Password Hash'),
			'ACCESS_ID' => Yii::t('app', 'ACCESS_ID'),
			'ACCESS_LEVEL' => Yii::t('app', 'ACCESS_LEVEL')			
        ];
    }
	
	
}
?>
