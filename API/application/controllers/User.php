<?php
error_reporting(E_PARSE);
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Comman_model', 'comman');
	}
	
	//user register
	public function register()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['UserName'] = $this->input->post('username');
			$userexist = $this->comman->check_user_is_exist($data['UserName']); //check username is already exist or not
			
			if($userexist == 0){ // if user not exist then
				
				$data['Email'] 			= $this->input->post('email');
				$data['Mobile']			= $this->input->post('mobile');
				$data['Password'] 		= $this->input->post('password');
				$dataDev['DeviceType'] 	= $this->input->post('deviceType');  //iPhone, Android
				$dataDev['DeviceToken'] = $this->input->post('deviceToken');
				$dataDev['IntUdId'] 	= $this->input->post('intUdId');	
				
				//check required parameter exist or not
				if($this->comman->required_data_exist($data) && $this->comman->required_data_exist($dataDev)){ 
					//if user created successfully
					$userdata = $this->comman->add_user($data,$dataDev);
					if(count($userdata) > 0){
						$result['status']	      	= 'True';
						$result['responsecode'] 	= '1';
						$result['msg'] 		 	 	= 'User created successfully';	
						$result['AccessToken']		= $userdata[1];
						
						$response['UserId'] 	= $userdata[0];
						$response['UserName'] 	= $data['UserName'];
						$response['Mobile'] 	= $data['Mobile'];
						$response['Email'] 		= $data['Email'];
						
						$result['data'][]	= $response;
						
					}else{
						$result['status']	      	= 'False';
						$result['responsecode'] 	= '103';
						$result['msg'] 		 	 	= 'User not created properly';	
					}
				}else{
					$result['status']	      	= 'False';
					$result['responsecode'] 	= '102';
					$result['msg'] 		 	 	= 'Required Parameter Missing';	
				}
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= '101';
				$result['msg'] 		  		= 'User Name already exist';	
			}
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	public function login()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['UserName'] = $this->input->post('username');
			$data['Password'] = $this->input->post('password');
			$dataDev['DeviceType'] 	= $this->input->post('deviceType');  //iPhone, Android
			$dataDev['DeviceToken'] = $this->input->post('deviceToken');
			$dataDev['IntUdId'] 	= $this->input->post('intUdId');
			
			if($this->comman->required_data_exist($data)){ //check required parameter exist or not
				
				if($this->comman->login_user($data)){ // check user exist or not = username/password
					$userid = $this->comman->get_user_id($data);
					
					/* Commented by Deep Trivedi on 19th Aug 2016 */
					
					if($userid['LogInStatus'] != 1){ // when single login
					
						$updatedata['LogInStatus'] = '1';
						$updatedata['LastLogin']   = date('Y-m-d H:i:s');
						$updatedata['AccessToken'] = uniqid();	
						$this->comman->update_login_access($userid['UserId'], $updatedata);
						
					}else{ // when multiple login
						$updatedata['AccessToken'] = $userid['AccessToken'];
					}
					
					
					$dataDev['AccessToken'] 	= $updatedata['AccessToken']; /* Added by Deep Trivedi on 19th Aug 2016 */
					$this->comman->update_device($dataDev, $userid['UserId']); //update device information if login from new device
										
					$result['status']	      	= 'True';
					$result['responsecode'] 	= '1';
					$result['msg'] 		 	 	= 'Login Successful';
					$result['AccessToken']		= $updatedata['AccessToken'];
					
					$userdata['UserId'] 	= $userid['UserId'];
					$userdata['UserName'] 	= $userid['UserName'];
					$userdata['Mobile'] 	= $userid['Mobile'];
					$userdata['Email'] 		= $userid['Email'];
					
					$result['data'][]	= $userdata;
				}else{
					if($this->comman->user_status($data)){
						$result['status']	      	= 'False';
						$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
						$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;
					}else{
						$result['status']	      	= 'False';
						$result['responsecode'] 	= '404';
						$result['msg'] 		 	 	= 'Username and Password doesn\'t Match';		
					}					
				}	
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= '102';
				$result['msg'] 		 	 	= 'Required Parameter Missing';	
			}
			
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	public function logout()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['UserId']      = $this->input->post('UserId');
			
			/* Added by Deep Trivedi on 19th Aug 2016 */ 
			$data['DeviceType']  = $this->input->post('deviceType');  //iPhone, Android
			$data['DeviceToken'] = $this->input->post('deviceToken');
			$data['IntUdId'] 	 = $this->input->post('intUdId');
			
			//check required parameter exist or not
			if($this->comman->required_data_exist($data)){ 
				
				/* Commented by Deep Trivedi on 19th Aug 2016 */ 
				//$data['LogInStatus'] = '1';
				
				/* $data is replaced by '' from this function argument by Deep Trivedi on 19th Aug 2016 */
				$response = $this->comman->select_comman('seller','UserId',''); //$data
				
				if(count($response) > 0){ //check for access token
					
					$update['AccessToken'] = '';
					$update['LogInStatus'] = '0';
					
					/* Commented by Deep Trivedi on 19th Aug 2016 */ 
					/*$this->comman->comman_update('seller',$update,'UserId = '.$data['UserId']); */
					
					/* Added by Deep Trivedi on 19th Aug 2016 */ 
					$this->comman->update_device_logout($data); 
					
					/*
					$check_device = check_device_logout($data);
					if( $check_device == 0 )
					    $this->comman->comman_update('seller',$update,'UserId = '.$data['UserId']);
					*/
					
					$result['status']	    	= 'True';
					$result['responsecode'] 	= '1';
					$result['msg'] 		 		= 'Logout Successful';
				}else{
					$result['status']	      	= 'False';
					$result['responsecode'] 	= '1000';
					$result['msg'] 		 	 	= 'AccessToken Expired, Please Login';		
				}
				
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= '102';
				$result['msg'] 		 	 	= 'Required Parameter Missing';	
			}
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	public function forgot_password()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['Email'] = $this->input->post('email');
			
			if($this->comman->required_data_exist($data)){ //check required parameter exist or not
				
				if($this->comman->reset_password($data)){ // if email exist
					
					//Update reset key
					$ResetKey = uniqid();
					$this->comman->update_reset_key(array( 'ResetToken' => $ResetKey ),$data['Email']);
					
					//send email
					$this->load->library('email');
					
					$from = ADMIN_EMAIL;
					$to = $data['Email'];
					$subject = 'My Partz - Password Reset';
					$body = $this->comman->html_body($ResetKey);
					
					if($this->comman->send_email($subject,$to,$from,$body)){ //if email sent successfully
						$result['status']	      	= 'True';
						$result['responsecode'] 	= '1';
						$result['msg'] 		 	 	= 'Reset Password Instructions Sent to Your Registered Email';		
					}else{
						$result['status']	      	= 'False';
						$result['responsecode'] 	= '500';
						$result['msg'] 		 	 	= 'Internal Server Error';
					}
				}else{
					$result['status']	      	= 'False';
					$result['responsecode'] 	= '103';
					$result['msg'] 		 	 	= 'Email doesn\'t exist';		
				}
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= '102';
				$result['msg'] 		 	 	= 'Required Parameter Missing';	
			}
			
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	public function password($token)
	{
		$this->load->helper('url');
		$isexist = $this->comman->select_comman('seller','UserId', array( 'ResetToken' => $token ));
		
		if(count($isexist) > 0){
			$data['ResetToken'] = $token;
			$data['status'] = '1';
		}else{
			$data['status'] = '0';
		}
		$this->load->view('password',$data);
	}
	public function change_password()
	{
		$arr['ResetToken'] = $this->input->post('ResetToken');
		$arr['Mobile'] = $this->input->post('Mobile');
		
		$isexist = $this->comman->select_comman('seller','UserId', $arr);
		
		if(count($isexist) > 0){
			$udata = array(
						'ResetToken' => '',
						'Password' => md5($this->input->post('Password'))
					);
			$this->comman->change_password($udata,$isexist[0]['UserId']);
			$data['msg'] = 'Your Password Changed Successfully.';
			$data['status'] = '1';
			$data['error']  = '1';
			$this->load->view('password',$data);
		}else{
			$data['status'] = '1';
			$data['error']  = '2';
			$data['ResetToken'] = $arr['ResetToken'];
			$this->load->view('password',$data);
			//redirect('password');
		}
	}
	public function change_password_mobile()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['AccessToken'] = $this->input->post('AccessToken');
			$data['UserId']   	 = $this->input->post('UserId');
			$update['Password']  = $this->input->post('Password');
			
			//check required parameter exist or not
			if($this->comman->required_data_exist($data) && $this->comman->required_data_exist($update)){ 
				
				$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $data['UserId'], 'Status' => 'Active'));  //check for user status active or not
				
				if($isactive > 0){
					$data['LogInStatus'] = '1';
					$response = $this->comman->select_comman('seller','UserId',$data);  //check for access token
					if(count($response) > 0){ 											//if exist
						$update['Password'] = md5($update['Password']);
						$this->comman->comman_update('seller', $update,'UserId = '.$data['UserId']);
						$result['status']	      	= 'True';
						$result['responsecode'] 	= '1';
						$result['msg'] 		 	 	= 'Password Updated Successfully';						
					}else{
						$result['status']	      	= 'False';
						$result['responsecode'] 	= '1000';
						$result['msg'] 		 	 	= 'AccessToken Expired, Please Login';		
					}
				}else{
					$result['status']	      	= 'False';
					$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
					$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;
				}	
				
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= '102';
				$result['msg'] 		 	 	= 'Required Parameter Missing';	
			}
			
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	
	public function update_profile()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['AccessToken'] = $this->input->post('AccessToken');
			$data['UserId']   = $this->input->post('UserId');
			$update['UserName'] = $this->input->post('UserName');
			$update['Email']    = $this->input->post('Email');
			$update['Mobile']   = $this->input->post('Mobile');
			
			//check required parameter exist or not
			if($this->comman->required_data_exist($data) && $this->comman->required_data_exist($data)){ 
				
				$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $data['UserId'], 'Status' => 'Active'));  //check for user status active or not
				
				if($isactive > 0){
					$data['LogInStatus'] = '1';
					
					$response = $this->comman->select_comman('seller','UserId',$data);  //check for access token
					if(count($response) > 0){ 											//if exist
						
						$userexist = $this->comman->check_user_is_exist($update['UserName']); //check username is already exist or not
						if($userexist == 0){
							
							$this->comman->comman_update('seller', $update,'UserId = '.$data['UserId']);
							$result['status']	      	= 'True';
							$result['responsecode'] 	= '1';
							$result['msg'] 		 	 	= 'User Profile Updated Successfully';	
							$result['data'][]			= $update;
						}else{
							$result['status']	      	= 'False';
							$result['responsecode'] 	= '101';
							$result['msg'] 		  		= 'User Name already exist';
						}
					}else{
						$result['status']	      	= 'False';
						$result['responsecode'] 	= '1000';
						$result['msg'] 		 	 	= 'AccessToken Expired, Please Login';		
					}
				}else{
					$result['status']	      	= 'False';
					$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
					$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;
				}
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= '102';
				$result['msg'] 		 	 	= 'Required Parameter Missing';	
			}
			
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	
	public function renew_cron()
	{
		$UserIDs = $this->comman->select_comman('seller','UserId,Email',"DATEDIFF(NOW(),UpdatedOn) > 30 and 'isActivated' = '0' ");
		foreach($UserIDs as $userid){
			$token = uniqid();
			$data = array(
						'Renew_token' => $token,
						'Status' => 'Inactive'
					);
			$this->comman->inactive_user($data,$userid['UserId']);
			
			$this->load->library('email');
			
			$from = ADMIN_EMAIL;
			$to = $userid['Email'];
			$subject = 'Renew Your Account';
			$content = "<a href='".base_url()."renew/".$token."'>Click here</a> to renew your account"; 
			$body = $this->comman->html_body($content);
			
			$this->comman->send_email($subject,$to,$from,$body);
		}
	}
	public function renew($token)
	{
		$isexist = $this->comman->select_comman('seller','UserId', array( 'Renew_token' => $token ));
		
		if(count($isexist) > 0){
			$udata = array(
						'Renew_token' => '',
						'UpdatedOn' => date('Y-m-d H:i:s'),
						'Status' => 'Active'
					);
			$this->comman->active_user($udata,$isexist[0]['UserId']);
			$data['msg'] = 'Your Account Successfully Re-Activated.';
			$data['status'] = '1';
		}else{
			$data['status'] = '0';
		}
		$this->load->view('renew',$data);
	}
	public function inactive_partz(){
		$this->comman->comman_update('part_detail',array('Status'=>'Inactive'),"DATEDIFF(NOW(),UpdatedOn) > 30");
	}
	public function active_user(){
		
		$UserIDs = $this->comman->select_comman('part_detail','UserId,PartId,PartName,Status',"DATEDIFF(NOW(),UpdatedOn) > 28 and NotificationWarning = 0");
		//$this->comman->comman_update('part_detail',array('NotificationWarning'=>'1'),"DATEDIFF(NOW(),UpdatedOn) > 28 and NotificationWarning = 0");
		
		echo "<pre>";
		print_r($UserIDs); 
		
		foreach($UserIDs as $userid){
			$deviceids = $this->comman->select_comman('devices','DeviceToken,DeviceType',"`UserId` = ".$userid['UserId']);
			$android_array = array();
			foreach($deviceids as $deviceid){
				if($deviceid['DeviceType'] == 'Android'){
					$android_array[] = $deviceid['DeviceToken'];
				}else{
					$this->iOS_push($deviceid['DeviceToken'], $userid['PartId'], $userid['PartName'], $userid['Status'], '1');
				}
			}
			if(!empty($android_array)){
				$this->android_push($android_array, $userid['PartId'], $userid['PartName'], $userid['Status'], '1'); 
				unset($android_array);
				//sleep(1);
			}
		}
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
			  
		print_r($payload);
		$payload = json_encode($payload);
		//$apnsHost = 'gateway.sandbox.push.apple.com'; 
		$apnsHost = 'gateway.push.apple.com';
		//$apnsCert = 'apns-prod.pem';		
		$apnsCert = "uploads/apns-prod.pem";		 
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
		echo $msg;
	}
	function android_push($deviceids, $PartId, $PartName, $Status, $NotificationWarning){
		$android_deviceToken = $deviceids;
		$notification_text = "Your listed part '$PartName' for sale is going to expire soon. Do you want to renew your listing ?";	
		$message = array("notification" => $notification_text, 'PartId'=>$PartId, 'PartName'=>$PartName, 'Status'=>$Status, 'NotificationWarning'=>$NotificationWarning);
		
		print_r($message);
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
	public function iOS_test($deviceToken='0360a36afe788ec1398b9d71582f82c80c083c10f6144073fb283b7f73a9fcd7', $PartId='1', $PartName='test', $Status="Active", $NotificationWarning="1"){
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
			  
		print_r($payload);
		$payload = json_encode($payload);
		//$apnsHost = 'gateway.sandbox.push.apple.com'; 
		$apnsHost = 'gateway.push.apple.com';
		//$apnsCert = 'apns-prod.pem';		
		$apnsCert = "uploads/apns-prod.pem";		 
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
	}
	function android_test($deviceids = 'fp_dlPolpws:APA91bG6D-tlTw8oBw3Enntsht7iwQ1jIt8mH3JgxghM2iYASPKwjFprWpuuDrjUXo5ecSPdmRDZ9BO73HPqsjnI9JDhN55l12XaftCan4bLFJkreOImP7LmpdQR1MwhoARDsARyvxoU', $PartId = '1', $PartName = 'test', $Status = 'Active', $NotificationWarning = '1'){
		$android_deviceToken = array($deviceids);
		$notification_text = "Your listed part '$PartName' for sale is going to expire soon. Do you want to renew your listing ?";	
		$message = array("notification" => $notification_text, 'PartId'=>$PartId, 'PartName'=>$PartName, 'Status'=>$Status, 'NotificationWarning'=>$NotificationWarning);
		echo "<pre>";
		print_r($message);
		$url = 'https://android.googleapis.com/gcm/send';

		$fields = array(
			'registration_ids' => $android_deviceToken,
			'data' => $message,
		);
		
		$headers = array(
			'Authorization: key=AIzaSyAsvOaZS4oYJtMqwmxsUnKTI8qdYOVpqh8',
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
		print_r($ch);
		print_r($result);
		if ($result === FALSE) {
			die('Curl failed: ' . curl_error($ch));
		}
		curl_close($ch);
	}
	
	function sendPushNotification() {
	$registration_ids[] = "APA91bEscp-E_1E06LKeNQA_JV2fOG_DDyBg8t1bbJblJ6C6-1p0o-BeoE8e3ijwSZ-ZPN4apDQsrcJmez72dTDvl9_EliS5txRNKHV5n_DSI-84TK98WN_Mxge90FkkNmFzukU0jIs5"; 
    $url = 'https://fcm.googleapis.com/fcm/send';
    $fields = array(
        'registration_ids' => $registration_ids,
        'data' => 'test'
    );

    $headers = array(
        'Authorization:key=AIzaSyDIhMRoFDPDRiICKKDC9ZWE76NuTmrmhNk',
        'Content-Type: application/json'
    );
    //echo json_encode($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

    $result = curl_exec($ch);
    if($result === false)
        die('Curl failed ' . curl_error());

    curl_close($ch);
    echo "<pre>";
	print_r($result);
	print_r(curl_error());
	
	exit;
	return $result;

	}
}
