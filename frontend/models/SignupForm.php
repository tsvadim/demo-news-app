<?php
namespace frontend\models;

use common\helpers\ParamHelper;
use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $username;
    public $email;
    public $password;

    /** @var User|null */
    private $_model;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return bool|User whether the creating new account was successful and email was sent
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->generateApiToken();
        $user->generateEmailVerificationToken();
        if($user->save() && $this->sendEmail($user)){
            $this->setUserModel($user);
            return  true;
        }
        return false;
    }

    /**
     * Sends confirmation email to user
     * @param User $user user model to with email should be send
     * @return bool whether the email was sent
     */
    protected function sendEmail($user)
    {
        /** Return true if email verification is disabled */
        if(!ParamHelper::isEnableEmailVerification())
            return true;

        return Yii::$app
            ->mailer
            ->compose(['html' => 'emailVerify-html', 'text' => 'emailVerify-text'], ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name . ' robot'])
            ->setTo($this->email)
            ->setSubject('Account registration at ' . Yii::$app->name)
            ->send();
    }

    /**
     * @param User|null $model
     */
    protected function setUserModel($model)
    {
        $this->_model = $model;
    }

    /**
     * Return signup user model
     * @return User|null
     */
    public function getUserModel()
    {
        return $this->_model;
    }
}
