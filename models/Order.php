<?php

namespace app\models;

use app\behaviors\TimestampFormatBehavior;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * This is the model class for table "orders".
 *
 * @property integer $id
 * @property string $sum
 * @property string $create
 * @property integer $user_sender_id
 * @property integer $user_recipient_id
 * @property integer $user_created
 * @property integer $account_id
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'orders';
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
            'createUser' => [
                'class' => BlameableBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => 'user_created',
                ],
            ],
        ];
    }


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sum'], 'number'],
            [['create'], 'safe'],
            [['user_sender_id', 'user_recipient_id', 'cash_balance_sender', 'cash_balance_recipient'], 'required'],
            [['user_sender_id', 'user_recipient_id', 'user_created'], 'integer']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'sum' => 'Sum',
            'create' => 'Create',
            'user_sender_id' => 'User Sender ID',
            'user_recipient_id' => 'User Recipient ID',
            'user_created' => 'User Created'
        ];
    }


}
