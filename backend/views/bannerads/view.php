<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Bannerads */

$this->title = $model->BannerID;
$this->params['breadcrumbs'][] = ['label' => 'Bannerads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bannerads-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->BannerID], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->BannerID], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'BannerID',
            'title',
            'ImageURL:ntext',
            'ExternalURL:ntext',
            'Screen',
        ],
    ]) ?>

</div>
