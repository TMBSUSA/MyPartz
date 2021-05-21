<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SellerSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="seller-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'UserId') ?>

    <?= $form->field($model, 'UserName') ?>

    <?= $form->field($model, 'Email') ?>

    <?= $form->field($model, 'Password') ?>

    <?= $form->field($model, 'Mobile') ?>

    <?php // echo $form->field($model, 'ProfilePic') ?>

    <?php // echo $form->field($model, 'FirstName') ?>

    <?php // echo $form->field($model, 'LastName') ?>

    <?php // echo $form->field($model, 'Gender') ?>

    <?php // echo $form->field($model, 'LogInStatus') ?>

    <?php // echo $form->field($model, 'LastLogin') ?>

    <?php // echo $form->field($model, 'ResetToken') ?>

    <?php // echo $form->field($model, 'AccessToken') ?>

    <?php // echo $form->field($model, 'Status') ?>

    <?php // echo $form->field($model, 'CreatedOn') ?>

    <?php // echo $form->field($model, 'UpdatedOn') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
