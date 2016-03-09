<?php

use yii\grid\GridView;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <h1><?=Html::encode($this->title)?></h1>
    <?php echo $this->render('_search', ['model' => $searchModel]); ?>
    <p>
        <?=Html::a('Создать пользователя', ['create'], ['class' => 'btn btn-success'])?>
    </p>
    <?=GridView::widget([
        'dataProvider' => $dataProvider,
        'rowOptions'   => ['class' => 'item'],
        'columns'      => [
            [
                'attribute'=>'id',
                'label'=>'ID',
                'format'=>'raw',
                'content'=>function($data){
                    return Html::a($data->id, ['view','id' => $data->id]);
                }
            ],
            'email:email:Email',
            'confirmed:boolean:Подтверждён',
            [
                'attribute'=>'balance',
                'label'=>'Баланс',
                'format'=>'raw',
                'content'=>function($data){
                    if($account = $data->getAccount()){
                        return $account->balance;
                    }

                    return 'Нет данных';
                }
            ],
            'create:datetime:Дата регистрации',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ])?>
</div>
