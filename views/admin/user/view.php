<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model app\models\User */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $addMoneyForm \app\models\forms\AddMoneyForm */
/* @var $transferMoneyForm \app\models\forms\TransferMoneyForm */

$this->title = 'Редактирование пользователя: '.$model->email.' ('.$model->id.')';
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Детально';
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('@app/views/order/money_alert')?>

    <p>
        <?= Html::a('Редактировать пользователя', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить пользователя', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Вы уверены что хотите удалить пользователя?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id:integer:ID',
            'email:email:Email',
            'create:datetime:Дата регистрации',
            'confirmed:boolean:Подтверждён',
            [
                'label'=>'Баланс',
                'value'=> $model->getAccount()->balance,
            ]
        ],
    ]) ?>
    <?php Pjax::begin(['enablePushState' => true]); ?>
        <h3>Добавить деньги</h3>
        <?php $form = ActiveForm::begin([
            'method'  => 'post',
            'action'  => ['admin/user/view', 'id' => $model->id],
            'options' => ['data-pjax' => '', 'class' => 'form-inline']
        ]); ?>
            <?php $moneyInput = $form->field($addMoneyForm, 'money', ['options' => ['class' => 'form-group']]);
            $moneyInput->enableClientValidation = false;
            $moneyInput->template = '{input}';

            echo $moneyInput->textInput(['placeholder' => 'Сумма']);
            ?>
            <div class="form-group">
                <?= Html::submitButton('Зачислить деньги', ['class' => 'btn btn-primary', 'name' => 'add-money']) ?>
            </div>
        <?php ActiveForm::end(); ?>

        <h3>Отправить деньги</h3>

        <?php $form = ActiveForm::begin([
            'method'  => 'post',
            'action'  => ['admin/user/view', 'id' => $model->id],
            'options' => ['data-pjax' => '', 'class' => 'form-inline']
        ]); ?>
        <?php $moneyInput = $form->field($transferMoneyForm, 'sum', ['options' => ['class' => 'form-group']]);
        $moneyInput->enableClientValidation = false;
        $moneyInput->template = '{input}';

        echo $moneyInput->textInput(['placeholder' => 'Сумма']);
        ?>

        <?php $emailInput = $form->field($transferMoneyForm, 'email', ['options' => ['class' => 'form-group']]);
            $emailInput->enableClientValidation = false;
            $emailInput->template = '{input}';

            echo $emailInput->textInput(['placeholder' => 'Email']);
        ?>
            <div class="form-group">
                <?= Html::submitButton('Отправить деньги', ['class' => 'btn btn-primary', 'name' => 'add-money-by-email']) ?>
            </div>
        <?php ActiveForm::end(); ?>

        <h3>Балансовые операции</h3>
        <?= $this->render('@app/views/order/_grid_admin', ['dataProvider' => $dataProvider]);?>
    <?php Pjax::end(); ?>

</div>
