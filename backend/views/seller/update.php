<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Seller */

$this->title = 'Update User';
$this->params['breadcrumbs'][] = ['label' => 'User', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="seller-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
