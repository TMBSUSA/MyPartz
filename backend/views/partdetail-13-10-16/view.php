<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model backend\models\Partdetail */

$this->title = '';
$this->params['breadcrumbs'][] = ['label' => 'Part Detail', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="partdetail-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->PartId], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->PartId], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

	<table class="table table-striped table-bordered detail-view">
    	<tbody>
        	<tr>
            	<th>Part Name</th>
                <td><?= $model->PartName ?></td>
            </tr>
            <tr>
            	<th>Part Details</th>
                <td><?= $model->PartDetails ?></td>
            </tr>
            <tr>
            	<th>Part Condition</th>
                <td><?= $model->PartCondition ?></td>
            </tr>
            <tr>
            	<th>Part Mfg Year</th>
                <td><?= $model->PartMfgYear ?></td>
            </tr>
            <tr>
            	<th>Images</th>
                <td><?php 
					$imgs = $this->context->GetImages($model->PartId);
					if(!empty($imgs)){
						foreach ($imgs as $img){
							echo "<img src='".$img."' width='100'>";
						}
					} ?></td>
            </tr>
            <tr>
            	<th>Status</th>
                <td><?= $model->Status ?></td>
            </tr>
            <tr>
            	<th>Send Notification</th>
                <td><a class="btn btn-primary" href="<?= Yii::$app->homeUrl ?>partdetail/notice/<?= $model->PartId ?>">Notify for Renewal</a></td>
            </tr>
        </tbody>
    </table>
</div>
