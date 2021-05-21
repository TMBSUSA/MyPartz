<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Partdetail */

$this->title = 'Create Part Detail';
$this->params['breadcrumbs'][] = ['label' => 'Part Details', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partdetail-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
