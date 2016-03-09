<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;

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
class OrderSearch extends Order
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['sum'], 'number'],
            [['create'], 'safe'],
            [['user_sender_id', 'user_recipient_id', 'user_created', 'account_id'], 'required'],
            [['user_sender_id', 'user_recipient_id', 'user_created', 'account_id'], 'integer']
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
            'user_created' => 'User Created',
            'account_id' => 'Account ID',
        ];
    }

    public static function findAllForUser(User $user)
    {

        $query = Order::find();
        $query->andFilterWhere([
            'or',
            'user_sender_id = '.$user->id,
            'user_recipient_id = '.$user->id,
        ]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $dataProvider;

    }
}
