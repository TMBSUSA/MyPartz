<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PartdetailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Part Details';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partdetail-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Part Detail', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?php 
	
	$gridColumns = [
		['class' => 'yii\grid\SerialColumn'],
		'PartName',
		'PartCondition',
		'YearFrom',
		'YearTo',
		'user.UserName',
		'partType.part_type_name',
		'vehiclemake.MakeName',
		'vehicleModel.ModelName',
		'Status'
	];
	
	echo ExportMenu::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => $gridColumns
	]);
	
	?>
   
	<?=
	GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'PartName:ntext',
            'PartCondition',
            'YearFrom',
            'YearTo',
			[
				'attribute' => 'UserId',
				'format' => 'raw',
            	'value' => function($data){
                	return Html::a(Html::encode($data->user->UserName),Yii::$app->homeUrl."seller/".$data->UserId);
           		}
   			],
			[
				'attribute' => 'PartTypeId',
				'value' => 'partType.part_type_name',
   			],
			[
				'attribute' => 'VehiclemakeId',
				'value' => 'vehiclemake.MakeName',
   			],
			[
				'attribute' => 'VehicleModelId',
				'value' => 'vehicleModel.ModelName',
   			],				
            // 'Lat',
            // 'Lng',
            // 'Location',
            'Status',
			[
				'attribute' => 'Notification',
				'format' => 'raw',
				'value' => function ($model) {                      
						return "<a class='btn btn-primary btn-xs' href='".Yii::$app->homeUrl."partdetail/notice/".$model->PartId."'>Notify for Renewal</a>";
				},
			],
            // 'CreatedOn',
            // 'UpdatedOn',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    
</div>
