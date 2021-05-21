<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Partphoto */

$this->title = 'Update Partphoto: ' . $model->PhotoId;
$this->params['breadcrumbs'][] = ['label' => 'Partphotos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->PhotoId, 'url' => ['view', 'id' => $model->PhotoId]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="partphoto-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
