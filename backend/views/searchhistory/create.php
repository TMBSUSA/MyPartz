<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Searchhistory */

$this->title = 'Create Searchhistory';
$this->params['breadcrumbs'][] = ['label' => 'Searchhistories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="searchhistory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
