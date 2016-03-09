<?php

/* @var $this yii\web\View */
/* @var $account app\models\Account */
/* @var $user app\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

use app\models\Order;
use yii\grid\GridView;
use yii\grid\SerialColumn;

?>
<style>
    .deposit
    {
        color: green;
    }
    .withdrawal
    {
        color: red;
    }
</style>
<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'      => [
        ['class' => SerialColumn::className()],
        'sum:decimal:Сумма',
        'create:datetime:Дата операции',
        [
            'label'     => 'Тип операции',
            'format'    => 'html',
            'value'   => function($order) use ($user){
                /** @var Order $order */
                if($order->user_sender_id === $user->getId()){
                    return '<strong class="withdrawal">(Снятие со счета)</strong> Перевод пользователю с ID: '.$order->user_recipient_id;
                }
                if($order->user_recipient_id === $user->getId()){
                    if($order->user_sender_id > 0){
                        return '<strong class="deposit">(Пополнение счета)</strong> Перевод от пользователю с ID: '.$order->user_sender_id;
                    }

                    return '<strong class="deposit">(Пополнение счета)</strong>';
                }

                return 'неизвестный тип операции';
            }
        ],
    ],
])?>