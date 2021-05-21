<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
   
class Comman {

	public function api_key($key) {
		if($api_key != 'fad6287d-b3ca-41b2-8efe-2b1e104d7add'){
			$data['status']	      	= 'False';
			$data['responsecode'] 	= '101';
			$data['msg'] 		  	= 'Wrong Api Key';	
			return 0;
		}else{
			return 1;	
		}
	}
}