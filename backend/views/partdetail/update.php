<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Partdetail */

$this->title = 'Update Part Detail';
$this->params['breadcrumbs'][] = ['label' => 'Part Detail', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="partdetail-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
