<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

/**
 * LoginForm is the model behind the login form.
 */
class RegisterForm extends Model
{
    public $email;
    public $password;
    public $password_again;

    private $user;
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password', 'password_again'], 'required'],
            ['password', 'compare', 'compareAttribute' => 'password_again', 'message' => 'Пароли разные.'],
            [
                'email',
                'unique',
                'targetClass' => 'app\models\User',
                'message'     => 'Пользователь с {value} уже существует.'
            ]
        ];
    }

    public function attributeLabels()
    {
        return [
            'email'          => 'Email пользователя (Логин)',
            'password'       => 'Пароль',
            'password_again' => 'Повторный пароль'
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function register()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->setAttributes($this->attributes);

        if(!$user->save()){
            return false;
        }

        $this->user = $user;

        return true;
    }

    /**
     * @return \app\models\User
     */
    public function getUser()
    {
        return $this->user;
    }

    public function confirmedUser()
    {
        if($confirm = EmailConfirm::createForUser($this->getUser())) {
            $url = Url::to(['auth/confirm/', 'code' => $confirm->code], true);
            Yii::$app->mailer->compose()->setTextBody("Ссылка на подтверждение email\n".$url)->send();

            return true;
        }
        return false;
    }
}
