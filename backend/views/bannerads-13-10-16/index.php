<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BanneradsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Banner Ads';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bannerads-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
	
    <?php /*?><p>
        <?= Html::a('Create Banner Ads', ['create'], ['class' => 'btn btn-success']) ?>
    </p><?php */?>
        
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
				'attribute' => 'image',
				'format' => 'raw',    
				'value' => function ($data) {
					return "<img src='".Yii::$app->homeUrl."uploads/".$data->ImageURL."' height='50' title='MyPartz'>";
				},
			],
            'ExternalURL:ntext',
            'Screen',
			
			[
				'attribute' => 'Action',
				'format' => 'raw',
				'value' => function ($model) {                      
						return "<a href='".Yii::getAlias('@web')."/bannerads/update/".$model->BannerID."' title='Update' aria-label='Update'><span class='glyphicon glyphicon-pencil'></span></a> <a href='".Yii::getAlias('@web')."/bannerads/delete/".$model->BannerID."' title='Reset' aria-label='Reset' data-confirm='Are you sure you want to reset this item?' data-method='post' data-pjax='0'><span class='glyphicon glyphicon-trash'></span></a>";
				},
			],

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
