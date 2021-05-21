<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Searchhistory */

$this->title = $model->SearchId;
$this->params['breadcrumbs'][] = ['label' => 'Searchhistories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="searchhistory-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->SearchId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->SearchId], [
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
            'SearchId',
            'SearchKey',
            'TotalCount',
            'LastSearchTime',
        ],
    ]) ?>

</div>
