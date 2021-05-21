<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use backend\models\Partdetail;

/* @var $this yii\web\View */
/* @var $model backend\models\Seller */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Sellers', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="seller-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->UserId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->UserId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'UserId',
            'UserName',
            'Email:email',
            //'Password',
            'Mobile',
            //'ProfilePic',
            //'FirstName',
            //'LastName',
            //'Gender',
            //'LogInStatus',
            //'LastLogin',
            //'ResetToken',
            //'AccessToken',
            'Status',
            'CreatedOn',
			[
				'label' => 'Part listed',
				'value' => Partdetail::find()->where(['UserId' => $model->UserId])->count(),
			],
            //'UpdatedOn',
        ],
    ]) ?>

</div>
