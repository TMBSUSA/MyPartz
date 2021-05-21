<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Vehiclemodel */

$this->title = 'Update Vehicle Model';
$this->params['breadcrumbs'][] = ['label' => 'Vehicle Model', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vehiclemodel-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
