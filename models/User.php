<?php

namespace app\models;

use app\behaviors\TimestampFormatBehavior;
use app\models\event\AfterRegisterUser;
use app\models\queries\UserQuery;
use Yii;
use yii\db\ActiveRecord;


class User extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
    const ROLE_USER = 1;
    const ROLE_ADMIN = 2;

    const EVENT_AFTER_REGISTER = 'afterRegisterUser';

    const SCENARIO_CREATE = 'create';

    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if($this->password){
                $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
            }

            return true;
        }

        return false;
    }

    public function behaviors()
    {
        return [
            'timestamp' => [
                'class' => TimestampFormatBehavior::className(),
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => 'create',
                ],
            ],
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if($insert) {
            Yii::$app->trigger(self::EVENT_AFTER_REGISTER, new AfterRegisterUser(['clientId' => $this->getId()]));
        }
    }


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['email', 'password'], 'required'],
            [['email', 'password'], 'string', 'max' => 255]
        ];
    }

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['email', 'password', 'group'];

        return $scenarios;
    }

    /**
     * @return null|Account
     */
    public function getAccount()
    {
        return $this->hasOne(Account::className(),['user_id' => 'id'])->one();
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getAttribute('id');
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->getAttribute('auth_key');
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAttribute('auth_key') === $authKey;
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }

    /**
     * @inheritdoc
     * @return UserQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }
}
