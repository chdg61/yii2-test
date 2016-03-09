<?php
/* @var $this \yii\web\View */
/* @var $model \app\models\forms\TransferMoneyForm */
/* @var $form ActiveForm */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Перевод денег';
?>

<div class="site-money">
    <h3>Отправить деньги</h3>

    <?= $this->render('@app/views/order/money_alert')?>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sum') ?>
    <?= $form->field($model, 'email') ?>

    <div class="form-group">
        <?= Html::submitButton('Отправить деньги', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
