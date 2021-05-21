<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\SearchhistorySearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="searchhistory-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'SearchId') ?>

    <?= $form->field($model, 'SearchKey') ?>

    <?= $form->field($model, 'TotalCount') ?>

    <?= $form->field($model, 'LastSearchTime') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
