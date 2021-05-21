<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\PartphotoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Partphotos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partphoto-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Partphoto', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'PhotoId',
            'PartId',
            'PhotoUrl:url',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
