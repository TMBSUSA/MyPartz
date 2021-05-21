<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\SearchhistorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Search history';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="searchhistory-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'SearchId',
            'SearchKey',
            'TotalCount',
            'LastSearchTime',

            //['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
