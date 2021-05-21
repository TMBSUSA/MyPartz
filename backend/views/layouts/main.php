<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <style>
	.navbar-inverse{ background-color: #2E507D; }
	.navbar-inverse .navbar-nav > li > a, .navbar-inverse .btn-link{color: #ffffff;}
	</style>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'My Partz',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar-inverse navbar-fixed-top',
        ],
    ]);
    
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems = [
			['label' => 'Home', 'url' => ['/site/index']],
			['label' => 'Actions', 'url' => ['#'], 'items' => [
				['label' => 'End Users', 'url' => Yii::$app->homeUrl.'user'],
				['label' => 'Sellers', 'url' => Yii::$app->homeUrl.'seller'],
				['label' => 'Part Details', 'url' => Yii::$app->homeUrl.'partdetail'],
				['label' => 'Vehicle Make', 'url' => Yii::$app->homeUrl.'vehiclemake'],
				['label' => 'Vehicle Model', 'url' => Yii::$app->homeUrl.'vehiclemodel'],
				['label' => 'Vehicle Part Type', 'url' => Yii::$app->homeUrl.'parttype'],
				['label' => 'Search History', 'url' => Yii::$app->homeUrl.'searchhistory'],
				['label' => 'Banner Ads', 'url' => Yii::$app->homeUrl.'bannerads'],
				['label' => 'Change Password', 'url' => Yii::$app->homeUrl.'site/change_password'],
			]],
		];
		$menuItems[] = '<li>'
            . Html::beginForm(['/site/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer">
    <div class="container">
        <p class="pull-left">&copy; My Partz <?= date('Y') ?></p>

        <p class="pull-right">Powered by <a href="http://www.lokavasoftware.com" target="_blank">Lokava</a></p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
