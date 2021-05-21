<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Vehiclemodel */

$this->title = 'Create Vehicle Model';
$this->params['breadcrumbs'][] = ['label' => 'Vehicle Model', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehiclemodel-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
