<?php
error_reporting(E_PARSE);
defined('BASEPATH') OR exit('No direct script access allowed');
class Garage extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Comman_model', 'comman');
	}
	
	public function index()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['UserId'] = $this->input->post('UserId');
			$data['AccessToken'] = $this->input->post('AccessToken');
			
			if($this->comman->required_data_exist($data)){ //check required parameter exist or not
				
				$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $data['UserId'], 'Status' => 'Active'));  //check for user status active or not
				
				if($isactive > 0){
					$data['LogInStatus'] = '1';
					
					$banners = $this->comman->select_comman('banner_ads','*',''); // select banner ads
					
					$banner_ads = array();		
					foreach($banners as $banner){
						if($banner['ImageURL'] == 'noimage.jpg'){
							//$banner['ImageURL'] = ''; 		
						}else{
							$banner['ImageURL'] = BACKEND_IMG.$banner['ImageURL']; 	
							$banner_ads[] = $banner;
						}
					}
					
					$response = $this->comman->select_comman('seller','UserId',$data);
					if(count($response)){ //check for access token
						
						$allpartid = $this->comman->garage_parts('mygarage','PartId',array('UserId' => $data['UserId']));
						$partids = array();
						if(count($allpartid) > 0){
							foreach($allpartid as $part){
								$partids[] = $part['PartId'];	
							}
							
							$data['lat'] = $this->input->post('Lat');
							$data['lng'] = $this->input->post('Lng');
							
							if($data['lat'] != '' && $data['lng'] != ''){
								$respo = $this->comman->my_garage($partids,$data['lat'],$data['lng']); 
							}else{
								$results = $this->comman->my_garage($partids);
								foreach ($results as $val){
									$val['Distance'] = "";
									$respo[] = $val;	
								}
							}
							
							$result['status']	    	= 'True';
							$result['responsecode'] 	= '1';
							$result['msg'] 		 		= 'Record Found Successfully';
							$result['data'] 			= $respo;
							$result['banner'] 			= $banner_ads;
						}else{
							$result['status']	      	= STATUS_FALSE;
							$result['responsecode'] 	= NO_RECORD_CODE;
							$result['msg'] 		 	 	= 'No partz are currently in your garage';		
							$result['banner'] 			= $banner_ads;	
						}
					}else{
						$result['status']	      	= STATUS_FALSE;
						$result['responsecode'] 	= ACCESS_TOKEN_EXPIRED_CODE;
						$result['msg'] 		 	 	= ACCESS_TOKEN_EXPIRED;		
					}
				}else{
					$result['status']	      	= STATUS_FALSE;
					$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
					$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;		
				}
			}else{
				$result['status']	      	= STATUS_FALSE;
				$result['responsecode'] 	= REQUIRED_PARAMETER_CODE;
				$result['msg'] 		 	 	= REQUIRED_PARAMETER;	
			}
		}else{
			$result['status']	      	= STATUS_FALSE;
			$result['responsecode'] 	= WRONG_API_KEY_CODE;
			$result['msg'] 		  	 	= WRONG_API_KEY;
		}
		echo json_encode($result);
	}
	public function add_remove_garage()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$partdata['PartId']    = $this->input->post('PartId');
			$partdata['AddRemove'] = $this->input->post('AddRemove');
			$data['UserId']        = $this->input->post('UserId');
			$data['AccessToken']   = $this->input->post('AccessToken');
			
			//check required parameter exist or not
			if($this->comman->required_data_exist($data) && $this->comman->required_data_exist($partdata)){ 
				
				$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $data['UserId'], 'Status' => 'Active'));  //check for user status active or not
				
				if($isactive > 0){
				
					$data['LogInStatus'] = '1';
					$response = $this->comman->select_comman('seller','UserId',$data);
					
					if(count($response)){ //check for access token
						
						$isexist = $this->comman->select_comman('mygarage','GarageId', array('PartId' => $partdata['PartId'],
																							 'UserId' => $data['UserId'] ));
						if(count($isexist) == 0){ // if not exist already
							
							if($partdata['AddRemove'] == 0){
								$this->comman->comman_insert('mygarage',array('PartId' => $partdata['PartId'],
																			  'UserId' => $data['UserId'], 
																			  'CreatedOn' => date('Y:m:d H:i:s') ));
								$result['status']	    	= 'True';
								$result['responsecode'] 	= '1';
								$result['msg'] 		 		= 'Part Added To Your Garage';	
							}
							
						}else{
							//if exist then remove
							if($partdata['AddRemove'] == 1){
								$this->comman->comman_delete('mygarage',array('PartId' => $partdata['PartId'],
																			  'UserId' => $data['UserId'] ));
								$result['status']	    	= 'True';
								$result['responsecode'] 	= '1';
								$result['msg'] 		 		= 'Part Removed From Your Garage';
							}else{ // if not requested for remove
								$result['status']	      	= 'False';
								$result['responsecode'] 	= '11';
								$result['msg'] 		 	 	= 'This Part is already in your Garage';	
							}
						}
					}else{
						$result['status']	      	= STATUS_FALSE;
						$result['responsecode'] 	= ACCESS_TOKEN_EXPIRED_CODE;
						$result['msg'] 		 	 	= ACCESS_TOKEN_EXPIRED;	
					}
				}else{
					$result['status']	      	= STATUS_FALSE;
					$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
					$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;
				}
			}else{
				$result['status']	      	= STATUS_FALSE;
				$result['responsecode'] 	= REQUIRED_PARAMETER_CODE;
				$result['msg'] 		 	 	= REQUIRED_PARAMETER;	
			}
		}else{
			$result['status']	      	= STATUS_FALSE;
			$result['responsecode'] 	= WRONG_API_KEY_CODE;
			$result['msg'] 		  	 	= WRONG_API_KEY;
		}
		echo json_encode($result);
	}
	public function users_part()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['UserId']      = $this->input->post('UserId');
			$data['AccessToken'] = $this->input->post('AccessToken');
			
			//check required parameter exist or not
			if($this->comman->required_data_exist($data)){ 
				
				$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $data['UserId'], 'Status' => 'Active'));  //check for user status active or not
				
				if($isactive > 0){
				
					$data['LogInStatus'] = '1';
					$response = $this->comman->select_comman('seller','UserId',$data);
					
					if(count($response) > 0){ //check for access token
						
						$total = $this->comman->user_added_part( $data['UserId'], $this->input->post('record_no'), '1' );
						$isexist = $this->comman->user_added_part( $data['UserId'], $this->input->post('record_no'), '0' );
						if(count($isexist) > 0){ // if exist 
							
							$result['status']	    	= 'True';
							$result['responsecode'] 	= '1';
							$result['msg'] 		 		= 'Record found successfully';
							$result['total_record']		= $total;
							$result['data']				= $isexist;
						}else{
							$result['status']	      	= STATUS_FALSE;
							$result['responsecode'] 	= NO_RECORD_CODE;
							$result['msg'] 		 	 	= "You don't have any parts for sale.";	
							$result['total_record']		= $total;
						}
					}else{
						$result['status']	      	= STATUS_FALSE;
						$result['responsecode'] 	= ACCESS_TOKEN_EXPIRED_CODE;
						$result['msg'] 		 	 	= ACCESS_TOKEN_EXPIRED;	
					}
				}else{
					$result['status']	      	= STATUS_FALSE;
					$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
					$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;	
				}
			}else{
				$result['status']	      	= STATUS_FALSE;
				$result['responsecode'] 	= REQUIRED_PARAMETER_CODE;
				$result['msg'] 		 	 	= REQUIRED_PARAMETER;	
			}
		}else{
			$result['status']	      	= STATUS_FALSE;
			$result['responsecode'] 	= WRONG_API_KEY_CODE;
			$result['msg'] 		  	 	= WRONG_API_KEY;	
		}
		echo json_encode($result);
	}
	public function active_user_part()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$pdata['PartId']      = $this->input->post('PartId');
			$data['UserId']      = $this->input->post('UserId');
			$data['AccessToken'] = $this->input->post('AccessToken');
			
			//check required parameter exist or not
			if($this->comman->required_data_exist($data) && $this->comman->required_data_exist($pdata)){ 
				
				$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $data['UserId'], 'Status' => 'Active'));  //check for user status active or not
				
				if($isactive > 0){
					$data['LogInStatus'] = '1';
					$response = $this->comman->select_comman('seller','UserId',$data);
					
					if(count($response) > 0){ //check for access token
						
						$this->comman->comman_update('part_detail',array('Status'=>'Active', 'NotificationWarning'=>'0', 'UpdatedOn'=>date('Y-m-d H:i:s')),array('PartId'=>$pdata['PartId']));						
						$result['status']	    	= 'True';
						$result['responsecode'] 	= '1';
						$result['msg'] 		 		= 'Your part listing has been renewed for another 30 days.';
					}else{
						$result['status']	      	= STATUS_FALSE;
						$result['responsecode'] 	= ACCESS_TOKEN_EXPIRED_CODE;
						$result['msg'] 		 	 	= ACCESS_TOKEN_EXPIRED;		
					}
				}else{
					$result['status']	      	= STATUS_FALSE;
					$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
					$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;	
				}
			}else{
				$result['status']	      	= STATUS_FALSE;
				$result['responsecode'] 	= REQUIRED_PARAMETER_CODE;
				$result['msg'] 		 	 	= REQUIRED_PARAMETER;	
			}
		}else{
			$result['status']	      	= STATUS_FALSE;
			$result['responsecode'] 	= WRONG_API_KEY_CODE;
			$result['msg'] 		  	 	= WRONG_API_KEY;	
		}
		echo json_encode($result);
	}
}

