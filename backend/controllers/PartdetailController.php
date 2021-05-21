<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\models\Partphoto;
use backend\models\Partdetail;
use backend\models\Vehiclemodel;
use backend\models\PartdetailSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PartdetailController implements the CRUD actions for Partdetail model.
 */
class PartdetailController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'notice'],
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
     * Lists all Partdetail models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PartdetailSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Partdetail model.
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
     * Creates a new Partdetail model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Partdetail();
		

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            
			$total = count($_FILES['imageFiles']['name']);
			
			for($i=0; $i<$total; $i++) {
				$newpath = uniqid() .".". pathinfo($_FILES['imageFiles']['name'][$i], PATHINFO_EXTENSION);
				if(move_uploaded_file($_FILES['imageFiles']['tmp_name'][$i], "uploads/".$newpath)) {
					$url = "http://mypartz.com.au".Yii::$app->homeUrl . "uploads/" . $newpath;
					$data[] = [$model->PartId, $url, $url];
				}
			}
			Yii::$app->db->createCommand()
					   	 ->batchInsert('part_photo',['PartId','PhotoUrl','ThumbUrl'],$data)->execute();
			return $this->redirect(['view', 'id' => $model->PartId]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Partdetail model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
			
			$total = count($_FILES['imageFiles']['name']);
			
			if($total > 0){
				for($i=0; $i<$total; $i++) {
					$newpath = uniqid() .".". pathinfo($_FILES['imageFiles']['name'][$i], PATHINFO_EXTENSION);
					if(move_uploaded_file($_FILES['imageFiles']['tmp_name'][$i], "uploads/".$newpath)) {
						$url = "http://mypartz.com.au".Yii::$app->homeUrl . "uploads/" . $newpath;
						$data[] = [$model->PartId, $url, $url];
					}
				}
				if(!empty($data)){
					Yii::$app->db->createCommand()
								 ->batchInsert('part_photo',['PartId','PhotoUrl','ThumbUrl'],$data)->execute();
				}
			}
			
            return $this->redirect(['view', 'id' => $model->PartId]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
	
    /**
     * Deletes an existing Partdetail model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Partdetail model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Partdetail the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Partdetail::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	public function actionSearchmodel($id){
		$count = Vehiclemodel::find()->where(['MakeId' => $id])->count();
		if($count > 0){
			$results = Vehiclemodel::find()->where(['MakeId' => $id])->all();
			foreach($results as $result){
				echo "<option value='".$result->ModelId."'>".$result->ModelName."</option>";
			}
		}
		else{
			echo "<option></option>";
		}
	}
	public function GetImages($id)
	{
		$count = Partphoto::find()->where(['PartId' => $id])->count();
		if($count > 0){
			$results = Partphoto::find()->where(['PartId' => $id])->all();
			foreach($results as $result){
				$data[] = $result->PhotoUrl;
			}
			return $data;
		}
		else{
			return '';
		}
	}
	public function GetImageHtml($id)
	{
		$data = '';
		$count = Partphoto::find()->where(['PartId' => $id])->count();
		if($count > 0){
			$results = Partphoto::find()->where(['PartId' => $id])->all();
			foreach($results as $result){
				$data .= '<img src="'.$result->PhotoUrl.'">';
			}
			return $data;
		}
		else{
			return '';
		}
	}
	public function actionNotice($id)
    {
		$date = date('Y-m-d H:i:s', strtotime(date('Y-m-d H:i:s') . ' -28 days'));
		Yii::$app->db->createCommand("UPDATE `part_detail` SET `UpdatedOn`='$date',`NotificationWarning`='1' WHERE PartId='$id'")->execute();
		$part = Yii::$app->db->createCommand("SELECT `PartName`,`Status` FROM `part_detail` WHERE PartId='$id'")->queryOne();

		$devices = Yii::$app->db->createCommand("SELECT `DeviceToken`,`DeviceType` 
												 FROM `devices` as d 
												 JOIN `part_detail` as pd ON d.UserId = pd.UserId 
												 WHERE pd.PartId = '$id'")->queryAll();
				
		foreach($devices as $device){
			if($device['DeviceType'] == 'Android'){
				$android_array[] = $device['DeviceToken'];
			}else{
				$this->iOS_push($device['DeviceToken'], $id, $part['PartName'], $part['Status'], '1');
			}
		}
		if(!empty($android_array)){
			$this->android_push($android_array, $id, $part['PartName'], $part['Status'], '1'); 
		}				
		return $this->redirect(['view', 'id' => $id]);
	}
	public function android_push($deviceids, $PartId, $PartName, $Status, $NotificationWarning){
		$android_deviceToken = $deviceids;
		$notification_text = "Your listed part '$PartName' for sale is going to expire soon. Do you want to renew your listing ?";	
		$message = array("notification" => $notification_text, 'PartId'=>$PartId);
		
		$url = 'https://android.googleapis.com/gcm/send';

		$fields = array(
			'registration_ids' => $android_deviceToken,
			'data' => $message,
		);
		
		$headers = array(
			'Authorization: key=AIzaSyA6oV4ZQmJTTFHHxt_8vXUkQd8etBgWtHU',
			'Content-Type: application/json' 
		);
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
		$result = curl_exec($ch);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);
	}
	public function iOS_push($deviceToken, $PartId, $PartName, $Status, $NotificationWarning){
		$device_token = $deviceToken;
		$notification_text = "Your listed part '$PartName' for sale is going to expire soon. Do you want to renew your listing ?";	
		$msg_text = array('PartId'=>$PartId);					
		$payload = array();						
		$payload['aps'] = array(
			 'alert' => $notification_text,
			 'category' => "RENEW_IDENTIFIER",
			 'badge' =>  '1', 
			 'sound' => 'default',
			 'data' => $msg_text     
			  );
			  
		//print_r($payload);
		$payload = json_encode($payload);
		//$apnsHost = 'gateway.sandbox.push.apple.com'; 
		$apnsHost = 'gateway.push.apple.com';
		//$apnsCert = 'apns-prod.pem';		
		$apnsCert = $_SERVER['DOCUMENT_ROOT']."/api/uploads/apns-prod.pem";		 
		$apnsPort = 2195;
		$streamContext = @stream_context_create();
		@stream_context_set_option($streamContext, 'ssl', 'local_cert', $apnsCert);
		$apns = @stream_socket_client('ssl://' . $apnsHost . ':' . $apnsPort, $error, $errorString, 60, STREAM_CLIENT_CONNECT, $streamContext); 
		// 60 is the timeout
		$apnsMessage = chr(0) . chr(0) . chr(32) . pack('H*', @str_replace(' ', '', $device_token)) . chr(0) . chr(strlen($payload)) . $payload;
	
		$result = @fwrite($apns, $apnsMessage);
		// socket_close($apns);
		@fclose($apns);
		
		// Push Notification Code End Here
		if(!$result)
		{
			$msg = 'Message not delivered'. PHP_EOL;
			//return $if;
		}
		else
		{
			$msg = 'MSG Delivered';
			//return $else;
		}
		//echo $msg;
	}
}
