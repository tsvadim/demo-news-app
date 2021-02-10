<?php
/**
 * @copyright Copyright (c) 2020 VadimTs
 * @link http://good-master.com.ua/
 * Creator: VadimTs
 * Date: 23.02.2020
 */

namespace backend\models\users;


use Yii;
use yii\helpers\ArrayHelper;

class User extends \common\models\User
{
    const SCENARIO_CREATE_PASSWORD = 'createPassword';

    public $new_pass;
    public $new_pass_repeat;
    public $roles;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','full_name','country','phone'], 'filter', 'filter' => 'trim'],
            [['username','full_name','country','phone'], 'filter', 'filter' => 'strip_tags'],
            ['username', 'required'],
            ['username', 'unique', 'message' => \Yii::t('app','This username has already been taken.')],
            ['username', 'match', 'pattern' => '/^[A-Za-z0-9_]+$/u','message' => \Yii::t('app','Incorrect symbols. Only [A-z0-9_].')],
            [['username','full_name'], 'string', 'min' => 4, 'max' => 128],
            ['country', 'string', 'max' => 128],
            ['phone', 'integer','integerOnly' => true,'message' => Yii::t('app', "{attribute} must be a number.")],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'message' => \Yii::t('app','This email address has already been taken.')],
            ['roles', 'each', 'rule' => ['in', 'range' => array_keys(\Yii::$app->authManager->getRoles())], 'skipOnEmpty' => true],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE,self::STATUS_INACTIVE, self::STATUS_DELETED]],

            [['new_pass','new_pass_repeat'],'required', 'on'=>self::SCENARIO_CREATE_PASSWORD],
            [['new_pass','new_pass_repeat'], 'string', 'min' => 6, 'on'=>self::SCENARIO_CREATE_PASSWORD],
            ['new_pass_repeat','compare','compareAttribute'=>'new_pass','on'=>self::SCENARIO_CREATE_PASSWORD],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(parent::attributeLabels(),[
            'new_pass'=>\Yii::t('app','New password'),
            'new_pass_repeat'=>\Yii::t('app','Confirm password'),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert))
        {
            if($this->isNewRecord)
                $this->generateAuthKey();

            if($this->scenario == self::SCENARIO_CREATE_PASSWORD)
            {
                $this->setPassword($this->new_pass);
            }
            return true;
        }
        return false;
    }
}