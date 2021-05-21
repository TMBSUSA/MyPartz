<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model backend\models\Bannerads */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="bannerads-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


    <?= $form->field($model, 'Screen')->dropDownList([ 'Home' => 'Home', 'MyGarage' => 'MyGarage', 'SearchResult' => 'SearchResult', ], ['prompt' => 'Select Screen']) ?>

    <?= $form->field($model, 'title')->textInput() ?>

	<?= $form->field($model, 'ExternalURL')->textInput() ?>
    
	<?= $form->field($model, 'ImageURL')->fileInput() ?>
    
    <div class="form-group">
    	<div class="help-block">Size recommendation: 1440 x 224 (width x height in pixels)</div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
