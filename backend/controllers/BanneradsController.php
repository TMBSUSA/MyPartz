<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\Bannerads;
use backend\models\BanneradsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

/**
 * BanneradsController implements the CRUD actions for Bannerads model.
 */
class BanneradsController extends Controller
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
                        'actions' => ['index', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Bannerads models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BanneradsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Bannerads model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Bannerads model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Bannerads();

        if ($model->load(Yii::$app->request->post())) {
            
			
				$model->ImageURL = UploadedFile::getInstance($model, 'ImageURL');
				$imgname = $model->upload(); 
				$model->ImageURL = $imgname;
                $model->title = $_POST['Bannerads']['title'];
			
			$model->save();
			
			return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Bannerads model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {


            $old_image = $model->ImageURL;
            $model->title = $_POST['Bannerads']['title'];
			
			$model->ImageURL = UploadedFile::getInstance($model, 'ImageURL');
			if (isset($model->ImageURL)){
				$imgname = $model->upload(); 
				$model->ImageURL = $imgname;
				if($old_image != 'noimage.jpg'){
					unlink("uploads/".$old_image);
				}
			}else{
				$model->ImageURL = $old_image;
			}
			$model->save();
			
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Bannerads model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
		unlink("uploads/".$model->ImageURL);
		Yii::$app->db->createCommand("UPDATE banner_ads SET ImageURL='noimage.jpg',ExternalURL='' WHERE BannerID=$id")->execute();
		return $this->redirect(['index']);
    }

    /**
     * Finds the Bannerads model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Bannerads the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Bannerads::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
