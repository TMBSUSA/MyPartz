<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Vehiclemake */

$this->title = 'Create Vehicle Make';
$this->params['breadcrumbs'][] = ['label' => 'Vehicle Make', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="vehiclemake-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
