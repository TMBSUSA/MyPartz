<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Vehiclemake */

$this->title = 'Update Vehicle make';
$this->params['breadcrumbs'][] = ['label' => 'Vehicle Make', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="vehiclemake-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
