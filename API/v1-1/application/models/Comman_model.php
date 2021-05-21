<?php
class Comman_model extends CI_Model {

	//User functions
	public function check_user_is_exist($username) {
		return $this->db->where('UserName', $username)->get('seller')->num_rows();
	}
	public function add_user($seller,$device) {
		$currentdatetime = date('Y-m-d H:i:s');
		$seller['Password']  = md5($seller['Password']);
		$seller['Status'] 	 = 'Active';
		$seller['AccessToken'] = uniqid();
		$seller['LogInStatus'] = '1';
		$seller['LastLogin'] = $currentdatetime;
		$seller['CreatedOn'] = $currentdatetime;
		$seller['UpdatedOn'] = $currentdatetime;
		
		if($this->db->insert('seller', $seller)){
			$device['UserId']    = $this->db->insert_id();
			$device['CreatedOn'] = $currentdatetime;
			if($this->db->insert('devices', $device)){
				return array($device['UserId'], $seller['AccessToken']);	
			}else{
				return 0;		
			}
		}else{
			return 0;	
		}
	}
	public function login_user($user) {
		
		$user['Password'] = md5($user['Password']);
		$num = $this->db->where( array('UserName' => $user['UserName'], 'Password' => $user['Password'], 'Status' => 'Active') )
				 	 	->get('seller')->num_rows();
				
		if($num > 0){
			return 1;		
		}else{
			return 0;	
		}
	}
	public function user_status($user) {
		
		$user['Password'] = md5($user['Password']);
		$num = $this->db->where( array('UserName' => $user['UserName'], 'Password' => $user['Password'], 'Status' => 'Inactive') )
				 	 	->get('seller')->num_rows();
		
		if($num > 0){
			return 1;		
		}else{
			return 0;	
		}
	}
	public function update_device($dataDev,$UserId) {
		
		/* Added DeviceType and UserId arguments by Deep Trivedi on 19th Aug 2016 */ 		
		$num = $this->db->where( array('IntUdId' => $dataDev['IntUdId'], 'DeviceToken' => $dataDev['DeviceToken'], 'DeviceType' => $dataDev['DeviceType'], 'UserId' => $UserId) )
				 	 	->from('devices')->count_all_results();
		
		if($num == 0){
			$dataDev['UserId'] = $UserId;	
			$dataDev['CreatedOn'] = date('Y-m-d H:i:s');	
			$this->comman_insert('devices',$dataDev);
		}
		else
		{
		    $this->db->where( array('IntUdId' => $dataDev['IntUdId'], 'DeviceToken' => $dataDev['DeviceToken'], 'DeviceType' => $dataDev['DeviceType'], 'UserId' => $UserId) );
			$this->db->update('devices', array('AccessToken' => $dataDev['AccessToken']) ); 	
		}
	}
	
	/* Added by Deep Trivedi on 19th Aug 2016 */ 
	public function update_device_logout($dataDev) {		
		$this->db->where( array('IntUdId' => $dataDev['IntUdId'], 'DeviceToken' => $dataDev['DeviceToken'], 'DeviceType' => $dataDev['DeviceType'], 'UserId' => $dataDev['UserId']) );
		$this->db->update('devices', array('AccessToken' => '') ); 	
	}
	
	public function check_device_logout($dataDev) {		
	    $num = 0;
		
		$IntUdId = $dataDev['IntUdId'];
		$DeviceToken = $dataDev['DeviceToken'];
		$DeviceType = $dataDev['DeviceType'];
		$UserId = $dataDev['UserId'];
		
		$this->db->select('count(*) as cnt');	
		$this->db->from('devices');
		$this->db->where('UserId', $UserId); 
		$this->db->where('AccessToken !=', ''); 
		$this->db->where('AccessToken !=', 'NULL');
		return $this->db->get()->count_all_results();
		
		/*
		$num = $this->db->where( array('IntUdId' => $dataDev['IntUdId'], 'DeviceToken' => $dataDev['DeviceToken'], 'DeviceType' => $dataDev['DeviceType'], 'UserId' => $dataDev['UserId'], 'AccessToken' => '!=0', 'AccessToken' => '!=NULL') )
				 	 	->from('devices')->count_all_results();
						
		return $num;
		*/
		
	}
	/* Added by Deep Trivedi on 19th Aug 2016 */ 
	
	public function update_login_access($userid,$data) {
		$this->db->where('UserId', $userid);
		$this->db->update('seller', $data);
	}
	public function get_user_id($user) {
		$user['Password'] = md5($user['Password']);
		$result = $this->db->select('UserId,UserName,Mobile,Email,LogInStatus,AccessToken')
						   ->where( array('UserName' => $user['UserName'], 'Password' => $user['Password']) )
				 	 	   ->get('seller')->result_array();
		return $result[0];
	}
	public function reset_password($user) {
		$num = $this->db->where('Email', $user['Email'])
				 	 	->from('seller')->count_all_results();
		
		if($num > 0){
			return 1;
		}else{
			return 0;	
		}
	}
	public function change_password($data,$id) {
		$this->db->update('seller', $data, "UserId = $id");
	}
	public function update_reset_key($data,$Email) {
		$this->db->update('seller', $data, array('Email' => $Email));
	}
	public function inactive_user($data,$id) {
		//Inactive User
		$this->db->update('seller', $data, "UserId = $id");
		
		//Inactive Part of user
		$this->db->update('part_detail', array('Status' => 'Inactive'), "UserId = $id"); 
	}
	public function active_user($data,$id) {
		//active User
		$this->db->update('seller', $data, "UserId = $id");
		
		//active Part of user
		$this->db->update('part_detail', array('Status' => 'Active'), "UserId = $id"); 
	}
	
//*****************************************************************************************
	//Part functions
	public function all_part($data, $count = '0') {
		$dummy = base_url()."api/uploads/dummy.png";
				
		$this->db->select("pd.PartId,pd.PartName,pd.PartCondition,pd.PartPrice, ifnull(`pp`.`PhotoUrl`, '".$dummy."') as PhotoUrl");
		if($data['lat'] != '' && $data['long'] != ''){
			$this->db->select("ROUND(SQRT(POW(69.1 * (pd.lat - '".$data['lat']."'), 2) + POW(69.1 * ('".$data['long']."' - pd.lng) * COS(pd.lat / 57.3), 2)), 1) AS Distance");	
		}
		$this->db->from('part_detail pd');
		$this->db->join('part_photo pp','pd.PartId = pp.PartId','left');
		
		//if search parameter is passed
		if($data['search'] != ''){
			$this->db->like('pd.PartName', $data['search'], 'both');	
		}
		$this->db->where('pd.Status', 'Active');
		$this->db->group_by('pd.PartId'); 
		
		if($count == 0){
			$this->db->limit('20',$data['offset']); 
			if($data['lat'] != '' && $data['long'] != ''){	
				$this->db->order_by('Distance');
			}else{
				$this->db->order_by('pd.PartId', 'desc');
			}
			return $this->db->get()->result_array();
		}else{
			return $this->db->get()->num_rows();
		}
	}
	public function search_part($data, $count = '0') {
		$dummy = base_url()."api/uploads/dummy.png";
				
		$this->db->select("pd.PartId,pd.PartName,pd.PartCondition,pd.PartPrice");
		$this->db->select("ifnull(`pp`.`PhotoUrl`, '".$dummy."') as PhotoUrl");
		
		if($data['lat'] != '' && $data['long'] != ''){
			$this->db->select("ROUND(SQRT(POW(69.1 * (pd.lat - '".$data['lat']."'), 2) + POW(69.1 * ('".$data['long']."' - pd.lng) * COS(pd.lat / 57.3), 2)), 1) AS Distance");	
		}
		
		$this->db->from('part_detail pd');
		$this->db->join('part_photo pp','pd.PartId = pp.PartId','left');
		$this->db->where('pd.Status', 'Active');
		
		if($data['PartTypeId'] != ''){
			$this->db->where('pd.PartTypeId', $data['PartTypeId']);	
		}
		if($data['YearFrom'] != ''){
			$this->db->where('pd.YearFrom <= "'.$data['YearFrom'].'" And "'.$data['YearFrom'].'" <= pd.YearTo ');
		}
		if($data['MakeId'] != ''){
			$this->db->where('pd.VehiclemakeId', $data['MakeId']);	
			
			if($data['ModelId'] != ''){
				$this->db->where('pd.VehicleModelId', $data['ModelId']);	
			}
		}
		if($data['search'] != ''){
			$this->db->like('pd.PartName', $data['search'], 'both');	
		}
		
		$this->db->group_by('pd.PartId'); 
		
		if($count == 0){
			$this->db->limit('20',$data['offset']); 
			if($data['lat'] != '' && $data['long'] != ''){	
				$this->db->order_by('Distance');
			}else{
				$this->db->order_by('pd.PartId', 'desc');
			}
			return $this->db->get()->result_array();
		}else{
			return $this->db->get()->num_rows();
		}
	}
	public function add_search_key($key) {
		$table = 'search_history';
		$isexist = $this->db->where('SearchKey', $key)->get($table)->num_rows();
		
		if($isexist > 0){
			$this->db->query("UPDATE $table SET TotalCount = TotalCount + 1, LastSearchTime = '".date("Y-m-d H:i:s")."' WHERE SearchKey = '".$key."'", false);
		}else{
			$data = array(	'SearchKey' => $key, 
							'TotalCount' => 1,
							'LastSearchTime' => date('Y-m-d H:i:s')
						);
			$this->comman_insert($table,$data);
		}
	}
	public function part_detail($data) {
				
		$this->db->select('pd.PartId,pd.PartName,pd.PartCondition,pd.PartPrice,pd.PartDetails,pd.NotificationWarning,pd.Status,s.Email,s.Mobile');
		
		if($data['lat'] != '' && $data['long'] != ''){
			$this->db->select("ROUND(SQRT(POW(69.1 * (pd.lat - '".$data['lat']."'), 2) + POW(69.1 * ('".$data['long']."' - pd.lng) * COS(pd.lat / 57.3), 2)),1) AS Distance");	
		}
		$this->db->from('part_detail pd');
		$this->db->join('seller s','pd.UserId = s.UserId','left');
		$this->db->where('pd.PartId',$data['part_id']);
		return $this->db->get()->result_array();
	}
	public function part_photos($id) {
				
		$this->db->select('PhotoUrl');	
		$this->db->from('part_photo');
		$this->db->where('PartId',$id);
		return $this->db->get()->result_array();
	}
	public function user_added_part($UserId, $offset = '', $count) {
		$dummy = base_url()."api/uploads/dummy.png";
				
		$this->db->select("pd.PartId,pd.PartName,pd.PartCondition,pd.PartPrice,pd.NotificationWarning, ifnull(`pp`.`PhotoUrl`, '".$dummy."') as PhotoUrl, pd.Status");
		$this->db->from('part_detail pd');
		$this->db->join('part_photo pp','pd.PartId = pp.PartId','left');
		$this->db->where('pd.UserID',$UserId);
		$this->db->group_by('pd.PartId'); 
		
		if($count == '1'){
			return $this->db->get()->num_rows();
		}else{
			if($offset != ''){
				$this->db->limit('20',$offset);
			}
			return $this->db->get()->result_array();	
		}
	}
	
//*************************************************************************************************************************
	//Garage functions	
	public function check_in_mygarage($PartId, $UserId) {
				
		$this->db->from('mygarage');
		$this->db->where('UserId',$UserId);
		$this->db->where('PartId',$PartId);
		return $this->db->count_all_results();
	}
	public function garage_parts($table,$columns,$where) {
		$this->db->select($columns);	
		$this->db->from($table);
		$this->db->where($where);
		$this->db->order_by('CreatedOn','desc');
		return $this->db->get()->result_array();
	}
	public function my_garage($PartIds, $lat = '', $lng = '') {
		$dummy = base_url()."api/uploads/dummy.png";
		
		$this->db->select("pd.PartId,pd.PartName,pd.PartCondition,pd.PartPrice, ifnull(`pp`.`ThumbUrl`, '".$dummy."') as ThumbUrl");
		if($lat != '' && $lng != ''){
			$this->db->select("ROUND(SQRT(POW(69.1 * (pd.lat - '".$lat."'), 2) + POW(69.1 * ('".$lng."' - pd.lng) * COS(pd.lat / 57.3), 2)),1) AS Distance");	
		}
		
		$this->db->from('part_detail pd');
		$this->db->join('part_photo pp','pd.PartId = pp.PartId','left');
		$this->db->where_in('pd.PartId', $PartIds); 
		$this->db->group_by('pd.PartId'); 
		if($lat != '' && $lng != ''){
			$this->db->order_by('Distance'); 
		}else{
			$this->db->order_by('CreatedOn', 'desc');
		}
		return $this->db->get()->result_array();
	}
	
	
//**********************************************************************************************************************
	//Comman Functions           (tbl_name, columns or *, array for where condition)
	public function select_comman($table,$columns,$where) {
		$this->db->select($columns);	
		$this->db->from($table);
		if($where != ''){
			$this->db->where($where);
		}
		return $this->db->get()->result_array();
	}
	public function comman_num_rows($table,$where) {
		return $this->db->where($where)->get($table)->num_rows();
	}
	public function comman_insert($table,$data) {
		$this->db->insert($table, $data); 
	}
	public function comman_update($table,$data,$where) {
		$this->db->update($table, $data, $where); 
	}
	public function comman_delete($table,$data) {
		$this->db->delete($table, $data); 
	}
	
	//constant functions for API Key
	public function api_key($api_key) {
		if($api_key == 'd1ceb97e-098d-44cf-b95a-f56fe9ec6c43'){
			return 1;
		}else{	
			return 0;	
		}
	}
	//Check for required params
	public function required_data_exist($data) {
		foreach($data as $val){
			if($val == ''){
				return false;
				break;	
			}	
		}
		return true;
	}
	function send_email($subject,$to,$from,$body) 
	{				
		// Always set content-type when sending HTML email
		$headers = "MIME-Version: 1.0" . "\r\n";
		$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		
		// More headers
		$headers .= "From: <$from>" . "\r\n";
		
		if(mail($to,$subject,$body,$headers)){
			return 1;
		}
		else{
			return 0;
		}
	}
	function html_body($ResetKey) 
	{
		$body = '<html>
		<body class="" style="font-family: sans-serif; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.4; -ms-text-size-adjust: 100%; -webkit-text-size-adjust: 100%; background-color: #f6f6f6; margin: 0; padding: 0;">
    <table border="0" cellpadding="0" cellspacing="0" class="body" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background-color: #f6f6f6;" width="100%" bgcolor="#f6f6f6">
      <tr>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
        <td class="container" style="font-family: sans-serif; font-size: 14px; vertical-align: top; display: block; Margin: 0 auto !important; padding: 10px; width: 100%;" valign="top">
          <div class="content" style="box-sizing: border-box; display: block; Margin: 0 auto; max-width: 580px; padding: 10px;">

            <!-- START CENTERED WHITE CONTAINER -->
            <table class="main" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; background: #fff; border-radius: 3px;" width="100%">
			  <!-- START MAIN CONTENT AREA -->
              <tr>
                <td class="wrapper" style="font-family: sans-serif; font-size: 14px; vertical-align: top; box-sizing: border-box; padding: 20px;" valign="top">
                  <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%;" width="100%">
                    <tr>
                      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Hi there,</p>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">You\'ve requested a password reset for your My Partz account.</p>
                        <table border="0" cellpadding="0" cellspacing="0" class="btn btn-primary" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: 100%; box-sizing: border-box;" width="100%">
                          <tbody>
                            <tr>
                              <td align="left" style="font-family: sans-serif; font-size: 14px; vertical-align: top; padding-bottom: 15px;" valign="top">
                                <table border="0" cellpadding="0" cellspacing="0" style="border-collapse: separate; mso-table-lspace: 0pt; mso-table-rspace: 0pt; width: auto;">
                                  <tbody>
                                    <tr>
                                      <td style="font-family: sans-serif; font-size: 14px; vertical-align: top; background-color: #3498db; border-radius: 5px; text-align: center;" valign="top" bgcolor="#3498db" align="center"> <a href="'.base_url().'api/user/password/'.$ResetKey.'" target="_blank" style="display: inline-block; color: #ffffff; background-color: #3498db; border: solid 1px #3498db; border-radius: 5px; box-sizing: border-box; cursor: pointer; text-decoration: none; font-size: 14px; font-weight: bold; margin: 0; padding: 12px 25px; text-transform: capitalize; border-color: #3498db;">Reset Password</a> </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <p style="font-family: sans-serif; font-size: 14px; font-weight: normal; margin: 0; Margin-bottom: 15px;">Best regards,<br>
The My Partz Team</p>

                      </td>
                    </tr>
                  </table>
                </td>
              </tr>

              <!-- END MAIN CONTENT AREA -->
              </table>
 
        <!-- END CENTERED WHITE CONTAINER -->
        </div>
        </td>
        <td style="font-family: sans-serif; font-size: 14px; vertical-align: top;" valign="top">&nbsp;</td>
      </tr>
    </table>
  </body>
</html>';
		
		return $body;
	}				
					
}