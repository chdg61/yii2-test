<?php
/**
 * D61 06.03.2016
 *
 */
?>
<?php if($flash = Yii::$app->session->getFlash('money.success')){?>
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?= $flash; ?>
    </div>
<?php }?>


<?php if($flash = Yii::$app->session->getFlash('money.error')){?>
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <?= $flash; ?>
    </div>
<?php }?>