<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\PartdetailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="partdetail-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'PartId') ?>

    <?= $form->field($model, 'PartName') ?>

    <?= $form->field($model, 'PartDetails') ?>

    <?= $form->field($model, 'PartCondition') ?>

    <?= $form->field($model, 'YearFrom') ?>

    <?= $form->field($model, 'YearTo') ?>

    <?php // echo $form->field($model, 'UserId') ?>

    <?php // echo $form->field($model, 'PartTypeId') ?>

    <?php // echo $form->field($model, 'VehiclemakeId') ?>

    <?php // echo $form->field($model, 'VehicleModelId') ?>

    <?php // echo $form->field($model, 'Lat') ?>

    <?php // echo $form->field($model, 'Lng') ?>

    <?php // echo $form->field($model, 'Location') ?>

    <?php // echo $form->field($model, 'Status') ?>

    <?php // echo $form->field($model, 'CreatedOn') ?>

    <?php // echo $form->field($model, 'UpdatedOn') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
