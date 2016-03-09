<?php

/* @var $this yii\web\View */
/* @var $account app\models\Account */
/* @var $user app\models\User */
/* @var $orderDataProvider yii\data\ActiveDataProvider */

use yii\grid\GridView;
use yii\helpers\Html;

$this->title = 'Личный кабинет';
?>
<div class="site-index">
    <div class="panel panel-default">
        <div class="panel-body">
            Баланс: <?= $account->balance;?> денежных едениц
        </div>
    </div>

    <?= $this->render('@app/views/order/_grid_user', [
        'dataProvider' => $orderDataProvider,
        'user'         => $user
    ])?>

</div>
