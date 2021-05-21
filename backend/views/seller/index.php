<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\export\ExportMenu;
use backend\models\Partdetail;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SellerSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Seller';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seller-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Seller', ['create'], ['class' => 'btn btn-success']) ?>
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
			[
				'attribute' => 'Part Listed',
				'format' => 'raw',
				'value' => function ($model) {                      
						return Partdetail::find()->where(['UserId' => $model->UserId])->count() ." <a href='partdetail?PartdetailSearch%5BPartName%5D=&PartdetailSearch%5BPartCondition%5D=&PartdetailSearch%5BPartMfgYear%5D=&PartdetailSearch%5BUserId%5D=".$model->UserName."&PartdetailSearch%5BPartTypeId%5D=&PartdetailSearch%5BVehiclemakeId%5D=&PartdetailSearch%5BVehicleModelId%5D=&PartdetailSearch%5BStatus%5D='>View Parts</a>";
				},
			],
			'Status',
            'CreatedOn',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
