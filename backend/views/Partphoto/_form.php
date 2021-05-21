<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Partphoto */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partphoto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'PartId')->textInput() ?>

    <?= $form->field($model, 'PhotoUrl')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
