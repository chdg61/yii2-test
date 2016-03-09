<?php

namespace app\models\admin;

use app\models\User;
use Yii;
use yii\base\Model;

class UserCreateForm extends Model
{

    public $email;
    public $password;
    public $group;

    private $user;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['email', 'password', 'group'], 'required'],
            ['email', 'email'],
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
            'email'    => 'Email (Логин)',
            'password' => 'Пароль',
            'group'    => 'Группа пользователя'
        ];
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function create()
    {
        if (!$this->validate()) {
            return false;
        }

        $user = new User();
        $user->setScenario(User::SCENARIO_CREATE);
        $t = $this->attributes;
        $user->setAttributes($this->attributes);
        $user->setAttribute('confirmed', 1);

        if(!$user->save()){
            return false;
        }

        $this->user = $user;

        return true;
    }

    /**
     *
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }
}
