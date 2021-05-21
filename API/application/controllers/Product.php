<?php
error_reporting(E_PARSE);
defined('BASEPATH') OR exit('No direct script access allowed');
class Product extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Comman_model', 'comman');
	}
	
	public function part_list() // Get all part list
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['offset'] = $this->input->post('record_no');
			
			if($this->comman->required_data_exist($data)){ //check required parameter exist or not
				$UserId = $this->input->post('UserId');
				$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $UserId, 'Status' => 'Active'));  //check for user status active or not
				
				if($isactive > 0 or $UserId == ''){
					$data['lat']    = $this->input->post('lat');
					$data['long']   = $this->input->post('long');
					$data['search'] = $this->input->post('search');
					
					if($data['search'] != ''){
						$this->comman->add_search_key($data['search']);	
					}
					
					if($data['lat'] != '' && $data['long'] != ''){
						$response = $this->comman->all_part($data);
					}else{
						$results = $this->comman->all_part($data);
						foreach ($results as $val){
							$val['Distance'] = "";
							$response[] = $val;	
						}
					}
					if(count($response) > 0){
						$result['status']	    	= 'True';
						$result['responsecode'] 	= '1';
						$result['msg'] 		 		= 'Record Found Successfully';
						$result['total_record'] 	= $this->comman->all_part($data, '1');
						$result['data'] 			= $response;
					}else{
						$result['status']	      	= 'False';
						$result['responsecode'] 	= '404';
						$result['msg'] 		 	 	= 'No Record Found';	
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
	public function serach_part() // Global search
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['offset'] = $this->input->post('record_no');
			
			if($this->comman->required_data_exist($data)){ //check required parameter exist or not
				
				$UserId = $this->input->post('UserId');
				$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $UserId, 'Status' => 'Active'));  //check for user status active or not
				
				if($isactive > 0 or $UserId == ''){
					$data['lat']  = $this->input->post('Lat');
					$data['long'] = $this->input->post('Lng');
					
					$data['offset']   	 = $this->input->post('record_no');
					$data['MakeId']   	 = $this->input->post('MakeId');
					$data['ModelId']  	 = $this->input->post('ModelId');
					$data['PartMfgYear'] = $this->input->post('PartMfgYear');
					$data['PartTypeId']  = $this->input->post('PartTypeId');
					$data['search']   	 = $this->input->post('search');
					
					if($data['search'] != ''){
						$this->comman->add_search_key($data['search']);	
					}
					
					//$response = $this->comman->search_part($data);
					//$result['query'] 			= $this->db->last_query();
					
					if($data['lat'] != '' && $data['long'] != ''){
						$response = $this->comman->search_part($data);
					}else{
						$star = $this->comman->search_part($data);
						foreach ($star as $val){
							$val['Distance'] = "";
							$response[] = $val;	
						}
					}
					
					if(count($response) > 0){
						$result['status']	    	= 'True';
						$result['responsecode'] 	= '1';
						$result['msg'] 		 		= 'Record Found Successfully';
						$result['total_record'] 	= $this->comman->search_part($data, '1');
						$result['data'] 			= $response;
					}else{
						$result['status']	      	= 'False';
						$result['responsecode'] 	= '404';
						$result['msg'] 		 	 	= 'No matching partz found';	
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
	public function part_detail() //get single part's details
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['part_id'] = $this->input->post('part_id');
			
			if($this->comman->required_data_exist($data)){ //check required parameter exist or not
				
				$UserId = $this->input->post('UserId');
				$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $UserId, 'Status' => 'Active'));  //check for user status active or not
				
				if($isactive > 0 or $UserId == ''){
					$data['lat']     = $this->input->post('lat');
					$data['long']    = $this->input->post('long');
					$data['UserId'] = $this->input->post('UserId');
					
					$response = $this->comman->part_detail($data);
					$photos = $this->comman->part_photos($data['part_id']); //Get All photos of part
					
					if($data['UserId'] != ''){ // if user is logIn then check part is Added in User garage or not
						$isadded = $this->comman->check_in_mygarage($data['part_id'], $data['UserId']);
					}else{
						$isadded = 0;	
					}
					
					if(count($response) > 0){
						$result['status']	    	= 'True';
						$result['responsecode'] 	= '1';
						$result['msg'] 		 		= 'Record Found Successfully';
						$result['data'][0]			  = $response[0];
						$result['data'][0]['isAdded'] = $isadded;
						if($data['lat'] == '' && $data['long'] == ''){
							$result['data'][0]['Distance'] = "";
						}
						if(count($photos) > 0){
							$result['data'][0]['photos']  = $photos;	
						}else{
							$result['data'][0]['photos'][]['PhotoUrl']  = base_url()."api/uploads/dummy.png";
						}
						
					}else{
						$result['status']	      	= 'False';
						$result['responsecode'] 	= '404';
						$result['msg'] 		 	 	= 'No Record Found';	
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
	public function delete_part()
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$pdata['PartId']      = $this->input->post('PartId');
			$data['UserId']      = $this->input->post('UserId');
			$data['AccessToken'] = $this->input->post('AccessToken');
			
			//check required parameter exist or not
			if($this->comman->required_data_exist($data) && $this->comman->required_data_exist($pdata)){ 
				
				$UserId = $this->input->post('UserId');
				$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $UserId, 'Status' => 'Active'));  //check for user status active or not
				
				if($isactive > 0 or $UserId == ''){
				
					$data['LogInStatus'] = '1';
					$response = $this->comman->select_comman('seller','UserId',$data);
					
					if(count($response) > 0){ //check for access token
						
						/*$images = $this->comman->select_comman('part_photo','PhotoUrl,ThumbUrl',array('PartId'=>$pdata['PartId'])); //select part images for unlink
						
						foreach($images as $img){ // remove images from server
							unlink(".uploads/part/".end(explode("/", $img['PhotoUrl'])));
							unlink(".uploads/part/".end(explode("/", $img['ThumbUrl'])));
						}*/
						
						$this->comman->comman_delete('part_detail',array('PartId'=>$pdata['PartId'])); // delete part from database
												
						$result['status']	    	= 'True';
						$result['responsecode'] 	= '1';
						$result['msg'] 		 		= 'Your part removed successfully.';
					}else{
						$result['status']	      	= 'False';
						$result['responsecode'] 	= '1000';
						$result['msg'] 		 	 	= 'Oops, it seems your connection has been expired ! Please login again.';		
					}
				}else{
					$result['status']	      	= 'False';
					$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
					$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;
				}
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= '102';
				$result['msg'] 		 	 	= 'Sorry, it seems required parameter is missing !';	
			}
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Please contact your technical support team, it looks like you are facing security header error !';	
		}
		echo json_encode($result);
	}
	public function get_all_make() // get all make like "Audi,BMW"
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$UserId = $this->input->post('UserId');
			$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $UserId, 'Status' => 'Active'));  //check for user status active or not
			
			if($isactive > 0 or $UserId == ''){
				$response = $this->comman->select_comman('vehicle_make','MakeId,MakeName','');
				if(count($response) > 0){
					$result['status']	    	= 'True';
					$result['responsecode'] 	= '1';
					$result['msg'] 		 		= 'Record Found Successfully';
					$result['data'] 			= $response;
				}else{
					$result['status']	      	= 'False';
					$result['responsecode'] 	= '404';
					$result['msg'] 		 	 	= 'No Record Found';	
				}
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
				$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;	
			}
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	public function get_all_model() // get all Model like "Audi's => A7"
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$UserId = $this->input->post('UserId');
			$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $UserId, 'Status' => 'Active'));  //check for user status active or not
			
			if($isactive > 0 or $UserId == ''){
				$data['MakeId'] = $this->input->post('MakeId');
				if($this->comman->required_data_exist($data)){ //check required parameter exist or not
					
					$response = $this->db->select('ModelId,ModelName')->from('vehicle_model')->where('MakeId',$data['MakeId'])->order_by('ModelName')->get()->result_array();
					if(count($response) > 0){
						$result['status']	    	= 'True';
						$result['responsecode'] 	= '1';
						$result['msg'] 		 		= 'Record Found Successfully';
						$result['data'] 			= $response;
					}else{
						$result['status']	      	= 'False';
						$result['responsecode'] 	= '404';
						$result['msg'] 		 	 	= 'No Record Found';	
					}
				}else{
					$result['status']	      	= 'False';
					$result['responsecode'] 	= '102';
					$result['msg'] 		 	 	= 'Required Parameter Missing';	
				}
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
				$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;
			}
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	public function get_all_part_type() // get all Model like "Audio,Tyre"
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$UserId = $this->input->post('UserId');
			$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $UserId, 'Status' => 'Active'));  //check for user status active or not
			
			if($isactive > 0 or $UserId == ''){
				$response = $this->comman->select_comman('part_type','part_type_id,part_type_name','');
				if(count($response) > 0){
					$result['status']	    	= 'True';
					$result['responsecode'] 	= '1';
					$result['msg'] 		 		= 'Record Found Successfully';
					$result['data'] 			= $response;
				}else{
					$result['status']	      	= 'False';
					$result['responsecode'] 	= '404';
					$result['msg'] 		 	 	= 'No Record Found';	
				}
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
				$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;	
			}
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	
	public function add_part()
	{
		//print_r($this->input->post());
		//exit;
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
			$data['UserId'] = $this->input->post('UserId');
			$isactive = $this->comman->comman_num_rows('seller',array('UserId' => $data['UserId'], 'Status' => 'Active'));  //check for user status active or not
				
			if($isactive > 0){
				$data['VehiclemakeId']  = $this->input->post('MakeId');
				$data['VehicleModelId'] = $this->input->post('ModelId');
				$data['PartMfgYear']  	= $this->input->post('PartMfgYear');
				$data['PartTypeId'] 	= $this->input->post('PartTypeId');
				$data['Location']     	= $this->input->post('Location');
				$data['PartPrice']    	= $this->input->post('PartPrice');
				$data['PartName']  		= $this->input->post('PartName');
				$data['PartDetails']  	= $this->input->post('PartDetails');
				$data['PartCondition'] 	= $this->input->post('PartCondition');
				
				if($this->comman->required_data_exist($data)){ //check required parameter exist or not
					
					$date = date('Y-m-d H:i:s');
					$data['CreatedOn'] = $date;
					$data['UpdatedOn'] = $date;
					
					/*Set location using postcode or current lat long*/
					if($data['Location'] != ''){ //using postcode
						$search_code = urlencode($data['Location']);
						$search_code = $search_code ."%20astralia";
						$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$search_code.'&sensor=false';
						$json = json_decode(file_get_contents($url));
							
						$data['Lat'] = $json->results[0]->geometry->location->lat;
						$data['Lng'] = $json->results[0]->geometry->location->lng;
						
					}
					if($data['Lat'] == '' and $data['Lng'] == '') // using current lat long
					{
						$data['Lat'] = $this->input->post('Lat');
						$data['Lng'] = $this->input->post('Lng');
					}
					/*Set location using postcode or current lat long*/
					
					$this->comman->comman_insert('part_detail',$data);
					$part_id = $this->db->insert_id();
					
					$this->load->library('upload');
					$this->load->library('image_lib'); 
					
					if(isset($_FILES['image1']['name'])) {
						$photo = $this->img_upload('image1');
						if($photo != ''){
							$this->comman->comman_insert('part_photo', array('PartId' => $part_id, 
																			 'PhotoUrl' => PHOTO_URL.$photo, 
																			 'ThumbUrl' => THUMB_URL.$photo));
						}
					}
					if(isset($_FILES['image2']['name'])) {
						$photo = $this->img_upload('image2');
						if($photo != ''){
							$this->comman->comman_insert('part_photo', array('PartId' => $part_id, 
																			 'PhotoUrl' => PHOTO_URL.$photo, 
																			 'ThumbUrl' => THUMB_URL.$photo));
						}
					}
					if(isset($_FILES['image3']['name'])) {
						$photo = $this->img_upload('image3');
						if($photo != ''){
							$this->comman->comman_insert('part_photo', array('PartId' => $part_id, 
																			 'PhotoUrl' => PHOTO_URL.$photo, 
																			 'ThumbUrl' => THUMB_URL.$photo));
						}
					}
					
					$result['status']	    	= 'True';
					$result['responsecode'] 	= '1';
					$result['msg'] 		 		= 'Your part has been listed for sale. Good luck!';
				}else{
					$result['status']	      	= 'False';
					$result['responsecode'] 	= '102';
					$result['msg'] 		 	 	= 'Required Parameter Missing';	
				}
			}else{
				$result['status']	      	= 'False';
				$result['responsecode'] 	= ACCOUNT_DEACTIVATED_CODE;
				$result['msg'] 		 	 	= ACCOUNT_DEACTIVATED;
			}
		}else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	function img_upload($filename){
		$upload_conf = array(
			'upload_path'   => realpath('./uploads/part/'),
			'allowed_types' => 'gif|jpg|jpeg|png',
			'max_size'      => '30000',
			'encrypt_name'  => true,
		);
		
		$this->upload->initialize( $upload_conf );
		$field_name = $filename;
		
		if ( !$this->upload->do_upload($filename,'')){
			$error['upload']= $this->upload->display_errors();				
		}else{
			$upload_data = $this->upload->data();
			
			$resize_conf = array(
				'upload_path'  => realpath('./uploads/part/thumb/'),
				'source_image' => $upload_data['full_path'], 
				'new_image'    => $upload_data['file_path'].'/thumb/'.$upload_data['file_name'],
				'width'        => 300,
				'height'       => 300);
			
			$this->image_lib->initialize($resize_conf);
			
			if ( ! $this->image_lib->resize()){
				$error['resize'] = $this->image_lib->display_errors();						
			}else{
				return $upload_data['file_name'];						
			}	
		}
	}
	public function banner_ads() // Get all bannder ads
	{
		if($this->comman->api_key($this->input->post('api_key'))) //check api key is same or not
		{
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
			$result['status']	    	= 'True';
			$result['responsecode'] 	= '1';
			$result['msg'] 		 		= 'Record Found Successfully';
			$result['banner'] 			= $banner_ads;
		}
		else{
			$result['status']	      	= 'False';
			$result['responsecode'] 	= '100';
			$result['msg'] 		  	 	= 'Wrong Api Key';	
		}
		echo json_encode($result);
	}
	
	public function getRemoteData($postcode)
	{
		
		/*$url = "https://digitalapi.auspost.com.au/locations/v2/points/postcode/".$postcode;
        
        $headers = array( 
            'AUTH-KEY: d21b1a5c-b6d0-4c5d-a136-1ed3426fefc2'
        ); 
       
        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL,$url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 60); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
        
        $data = curl_exec($ch); 

        if (curl_errno($ch)) { 
            //print "Error: " . curl_error($ch); 
        } else { 
            // Show me the result 
            var_dump($data); 
            curl_close($ch); 
        }*/
		
		
		$search_code = urlencode($postcode);
		
		$url = 'http://maps.googleapis.com/maps/api/geocode/json?address='.$search_code.'&sensor=false';
		$json = json_decode(file_get_contents($url));
			
		$lat = $json->results[0]->geometry->location->lat;
		$lng = $json->results[0]->geometry->location->lng;
		 
	}
}

