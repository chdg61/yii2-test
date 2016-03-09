<?php

/* @var $this yii\web\View */
/* @var $account app\models\Account */
/* @var $dataProvider yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;


$contentHelper = function($userId){
    if($userId > 0){
        return Html::a($userId, ['admin/user/view', 'id' => $userId]);
    }

    return '-';
};
?>
<?=GridView::widget([
    'dataProvider' => $dataProvider,
    'columns'      => [
        'id:integer:ID',
        'sum:decimal:Сумма',
        'create:datetime:Дата операции',
        [
            'attribute' => 'user_sender_id',
            'label'     => 'Отправитель',
            'format'    => 'raw',
            'content'   => function($data) use ($contentHelper){
                return $contentHelper($data->user_sender_id);
            }
        ],
        [
            'attribute' => 'user_recipient_id',
            'label'     => 'Получатель',
            'format'    => 'raw',
            'content'   => function($data) use ($contentHelper){
                return $contentHelper($data->user_recipient_id);
            }
        ],
        [
            'attribute' => 'user_created',
            'label'     => 'Создатель',
            'format'    => 'raw',
            'content'   => function($data) use ($contentHelper){
                return $contentHelper($data->user_created);
            }
        ],
        'cash_balance_sender:decimal:Остаток у отправителя',
        'cash_balance_recipient:decimal:Остаток у получателя',
    ],
])?>