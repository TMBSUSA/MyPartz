<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\User;
use common\models\LoginForm;
use backend\controllers\PartdetailController;
use backend\models\Partdetail;
use backend\models\Seller;
use backend\models\Vehiclemake;
use backend\models\Vehiclemodel;
use backend\models\Parttype;
use backend\models\Bannerads;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'change_password'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }
	
	public function actionChange_password()
    {
		$user = Yii::$app->user->identity;
		$loadedPost = $user->load(Yii::$app->request->post());
		
		if ($loadedPost && $user->validateCurrentPassword($user->currentPassword)) {
            $user->password = $user->newPassword;
			$user->save(false);
			Yii::$app->session->setFlash('success','You have successfully changed password');
			return $this->refresh();
        }
		return $this->render('change_password',[
			'user' => $user,
		]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
	public function countUser()
	{
		return Seller::find()->count();
	}
	public function countPart()
	{
		return Partdetail::find()->count();
	}
	public function countMake()
	{
		return Vehiclemake::find()->count();
	}
	public function countModel()
	{
		return Vehiclemodel::find()->count();
	}
	public function countPartType()
	{
		return Parttype::find()->count();
	}
	public function countBannerAds()
	{
		return Bannerads::find()->count();
	}
}
