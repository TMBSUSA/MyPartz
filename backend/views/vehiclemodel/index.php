<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\VehiclemodelSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List of Vehicle Models';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehiclemodel-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Vehicle Model', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php 
	
	$gridColumns = [
		['class' => 'yii\grid\SerialColumn'],
		'make.MakeName',
		'ModelName'
	];
	
	echo ExportMenu::widget([
		'dataProvider' => $dataProvider,
		'filterModel' => $searchModel,
		'columns' => $gridColumns
	]);
	
	?>
    
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
			
            'ModelName',
			[
				'attribute' => 'MakeId',
				'value' => 'make.MakeName',
			],

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
