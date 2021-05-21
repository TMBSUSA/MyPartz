<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Partphoto */

$this->title = 'Create Partphoto';
$this->params['breadcrumbs'][] = ['label' => 'Partphotos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="partphoto-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
