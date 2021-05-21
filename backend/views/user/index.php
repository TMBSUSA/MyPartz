<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use backend\models\Partdetail;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SellerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seller-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    
    <?php 
	
	$gridColumns = [
		['class' => 'yii\grid\SerialColumn'],
		'UserName',
		'Email',
		'Mobile',
		'Status',
		'CreatedOn'
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

    		'UserName',
	        'Email:email',
            'Mobile',
			'Status',
            'CreatedOn',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
