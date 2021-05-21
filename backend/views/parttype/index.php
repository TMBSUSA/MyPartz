<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\ParttypeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'List of Part Types';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="parttype-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Part Type', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php 
	
	$gridColumns = [
		['class' => 'yii\grid\SerialColumn'],
		'part_type_name'
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

            'part_type_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
