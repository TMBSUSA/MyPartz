<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model backend\models\Bannerads */

$this->title = 'Update Banner Ads';
$this->params['breadcrumbs'][] = ['label' => 'Banner Ads', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="bannerads-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
