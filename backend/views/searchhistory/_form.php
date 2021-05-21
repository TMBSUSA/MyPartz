<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Searchhistory */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="searchhistory-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'SearchKey')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'TotalCount')->textInput() ?>

    <?= $form->field($model, 'LastSearchTime')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
