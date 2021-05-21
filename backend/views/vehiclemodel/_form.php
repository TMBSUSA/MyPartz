<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\Vehiclemake;

/* @var $this yii\web\View */
/* @var $model backend\models\Vehiclemodel */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="vehiclemodel-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'ModelName')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'MakeId')->dropDownList(
			ArrayHelper:: map(Vehiclemake:: find()->All(),'MakeId','MakeName'),
			['prompt' => 'Select Vehicle Make']
	) ?>
    
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
