<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use backend\models\Seller;
use backend\models\Parttype;
use backend\models\Vehiclemake;
use backend\models\Vehiclemodel;
use kartik\file\FileInput;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model backend\models\Partdetail */
/* @var $form yii\widgets\ActiveForm */
?>
<div class="partdetail-form">
    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
	<?= $form->field($model, 'UserId')->dropDownList(
			ArrayHelper:: map(Seller:: find()->All(),'UserId','UserName'),
			['prompt' => 'Select User']
	) ?>
    
    <?= $form->field($model, 'PartName')->textInput() ?>
    <?= $form->field($model, 'PartDetails')->textarea(['rows' => 6]) ?>
	<div class="form-group field-partdetail-imageFiles">
    <label class="control-label">Add Attachments</label>	
	<?= FileInput::widget([
			'name' => 'imageFiles[]',
			'options'=>[
				'multiple'=>true
			],
			'pluginOptions' => [
				'initialPreview'=>$this->context->GetImages($model->PartId),
				'initialPreviewAsData'=>true,
				'uploadUrl' => Url::to(['/site/uploads']),
				'maxFileCount' => 3
			]
		]);
	?>
    </div>
    <?= $form->field($model, 'PartCondition')->dropDownList([ 'A' => 'A', 'B' => 'B', 'C' => 'C', 'D' => 'D', ], ['prompt' => '']) ?>
    <?= $form->field($model, 'PartMfgYear')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'PartPrice')->textInput() ?>
    <?= $form->field($model, 'PartTypeId')->widget(Select2::classname(), [
			'data' => ArrayHelper:: map(Parttype:: find()->All(),'part_type_id','part_type_name'),
			'options' => ['placeholder' => 'Select Part Type'],
			'pluginOptions' => [
				'allowClear' => true
			],
		]); ?>
    
    <?= $form->field($model, 'VehiclemakeId')->dropDownList(
			ArrayHelper:: map(Vehiclemake:: find()->All(),'MakeId','MakeName'),
			[	
				'prompt' => 'Select Vehicle Make',
				'onchange'=>'
					$.post( "searchmodel?id='.'"+$(this).val(), function(data){
						$("select#partdetail-vehiclemodelid").html(data);
					});'
			]); ?>
	
    <?= $form->field($model, 'VehicleModelId')->dropDownList(
			ArrayHelper:: map(Vehiclemodel:: find()->All(),'ModelId','ModelName'),
			['prompt' => 'Select Vehicle Model']
	) ?>
    <?= $form->field($model, 'Location')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'Status')->dropDownList([ 'Active' => 'Active', 'Inactive' => 'Inactive', ], ['prompt' => '']) ?>
    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
        <a class="btn btn-primary" href="<?= Yii::$app->homeUrl ?>partdetail/notice/<?= $model->PartId ?>">Notify for Renewal</a>
    </div>
    <?php ActiveForm::end(); ?>
</div>
<style>
.kv-file-remove, .kv-file-upload, .file-upload-indicator{display:none}
.file-actions{margin:0}
</style>