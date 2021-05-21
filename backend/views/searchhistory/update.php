<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Searchhistory */

$this->title = 'Update Searchhistory: ' . $model->SearchId;
$this->params['breadcrumbs'][] = ['label' => 'Searchhistories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->SearchId, 'url' => ['view', 'id' => $model->SearchId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="searchhistory-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
