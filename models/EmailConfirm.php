<?php

namespace app\models;

use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "email_confirm".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $key
 */
class EmailConfirm extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'email_confirm';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'code'], 'required'],
            [['user_id'], 'integer'],
            [['code'], 'string', 'max' => 255]
        ];
    }

    public static function createForUser(User $user)
    {
        $self = new static();
        $code = preg_replace('/[_\-\+\/]/iu', '', Yii::$app->security->generateRandomString(15));
        $self->setAttribute('user_id', $user->getId());
        $self->setAttribute('code', $code);

        if(!$self->save()){
            return null;
        }

        return $self;
    }

    /**
     * @return null|User
     */
    public function getUser()
    {
        return $this->hasOne('app\models\User', ['id' => 'user_id'])->one();
    }
}
