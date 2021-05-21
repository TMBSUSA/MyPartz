<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Bannerads */

$this->title = 'Create Bannerads';
$this->params['breadcrumbs'][] = ['label' => 'Bannerads', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="bannerads-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
