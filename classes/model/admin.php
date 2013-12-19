<?php defined('SYSPATH') or die('No direct script access.');
/*
 * @Class: Controller_Activity
 * @Author: Durga Malleswar
 * @Created: 7-09-2010
 * @Modified:	27-09-2010 
 * 				13.11.2013 : added function get_admin_consumer: admin_user data from user-email and password.
 * 				13.11.2013 : added function expire_day_left: which will tell expired days left from company created date.
 * @description: This class file holds the operations of activity slips.
 */
class Model_Admin extends Model
{
	/**
	@function		:	verify_users
	@param1			:	username (entered by user)
	@param2			:	password (entered by user)
	@return			: 	user info as array if user is valid else false.
	*/
	private $total_users;
	private $plan_max_users;
	
	/**
	 * @Function : get_admin_consumer
	 * @Description : Verify admin username and password and return the consumer key and secret.
	 * @Param1 : User Email
	 * @Param2 : User Password	 
	 */
	public function get_admin_consumer($useremail, $password)
	{
		$useremail=strtolower($useremail);	
		
		$sql	=	"SELECT c.activation_id, c.active_status, a.company_id
					 FROM company AS c, admin_users AS a
					 WHERE c.id = a.company_id
					 AND a.email = '".addslashes($useremail)."'
					 AND a.password = AES_ENCRYPT('".addslashes($password)."','".ENCRYPT_KEY."')
					 ";
		$query	=	DB::query(Database::SELECT, $sql);
		$result	=	$query->execute()->as_array();
		
		if(!empty($result) && $result[0]['active_status'] == 0)
		{
			return $result;
		}
		$query	=	"SELECT au.id, au.lastname as lastname, au.email AS user_email, da.device_name, AES_DECRYPT(da.token, '".ENCRYPT_KEY."') as token, AES_DECRYPT(da.secret, '".ENCRYPT_KEY."') as secret,
					 au.company_id, c.name AS company_name, au.name AS user_name, c.activation_id, c.active_status, c.status_flag, c.country
					 FROM admin_users AS au, company AS c, dropbbox_account AS da
					 WHERE c.id = au.company_id
					 AND da.company_id = c.id
					 AND au.email = '".addslashes($useremail)."'
					 AND au.password = AES_ENCRYPT('".addslashes($password)."','".ENCRYPT_KEY."')
					 ";					 
		$query	=	DB::query(Database::SELECT, $query);
		$data	=	$query->execute()->as_array();
		return $data;
		
	}

	/*
	 * @Method: expire_day_left
	 * @Description: This method will give no. of days left in expiration.
	 */
	public function expire_day_left($company_id)
	{
		$sql		=	"SELECT DATE_ADD(created_date, INTERVAL 30 DAY) as default_end_date, end_date, expire_date_modify
						 FROM company
						 WHERE id = '".$company_id."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if($result[0]['expire_date_modify'] == 1) {
			$current_end_date	=	$result[0]['end_date'];
		} else {
			$current_end_date	=	$result[0]['default_end_date'];
		}
		$sql	=	"SELECT c.created_date as startdate, DATEDIFF('".$current_end_date."', now()) as days_left
					 FROM company as c
					 WHERE c.id = '".$company_id."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $data[0]['days_left'];
	}
	
	/*
	 * @Method: get_users 
	 * @Description: This method fetches the activits from database.
	 */
	public function get_users($user_type)
	{
		$user_type	=	empty($user_type)?2:$user_type;
		
		$employee_m	=	new Model_Employee;
		$vendor_m 	= 	new Model_Vendor;
		
		switch($user_type)
		{
			case 1:
					$emp_array	=	$employee_m->get_all_employee($_SESSION['company_id']);
					$ven_array	=	$vendor_m->get_all_vendor($_SESSION['company_id']);
					if(!empty($emp_array) && !empty($ven_array))
					return array_merge($emp_array,$ven_array);
					break;
			case 2:
					return $employee_m->get_all_employee($_SESSION['company_id']);
					break;
			case 3: 
					return $vendor_m->get_all_vendor($_SESSION['company_id']);
					break;
		}
		
	}
	
	/**
	 * @Method      : 	sort_user_session 
	 * @Description : 	get the user details.
	 * @Params		:	array
	 * @Return		:	array
	 */
/*
	private function sort_user_session($arr_user)
	{
		$arr_splitted	=	array();
		$arr_sorted		=	array();
		$i=0;
		 
		try {
		foreach($arr_user as $user) {
			if(empty($user['FirstName'])) {
				$name_key_index		=	str_replace(" ","_",strtolower($user['CompanyOrLastName']."_".$i));
				$arr_splitted[$name_key_index]['name']	=	$user['CompanyOrLastName'];
			} else {
				$name_key_index		=	str_replace(" ","_",strtolower($user['FirstName']."_".$i));
				$arr_splitted[$name_key_index]['name']	=	$user['FirstName']." ".$user['CompanyOrLastName'];
			}
			$arr_splitted[$name_key_index]['id']	=	$user['RecordID'];
			$i++;
		}} catch(Exception $e) {die($e->getMessage());}
		ksort($arr_splitted);
		unset($user);
		$j=0;
		foreach($arr_splitted as $user) {
			$arr_sorted[$j]['CompanyOrLastName']	=	$user['name'];
			$arr_sorted[$j]['RecordID']				=	$user['id'];
			$user_active_result						=	$this->get_user_info($user['id'], $_SESSION['company_id']);
			$arr_sorted[$j]['active_status']		=	empty($user_active_result)?0:1;
			$arr_sorted[$j]['email']				=	empty($user_active_result)?"":$user_active_result[0]['email'];
			$j++;
		}
		return $arr_sorted;
	}*/

	
	/**
	 * @function	:	update_employees
	 * @description	:	update employee.josn file
	*/
	public function update_employees($params,$admin="")
	{
		$data_model		=	new Model_Data();
		$dharmausers_m 	=	new Model_Dharmausers;
		$employee_m 	=	new Model_Employee;
		$vendor_m 		=	new Model_Vendor;
		$return_val		=	1;
		try
		{
			if(isset($params['Email']))
			{ 
				$params['Email'] = strtolower($params['Email']);
			}
			
			$check_user = $dharmausers_m->get_user_info($params['RecordID'], $_SESSION['company_id']);
			if(isset($check_user)&&!$check_user) // if its new user then insert it
			{
				$limit_expired_flag		=	$this->check_user_limit();
				if($limit_expired_flag) {
					throw new Kohana_Exception(Kohana::message('error', 'company_user_limit_expired'));
				}
				$active_id	= md5(time().session_id().rand());
				$query_user = sprintf('INSERT INTO dharma_users '."\n".
										'SET 	record_id		= %s,
											 	first_name 		= %s,
											 	last_name 		= %s, 
											 	email     		= %s,
											 	password 		= AES_ENCRYPT(%s, "'.ENCRYPT_KEY.'"),
											 	type			= %s,
											 	company_id		= %s,
											 	created_date	= %s,
											 	status			= %s,
											 	display_rate	= %s,
 											 	show_jobs 		= %s,
											 	active_id  		= %s',
												$params['RecordID'],
												$this->_db->escape($params['FirstName']),
												$this->_db->escape($params['LastName']),
												$this->_db->escape($params['Email']),					
												$this->_db->escape($params['Password']),
												$this->_db->escape($params['Type']),
												$_SESSION['company_id'],
												'now()',
												'1',
												$this->_db->escape($params['rate_display']),
												$this->_db->escape($params['show_jobs']),
												$this->_db->escape($active_id)
												);
				$mail_content['to']			=	$params['Email'];
				$mail_content['cc']			=	"";
				if($params['Type'] == "Employee") {
					$mail_content['subject']	=	"AccountEdge Cloud Employee card registration";
				} else {
					$mail_content['subject']	=	"AccountEdge Cloud Vendor card registration";
				}
				$web_link					=	SITEURL;
				
				
				$sql						=	"SELECT c.name as company_name, a.email as email, a.name as user_name
												 FROM company as c
												 LEFT JOIN admin_users as a
												 ON c.id = a.company_id
												 WHERE c.id = '".$_SESSION['company_id']."'";
				$query						=	DB::query(Database::SELECT, $sql);
				$company_info				=	$query->execute()->as_array();
				$mail_content['content']	=	$this->get_user_active_mail($params, $company_info[0], $active_id);
				$data_model->send_email($mail_content,1); // send email regarding the changes
			}
			else
			{ // updating the user
				
				$query_user = sprintf('UPDATE dharma_users '."\n".
										'SET first_name = %s,
											 last_name 	= %s, 
											 email      = %s,
											 password 	= AES_ENCRYPT(%s, "'.ENCRYPT_KEY.'"),
											 display_rate = %s,
											 show_jobs    = %s'."\n".
										'WHERE record_id =	"'.$params['RecordID'].'"
										 AND company_id  =  "'.$_SESSION['company_id'].'"',
												$this->_db->escape($params['FirstName']),
												$this->_db->escape($params['LastName']),
												$this->_db->escape($params['Email']),
												$this->_db->escape($params['Password']),	
												$this->_db->escape($params['rate_display']),
												$this->_db->escape($params['show_jobs'])
												);
				
				if($dharmausers_m->change_in_email($params)) {
					$mail_content['to']			=	$params['Email'];
					$mail_content['subject']	=	"AccountEdge Cloud User Account Update";
					$web_link					=	SITEURL;
					$sql						=	"SELECT c.name as company_name, a.email as email, a.name as user_name
													 FROM company as c
													 LEFT JOIN admin_users as a
													 ON c.id = a.company_id
													 WHERE c.id = '".$_SESSION['company_id']."'";
					$query						=	DB::query(Database::SELECT, $sql);
					$company_info				=	$query->execute()->as_array();
					$mail_content['content']	=	$this->get_update_user_email_template($params, $company_info[0]);
					$data_model->send_email($mail_content,1); // send email regarding the changes
				} else {
					$return_val	=	2;
				}
			}
			
			$this->_db->query(Database::INSERT, $query_user, False);
			
			// update employee/vendor is active in the employee/vendor table.
			$db_key_value['active_status']=1;
			$db_key_value['email']=($params['Email']);
			
			if ($params['Type']=='Employee'){
				$employee_m->update_employee_by_key($_SESSION['company_id'], $params['RecordID'], $db_key_value);
			} else if ($params['Type']=='Vendor'){
				$vendor_m->update_vendor_by_key($_SESSION['company_id'], $params['RecordID'], $db_key_value);
			}
			
			return $return_val;
		}
		catch (Exception $e)
		{ //die($e->getMessage());
			throw new Exception($e->getMessage());
		}	
	}
	
	
	
	public function get_update_user_email_template($params, $company)
	{
		$content      =	View::factory("admin/update_user_email")
							->set('company', $company)
							->set('post', $params);
		return $content;
	}
	
	private function check_user_limit()
	{
		$sql	=	"SELECT c.signup_plan_id, spu.user_limit, count(d.id) as total_users
					 FROM company_plan as c 
					 LEFT JOIN signup_plan as s 
					 ON c.signup_plan_id = s.plan_id
					 LEFT JOIN signup_plan_user_limit spu
					 ON s.plan_id = spu.plan_id
					 LEFT JOIN dharma_users as d
					 ON c.company_id = d.company_id
					 WHERE c.company_id = '".$_SESSION['company_id']."'
					 AND d.status = 1
					 GROUP BY d.company_id";
		$query		=	DB::query(Database::SELECT, $sql);
		$data		=	$query->execute()->as_array();
		
		if(empty($data)) return false;
		if($data[0]['user_limit'] == "*") return false;
		elseif($data[0]['total_users'] >= $data[0]['user_limit']) return true;
		else return false;
	}
	
	/*
     * @Function 	  : action_emailCheck
	 * @Description   : Checks whether email exists or not.
	 */
	public function check_email($email)
	{
	   $sql 		= "(
	   						SELECT id 
	   						FROM temp_company_signup 
	   						WHERE email = '".addslashes(strtolower(trim($email)))."'
	   					) 
	   					UNION ALL(
	   						SELECT id
	   						FROM admin_users 
	   						WHERE email = '".addslashes(strtolower(trim($email)))."'
						)
						UNION ALL(
							SELECT id
	   						FROM dharma_users 
	   						WHERE email = '".addslashes(strtolower(trim($email)))."'
						)";
	   $query		=	DB::query(Database::SELECT, $sql);
	   $data		=	$query->execute()->as_array();
	   return $data;
	}
	
	
	/**
	@Function 	:	new_user
	@Description:	Check whether the user is already available or not.
	*/
/*
	public function new_user($user_id, $company_id)
	{
		$query		=	"SELECT * 
						 FROM dharma_users
						 WHERE record_id = '".$user_id."'
						 AND company_id = '".$company_id."'";
		$query		=	DB::query(Database::SELECT, $query);
		$data		=	$query->execute()->as_array();
		if(empty($data))
			return true;
		else 
			return false;
	}*/

	
	/**
	 * @Function	:	check_super_admin
	 * @Description	:	Check user is super admin or company admin
	 * @Param1		:	Username entered by user
	 * @Param2		:	Password entered by user
	 * @Return		:	True if super admin else false
	*/
	public function check_super_admin($username, $password)
	{
		$query	=	"SELECT *
					 FROM superadmin_user
					 WHERE username = '".addslashes($username)."'
					 AND password	= '".md5(addslashes($password))."'";
		$query		=	DB::query(Database::SELECT, $query);
		$data		=	$query->execute()->as_array();
		
		if(!empty($data))
		{
			$_SESSION['superadmin']		=	1;
			$_SESSION['admin_email']	=	'Admin@Dharma';
			if($data[0]['readonly']) {
				$_SESSION['readonly_super_admin']	=	1;
			}
			return true;
		}
		else 
		{
			return false;
		}
	}
	
	/**
	 * @Function	:	save_company
	 * @Description	:	Save company details into the database.
	 * @Param1		:	User input data
	*/
	public function save_company($post)
	{
		$data_model	=	new Model_Data;
		$edit	=	empty($post['company_id']) ? false:true;
		
		if(isset($post['UserEmail'])){
			$post['UserEmail']= addslashes(strtolower($post['UserEmail']));
		}
		
		if(empty($post['company_name']) OR $post['company_name'] == "")
		{
			//throw new Kohana_Exception('Please enter Company Name');
			throw new Kohana_Exception(Kohana::message('error', 'company_name'));
		}
		if(empty($post['serialnumber']) OR $post['serialnumber'] == "")
		{
			//throw new Kohana_Exception('Please enter Serial Number.');
			throw new Kohana_Exception(Kohana::message('error', 'serial_num'));
		}
		if(strlen($post['serialnumber']) != 12)
		{
			//throw new Kohana_Exception('Please enter Serial Number.');
			throw new Kohana_Exception(Kohana::message('error', 'serial_num'));
		}
		if(!preg_match("/^([0-9]*)$/",$post['serialnumber']))
		{
			//throw new Kohana_Exception('Please enter Serial Number.');
			throw new Kohana_Exception(Kohana::message('error', 'serial_num'));
		}
		if(empty($post['UserEmail']) OR $post['UserEmail'] == "")
		{
			//throw new Kohana_Exception('Please enter Email.');
			throw new Kohana_Exception(Kohana::message('error', 'valid_email'));
		}
		if(!preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/",$post['UserEmail']))
		{
			//throw new Kohana_Exception('Please enter valid Email.');
			throw new Kohana_Exception(Kohana::message('error', 'valid_email'));
		}
		if(empty($post['Password']) OR $post['Password'] == "")
		{
			//throw new Kohana_Exception('Please enter Password.');
			throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
		}
		if(!preg_match("/^([a-zA-Z0-9\!\@\$\\~\?]*)$/",$post['Password']))
		{
			throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
		}
		if(strlen($post['Password']) < 6 || strlen($post['Password'])>15)
		{
			//throw new Kohana_Exception('Please enter Password.');
			throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
		}
		if($this->email_exists($post['UserEmail'], $post))
		{
			//throw new Kohana_Exception('Email already exists.');
			throw new Kohana_Exception(Kohana::message('error', 'email_exists'));
		}
		
		if($edit) // user want to edit the company
		{
 			$sql 		=  "UPDATE company 
							SET name 			= '".addslashes($post['company_name'])."',
								serialnumber 	= '".addslashes($post['serialnumber'])."'
							WHERE id			= '".addslashes($post['company_id'])."'
							";
			$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			
			$sql 		=  "UPDATE admin_users 
							SET email 			= '".$post['UserEmail']."',
								password		= AES_ENCRYPT('".addslashes($post['Password'])."','".ENCRYPT_KEY."')
							WHERE company_id	= '".addslashes($post['company_id'])."'
							";
			$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			
		}
		else // user adding a new company
		{
			$activate_id	=	md5(time().rand());
			$sql 		=  "INSERT INTO company (name,serialnumber,created_date,activation_id,active_status)  
								VALUES ('" .addslashes($post['company_name'])."',
										'" .addslashes($post['serialnumber'])."',
										now(),
										'".$activate_id."',
										0
										)";
			$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			$company_id	=  $query[0];
			
			$sql		=	"INSERT INTO admin_users (email, password, name, company_id)
							 VALUES (
								'".$post['UserEmail']."',
								AES_ENCRYPT('".addslashes($post['Password'])."','".ENCRYPT_KEY."'),
								'".addslashes($post['company_name'])."',
								'".$company_id."'
							 )";
			$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			$mail_content['to']			=	$post['UserEmail'];
			$mail_content['subject']	=	"AccountEdge Cloud Registration";
			$activation_link			=	SITEURL."/admin/activation/".$activate_id;
			$mail_content['content']	=	"Dear ".ucfirst($post['company_name'])." ,<br/><br/>
														You have been signed up as an Administrator for AccountEdge Cloud.<br/><br/>
														Please complete your AccountEdge Cloud registration by clicking the following link:<br/>
														<a href='".$activation_link."'>".$activation_link."</a><br/><br/>
														Sincerely,<br/><br/>
														The AccountEdge Team.
											";
			
			$data_model->send_email($mail_content);	// send email.
		}
		
		return true;
	}
	
	/**
	 * @Function	:	email_exists
	 * @Description	:	Check whether admin user email is already exists or not.
	 * @Param1		:	Email
	 * @Param2		:	User input
	*/
	private function email_exists($email, $post)
	{
		$email=addslashes(trim(strtolower($email)));
		if(empty($post['company_id'])) // adding new company
		{
			$query	=	"SELECT *
						 FROM admin_users
						 WHERE email = '".addslashes(trim($email))."'";
		}
		else
		{
			$query	=	"SELECT *
						 FROM admin_users
						 WHERE email = '".addslashes(trim($email))."'
						 AND company_id != '".$post['company_id']."'";
		}
		$query		=	DB::query(Database::SELECT, $query);
		$data		=	$query->execute()->as_array();
		if(empty($data)) 
			return false;
		else 
			return true;
	}
	
	/**
	 * @Function	:	get_company_list
	 * @Description	:	Fetch all the company from the system
	*/
	public function get_company_list()
	{
		$query	=	"SELECT c.id, c.name as name, 1 as status
					 FROM company as c
					 WHERE status_flag = 1
					 UNION
					 SELECT c.id, c.company_name as name, 2 as status
					 FROM temp_company_signup as c
					 WHERE 1=1
					 ORDER BY name ASC";
		
		$query		=	DB::query(Database::SELECT, $query);
		$data		=	$query->execute()->as_array();
		return $data;
	}
	
	/**
	 * @Function	:	get_company_info
	 * @Description	:	Get individual company information
	 * @Param1		:	Company id
	*/
	public function get_company_info($company_id)
	{
		$query	=	"SELECT c.name, c.id, c.serialnumber , a.email, da.device_name, 
					 da.email as dropbox_email, da.password as dropboxpassword, c.activation_id,
					 c.active_status
					 FROM company as c LEFT JOIN admin_users as a ON
					 c.id = a.company_id
					 LEFT JOIN dropbbox_account as da ON c.id = da.company_id 
					 WHERE c.id = '".addslashes($company_id)."'";
		$query		=	DB::query(Database::SELECT, $query);
		$data		=	$query->execute()->as_array();
		//echo "<pre>";print_r($data);die;
		if(empty($data)) throw new Exception("Invalid company id.");
		else return $data;
	}
	
	/**
	 * @Function	:	delete_company
	 * @Description	:	Delete company entry from the list of following tables:
	 * 					account
						activities
						activities_sync 
						activity_slip_lists
						admin_users
						aec_api_master
						aed_updated_files
						company
						company_plan
						customers
						custom_list_fields
						custom_list_items
						dharma_users
						dropbbox_account
						email_support
						gateway_ach
						items
						jobs
						sales
						sales_and_purchase
						taxes						
						Also calling the OUTH delete function for registry delete.
	 * @Param1		:	Company id
	 * modified date: 	02-09-2013
	*/
	public function delete_company($company_id) //rolls : check this
	{
		// get user id 
		$sql = "SELECT * FROM admin_users WHERE company_id = '".addslashes($company_id)."'";
		$user_data  =  DB::query(Database::SELECT, $sql);
		if(isset($user_data)){
			$user_data		=	$user_data->execute()->as_array();
			if(isset($user_data[0])){
				$user_data		= $user_data[0];
			} else {
				$user_data		= NULL;
				return FALSE;
			}	// $user_data['id']
		}else{
			return FALSE;
		}
		
		// calling oauth delete function.
		$oauth_m = new Model_Oauth;
		$oauth_m->delete_user_by_user($user_data['id']);
		
		$count = array();
		// account
		$sql	=	"DELETE FROM accounts WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["account"]=$query;
		
		// activities
		$sql	=	"DELETE FROM activities WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["activities"]=$query;
		
		//activities_sync
		$sql	=	"DELETE FROM activities_sync WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["activities_sync"]=$query;
		
		//activity_slip_lists
		$sql	=	"DELETE FROM activity_slip_lists WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["activity_slip_lists"]=$query;
		
		//admin_users
		$sql	=	"DELETE FROM admin_users WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["admin_users"]=$query;
		
		//aec_api_master
		$sql	=	"DELETE FROM aec_api_master WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["aec_api_master"]=$query;
		
		//aed_updated_files
		$sql	=	"DELETE FROM aed_updated_files WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["aed_updated_files"]=$query;
		
		//company
		$sql	=	"DELETE FROM company WHERE id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["company"]=$query;
		
		//company_plan
		$sql	=	"DELETE FROM company_plan WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["company_plan"]=$query;
		
		//customers
		$sql	=	"DELETE FROM customers WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["customers"]=$query;
		
		//custom_list_fields
		$sql	=	"DELETE FROM custom_list_fields WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["custom_list_fields"]=$query;
		
		//custom_list_items
		$sql	=	"DELETE FROM custom_list_items WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["custom_list_items"]=$query;
		
		//dharma_users
		$sql	=	"DELETE FROM dharma_users WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["dharma_users"]=$query;
		
		//dropbbox_account
		$sql	=	"DELETE FROM dropbbox_account WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["dropbbox_account"]=$query;
		
		//email_support
		$sql	=	"DELETE FROM email_support WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["email_support"]=$query;
		
		//gateway_ach
		$sql	=	"DELETE FROM gateway_ach WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["gateway_ach"]=$query;
		
		//items
		$sql	=	"DELETE FROM items WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["items"]=$query;
		
		//jobs
		$sql	=	"DELETE FROM jobs WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["jobs"]=$query;
		
		//sales
		$sql	=	"DELETE FROM sales WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["sales"]=$query;
		
		//sales_and_purchase
		$sql	=	"DELETE FROM sales_and_purchase WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["sales_and_purchase"]=$query;
		
		//taxes
		$sql	=	"DELETE FROM taxes WHERE company_id = '".addslashes($company_id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		$count["taxes"]=$query;

		return $count;		
	}
	
	/**
	 * @Function	:	save_consumer
	 * @Description	:	Save consumer key and secret based on the company.
	 * @Param1		:	User input
	*/
	public function save_consumer($post)
	{
		$sql	=	"UPDATE company 
					 SET consumer_key   =  '".$post['key']."',
						 consumer_secret = '".$post['secret']."'
					 WHERE id = '".$post['company']."'
					";
		$query  =  $this->_db->query(Database::INSERT, $sql, true); // update company table by adding key information
		
		/** fetch company details and login to the system automatically **/
		$query	=	"SELECT email as username, AES_DECRYPT(password, '".ENCRYPT_KEY."') as password
					 FROM admin_users 
					 WHERE company_id = '".addslashes($post['company'])."'";
					 
		$query		=	DB::query(Database::SELECT, $query);
		$data		=	$query->execute()->as_array();
		if(empty($data)) throw new Exception("Invalid company id.");
		else return $data;
	}
	
	/**
	 * @description : Insert/update company information.
	 * @param $post : Array containing company inputs.
	 */
	public function submit_company_info($post, $payment_status="")
	{
		$post['company_name']=addslashes(htmlentities(trim(strtolower($post['company_name']))));
		$post['UserEmail']=addslashes(htmlentities(trim(strtolower($post['UserEmail']))));
		if(isset($_POST['company_id'])) // edit company information
		{
			if(isset($_POST['offline'])) {
				$sql	=	"UPDATE temp_company_signup
							 SET company_name ='".$post['company_name']."',
							 serialnumber ='".addslashes(htmlentities(trim($post['serialnumber'])))."',
							 billing_name ='".addslashes(htmlentities(trim($post['cname'])))."',
							 address='".addslashes(htmlentities(trim($post['address'])))."',
							 city='".addslashes(htmlentities(trim($post['city'])))."',
							 state='".addslashes(htmlentities(trim($post['state'])))."',
							 country='".addslashes(htmlentities(trim($post['country'])))."',
							 zipcode='".addslashes(htmlentities(trim($post['zipcode'])))."',
							 phone='".addslashes(htmlentities(trim($post['phone'])))."',
							 email = '".$post['UserEmail']."',
							 password = AES_ENCRYPT('".addslashes($post['password'])."','".ENCRYPT_KEY."'),
							 name = '".addslashes(htmlentities(trim($post['name'])))."',
							 last_name = '".addslashes(htmlentities(trim($post['lastname'])))."'
							 WHERE id = '".$_POST['company_id']."'
							 ";//die($sql);
				$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			}
			else {
				$sql	=	"UPDATE company
							 SET name='".$post['company_name']."',
							 serialnumber='".addslashes(htmlentities(trim($post['serialnumber'])))."',
							 billing_name='".addslashes(htmlentities(trim($post['cname'])))."',
							 address='".addslashes(htmlentities(trim($post['address'])))."',
							 city='".addslashes(htmlentities(trim($post['city'])))."',
							 state='".addslashes(htmlentities(trim($post['state'])))."',
							 country='".addslashes(htmlentities(trim($post['country'])))."',
							 zipcode='".addslashes(htmlentities(trim($post['zipcode'])))."',
							 phone='".addslashes(htmlentities(trim($post['phone'])))."'
							 WHERE id = '".$_POST['company_id']."'
							 ";//die($sql);
				$query  	=  $this->_db->query(Database::INSERT, $sql, true);
				
				// next edit company user name and password
				$sql	=	"UPDATE admin_users
							 SET email='".$post['UserEmail']."',
							 	 name = '".addslashes(htmlentities(trim($post['name'])))."',
 								 lastname = '".addslashes(htmlentities(trim($post['lastname'])))."',
								 password = AES_ENCRYPT('".addslashes($post['password'])."','".ENCRYPT_KEY."')
							 WHERE company_id = '".$_POST['company_id']."'";
				$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			}
		}
		else // insert new company
		{
			
			$data_model		=	new Model_Data;
			$activation_id	=	md5(time().rand());
			
			$mail_content['to']			=	$post['UserEmail'];
			$mail_content['subject']	=	"AccountEdge Cloud Registration";
			$activation_link			=	SITEURL."/admin/activation/".$activation_id;
			$mail_content['content']	=   $this->get_signup_email_content($post, $activation_link);
			$data_model->send_email($mail_content, 1);	// send email.
			
			$sql		=	"INSERT INTO temp_company_signup (
								plan, company_name, serialnumber, billing_name, address, city, state, country, zipcode, 
								phone, email, password, name, created_date, activation_id, status
								)
							VALUES (
								'".addslashes($post['plan'])."',
								'".$post['company_name']."',
								'".addslashes(htmlentities(trim($post['serialnumber'])))."',
								'".addslashes(htmlentities(trim($post['cname'])))."',
								'".addslashes(htmlentities(trim($post['address'])))."',
								'".addslashes(htmlentities(trim($post['city'])))."',
								'".addslashes(htmlentities(trim($post['state'])))."',
								'".addslashes(htmlentities(trim($post['country'])))."',
								'".addslashes(htmlentities(trim($post['zipcode'])))."',
								'".addslashes(htmlentities(trim($post['phone'])))."',
								'".$post['UserEmail']."',
								AES_ENCRYPT('".addslashes($post['password'])."','".ENCRYPT_KEY."'),
								'".addslashes(htmlentities(trim($post['name'])))."',
								now(),
								'".$activation_id."',
								1
							)";
			$query  	=  $this->_db->query(Database::INSERT, $sql, true);
		}
		return true;
	}
	
	
	public function register_free_user($post) 
	{
			$data_model		=	new Model_Data;
			$activation_id	=	md5(time().rand());
			
			$mail_content['to']			=	$post['UserEmail'];
			$mail_content['subject']	=	"AccountEdge Cloud Registration";
			$activation_link			=	SITEURL."/admin/activation/".$activation_id;
			$post['company_name']=addslashes(htmlentities(trim(strtolower($post['company_name']))));
			$post['UserEmail']=addslashes(htmlentities(trim(strtolower($post['UserEmail']))));
			$mail_content['content']	=   $this->get_signup_email_content($post, $activation_link, 1);
			$data_model->send_email($mail_content, 1);	// send email.
			
			if(!empty($_POST['ref_id'])) {
				$obj_register	=	new Model_Register;
				$comp_id		=	$obj_register->get_company_id_by_token($_POST['ref_id']);
				$sql			=	"UPDATE company
								 	 SET name = '".$post['company_name']."',
								 		 status_flag = 1,
								 		 activation_id = '".$activation_id."'
									 WHERE id = '".$comp_id."'";
				$query  		=  $this->_db->query(Database::INSERT, $sql, true);
				
				$sql			=	"UPDATE admin_users
									 SET email = '".$post['UserEmail']."',
									 password  = AES_ENCRYPT('".addslashes($post['password'])."','".ENCRYPT_KEY."')
									 WHERE company_id = '".$comp_id."'";
				$query  	=  $this->_db->query(Database::INSERT, $sql, true);
				
				$sql		=	"UPDATE company_plan
								 SET signup_plan_id = '".addslashes($post['plan'])."',
								 service_token = ''
								 WHERE company_id = '".$comp_id."'";
				$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			} else {
				$sql		=	"INSERT INTO temp_company_signup (
									plan, company_name, email, password,created_date, activation_id, status
									)
								VALUES (
									'".addslashes($post['plan'])."',
									'".$post['company_name']."',
									'".$post['UserEmail']."',
									AES_ENCRYPT('".addslashes($post['password'])."','".ENCRYPT_KEY."'),
									now(),
									'".$activation_id."',
									1
								)";
				$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			}
			return $activation_id;
	}
	/**
	 * @description			:	Fetch company details from database uisng company activation id.
 	 * @param $active_id	: 	Company actication id
	 */
	public function get_company_details($active_id)
	{
		$sql		=	"SELECT *
						 FROM temp_company_signup
						 WHERE activation_id = '".$active_id."'
						";
		$query		=	DB::query(Database::SELECT, $sql);
		$company	=	$query->execute()->as_array();
		return $company;
	}

	/**
	 * @description			:	Fetch company details from database uisng company activation id.
 	 * @param $active_id	: 	Company actication id
	 */
	public function get_company_details_dropbox($active_id)
	{
		$sql		=	"SELECT *
						 FROM temp_company_signup
						 WHERE activation_id = '".$active_id."'
						";
		$query		=	DB::query(Database::SELECT, $sql);
		$company	=	$query->execute()->as_array();
		if(empty($company)) {
			$sql		=	"SELECT *
							 FROM company
							 WHERE activation_id = '".$active_id."'
							";
			$query		=	DB::query(Database::SELECT, $sql);
			$company	=	$query->execute()->as_array();
		}
		return $company;
	}

	/**
	 * @description		:	get dropbox email/password/device name from the user and insert it. 
	 * @param $post		:	Array containing dropbox inputs
	 * @param $edit		:	true/false - if edit form value will be true else false.
	 */
	public function save_dropbox_info($post, $edit=false)
	{
		$sql			=	"SELECT *
							 FROM company as c
							 WHERE c.activation_id = '".addslashes($post['activate_id'])."'";
		$query			=	DB::query(Database::SELECT, $sql);
		$company_user	=	$query->execute()->as_array();
		if(empty($company_user)) {
			$sql		=	"SELECT *
						 FROM temp_company_signup as c
						 WHERE c.activation_id = '".addslashes($post['activate_id'])."'";
			$query		=	DB::query(Database::SELECT, $sql);
			$company_user	=	$query->execute()->as_array();
			if(!empty($company_user)){//rolls
				$company_info	=	$company_user[0];
			} else {
				$company_info	=	NULL;
				return FALSE;
			}
		} else {
			$edit	=	1;	
		}
		
		
		if($edit)
		{
			$this->update_dropbox_tokens_by_company_id($post['device_name'], $_SESSION['oauth_tokens'], $company_user[0]['id']);
		}
		else
		{
			//rolls
			$sql		=	"INSERT INTO company (
								name, serialnumber, billing_name, address, address2, city, state, country, 
								zipcode, phone, created_date, active_date, activation_id, active_status, status_flag
								)
							VALUES (
								'".addslashes(htmlentities(trim($company_info['company_name'])))."',
								'".addslashes(htmlentities(trim($company_info['serialnumber'])))."',
								'".addslashes(htmlentities(trim($company_info['billing_name'])))."',
								'".addslashes(htmlentities(trim($company_info['address'])))."',
								'".addslashes(htmlentities(trim($company_info['address2'])))."',
								'".addslashes(htmlentities(trim($company_info['city'])))."',
								'".addslashes(htmlentities(trim($company_info['state'])))."',
								'".addslashes(htmlentities(trim($company_info['country'])))."',
								'".addslashes(htmlentities(trim($company_info['zipcode'])))."',
								'".addslashes(htmlentities(trim($company_info['phone'])))."',
								'".$company_info['created_date']."',
								now(),
								'".$post['activate_id']."',
								1,
								1
							)";
						
			$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			$company_id	=  $query[0];
			
			//insert company user details
			if(isset($company_info['email'])){
				$company_info['email'] = addslashes(htmlentities(trim(strtolower($company_info['email']))));
			}
			$sql	=	"INSERT INTO admin_users (
								email, password, name, lastname, company_id
							)
						VALUES (
							'".$company_info['email']."',
							'".addslashes($company_info['password'])."',
							'".addslashes(htmlentities(trim($company_info['name'])))."',
							'".addslashes(htmlentities(trim($company_info['last_name'])))."',
							'".$company_id."'
						)";
			$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			$company_admin_id	=  $query[0];
			
			if($company_info['plan'] == 4) {
				$sql	=	"INSERT INTO company_plan(company_id, signup_plan_id)
							 VALUES('".$company_id."', '".$company_info['plan']."')";
				$query  =  $this->_db->query(Database::INSERT, $sql, true);
			} else {
				$sql	=	"INSERT INTO company_plan(company_id, signup_plan_id, service_token)
							 VALUES('".$company_id."', '".$company_info['plan']."', '".$company_info['service_token']."')";
				$query  =  $this->_db->query(Database::INSERT, $sql, true);
			}

			// insert drop-box details with company id
			$sql	=	"INSERT into dropbbox_account
								(device_name, token, secret, admin_user_id, company_id)
						 VALUES (
							'".addslashes($post['device_name'])."',
							AES_ENCRYPT('".addslashes($_SESSION['oauth_tokens']['token'])."','".ENCRYPT_KEY."'),
							AES_ENCRYPT('".addslashes($_SESSION['oauth_tokens']['token_secret'])."','".ENCRYPT_KEY."'),
							'".$company_admin_id."',
							'".$company_id."'
						 )";
			$query  	=  $this->_db->query(Database::INSERT, $sql, true);
			$sql	=	"DELETE FROM temp_company_signup WHERE activation_id  = '".addslashes($post['activate_id'])."'";
			$query  =  $this->_db->query(Database::DELETE, $sql, true); // delete all the users in this company
		}
		return true;
	}
	
	/**
	 * @description	:	check dropbox file structure in dropbox server.
	 */
	public function create_dropbox_file_structure()
	{
		$dropbox_model	=   new Model_dropbox;
		$json_model		=	new Model_Json;
		$data			=	new Model_Data;
		try {
			$account[0]['device_name']	=	$_POST['device_name'];
			$data->define_dropbox_constants($account[0]);
		}catch(Exception $e){
			die($e->getMessage());
		}
		// check each file present or not in dropbox
		try {
			$dropbox_model->get_file(DROPBOXFOLDERPATH."Employees.json");
			try {
				$dropbox_model->get_file(DROPBOX_ACTIVITY_FOLDERPATH."ActivitySlips.json");
			}
			catch(Exception $e){
				$json_model->file_content	=	"";
				$json_model->insert_array(DROPBOX_ACTIVITY_FOLDERPATH."ActivitySlips.json",
										 CACHE_DROPBOXFOLDERPATH."ActivitySlips_".session_id().".json");
			}
			$dropbox_model->get_file(DROPBOXFOLDERPATH."Customers.json"); //fecth file from dropbox
			$dropbox_model->get_file(DROPBOXFOLDERPATH."Vendors.json");
			$dropbox_model->get_file(DROPBOXFOLDERPATH."Activities.json");
		}
		catch(Exception $e)
		{ 
			throw new Exception("Please sync your account from AccountEdge Desktop1");
		}
		return true;
	}
	
	/**
 	 * @description			:	fetch company email/pass and other info from table.
	 * @param $activate_id	:	company activation id
	 */
	public function get_company_by_activity($activate_id)
	{
		$sql	=	"SELECT au.email AS username, AES_DECRYPT( au.password, '".ENCRYPT_KEY."' ) AS password, c.name as company_name, c.id as company_id
					 FROM admin_users AS au, company AS c
					 WHERE c.id = au.company_id
					 AND c.activation_id = '".addslashes($activate_id)."'";
		$query			=	DB::query(Database::SELECT, $sql);
		$company_user	=	$query->execute()->as_array();
		return $company_user;
	}
	
	/**
	 * @description	:	fetch company settings using company auto id
	 * @param $company_id : integer value. (company auto id)
	 */
	public function get_admin_settings($company_id)
	{
		$sql	=	"SELECT c.id as company_id, c.name as company_name, c.serialnumber as serialnumber, c.billing_name as cname, c.address as address, c.city as city
					 , c.state as state, c.country as country, c.zipcode as zipcode, c.phone as phone, c.active_status, c.created_date as date_created, a.name as name,a.lastname as lastname,
					 a.email as UserEmail, AES_DECRYPT( a.password, '".ENCRYPT_KEY."' ) AS password, c.expire_date_modify, c.end_date, 
					 DATE_ADD(c.created_date, INTERVAL 30 DAY) as default_end_date, c.suspend_status, c.suspend_resume_date
					 FROM company as c, admin_users as a
					 WHERE c.id = a.company_id
					 AND c.id = '".$company_id."'";
				//	 die($sql);
		$query			=	DB::query(Database::SELECT, $sql);
		$company_user	=	$query->execute()->as_array();
		if(empty($company_user))
		{
			return null;
		}
		else
		{
			return $company_user[0];
		}
	}
	
	/**
 	 * @description	:	Fetch country list
	 */
	public function get_country_list()
	{
		$sql		=	"SELECT * FROM country_list WHERE 1";
		$query		=	DB::query(Database::SELECT, $sql);
		return $query->execute()->as_array();
	}
	
	/**
	 * @description	:	fetch default company to be viewed when logged in as super admin
	 */
	public function get_company_default()
	{
		$sql	=	"SELECT id 
					 FROM company 
					 WHERE 1 
					 ORDER BY name ASC 
					 LIMIT 0,1";
		$query	=	DB::query(Database::SELECT, $sql);
		$result	=	$query->execute()->as_array();
		if(empty($result))
			return null;
		else
			return $result[0]['id'];
	}
	
	/**
	 * Sync all employee and vendor list from the dropbox.
	 */
	public function sync_users_from_aed()
	{
		$json			=	new Model_Json;
		$dropbox_model	=   new Model_dropbox;
		unset($_SESSION['dropbox']);
		try {
			$json->files_list['Employees']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Employees.json');
		} catch(Exception $e) {
			$json->files_list['Employees']	=	"";
		}
		try {
			$json->files_list['Contracts']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Vendors.json');
		} catch(Exception $e) {
			$json->files_list['Contracts']	=	"";
		}
		$json->filter_json_array();
		$sql	=	"INSERT INTO sync_users_log (company_id, date)
					 VALUES ('".$_SESSION['company_id']."', now())";
		$query  =  $this->_db->query(Database::INSERT, $sql, true);
		return true;
	}
	
	public function get_current_plan($company_id="")
	{
		if(empty($company_id)) {
			$id	=	$_SESSION['company_id'];
		} else {
			$id	=	$company_id;
		}
		$sql	=	"SELECT cp.signup_plan_id as plan_id, sp.name as plan_name, sp.price as plan_price
					 FROM company_plan as cp
					 LEFT JOIN signup_plan as sp
					 ON cp.signup_plan_id = sp.plan_id
					 WHERE cp.company_id = '".$id."'";
		$query	=	DB::query(Database::SELECT, $sql);
		$result	=	$query->execute()->as_array();
		if(empty($result)) { 
			return false;
		}
		return $result[0];
	}
	
	public function get_company_email()
	{
		$sql	=	"SELECT au.email as email, c.name as name
					FROM admin_users as au
					LEFT JOIN company as c
					ON au.company_id = c.id
					WHERE au.company_id = '".$_SESSION['company_id']."'";
		$query	=	DB::query(Database::SELECT, $sql);
		$result	=	$query->execute()->as_array();
		return $result[0];
	}
	
	public function is_user_exceeds_limit()
	{
		$sql	=	"SELECT c.signup_plan_id, spu.user_limit, count(d.id) as total_users
					FROM company_plan as c 
					LEFT JOIN signup_plan as s 
					ON c.signup_plan_id = s.plan_id
					LEFT JOIN signup_plan_user_limit spu
					ON s.plan_id = spu.plan_id
					LEFT JOIN dharma_users as d
					ON c.company_id = d.company_id
					WHERE c.company_id = '".$_SESSION['company_id']."'
					GROUP BY d.company_id";
		$query	=	DB::query(Database::SELECT, $sql);
		$data	=	$query->execute()->as_array();
		
		if(empty($data)) return false;
		if($data[0]['user_limit'] == "*") return false;
		elseif($data[0]['total_users'] > $data[0]['user_limit']) {
			$this->total_users		=	$data[0]['total_users'];
			$this->plan_max_users	=	$data[0]['user_limit'];
			return true;
		}
		else return false;
	}
	
	/*
	public function deactivate_users()
		{
			$num_more_user	=	$this->total_users-$this->plan_max_users;
			
			$sql			=	"SELECT *
								 FROM dharma_users
								 WHERE company_id = '".$_SESSION['company_id']."'
								 ORDER BY first_name DESC
								 LIMIT 0, $num_more_user";
			$result			=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			foreach($result as $user) {
				$sql		=	"UPDATE dharma_users SET status = 0 WHERE id='".$user['id']."'";
				$query  	=    $this->_db->query(Database::INSERT, $sql, true);
			}
			return true;
			
		}*/
	
	
	public function get_dropbox_info()
	{
		$sql	=	"SELECT device_name, email, AES_DECRYPT(`password`, '".ENCRYPT_KEY."') as password
					 FROM dropbbox_account
					 WHERE company_id = '".$_SESSION['company_id']."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result[0];
	}
	
	public function update_dropbox($post)
	{
		$this->update_dropbox_tokens_by_company_id($post['device_name'], $_SESSION['oauth_tokens'], $_SESSION['company_id']);
		$_SESSION['new_dropbox_device_name']	=	$post['device_name'];
		return true;
	}
	
	public function get_service_token($company_id)
	{
		$sql	=	"SELECT service_token
					 FROM company_plan
					 WHERE company_id = '".$company_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result[0]['service_token'];
	}
	
	public function get_offline_company_info($id)
	{
		$sql	=	"SELECT c.id, c.id as company_id, c.company_name, c.serialnumber , c.billing_name as cname, c.address,
					c.city, c.state, c.country , c.zipcode , c.phone, c.created_date as date_created,
					c.name as name,c.last_name as lastname, c.email as UserEmail, AES_DECRYPT( c.password, '".ENCRYPT_KEY."' ) AS password, 0 as active_status,
					DATE_ADD(c.created_date, INTERVAL 30 DAY) as end_date
					FROM temp_company_signup as c
					WHERE id = '".addslashes($id)."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();

		if(!empty($result)) {
			return $result[0];
		} else {
			return false;
		}
	}
	
	public function delete_offline_company($id) 
	{
		$sql	=	"DELETE FROM temp_company_signup
					 WHERE id = '".addslashes($id)."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		return true;
	}
	
	public function get_offline_company_name($id)
	{
		$sql	=	"SELECT id, company_name as name
					 FROM temp_company_signup
					 WHERE id = '".addslashes($id)."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result[0];
	}
	
	public function get_company_plan_by_id($id)
	{	
		$sql	=	"SELECT s.price, s.name
					 FROM company_plan as c
					 LEFT JOIN signup_plan as s
					 ON s.plan_id = c.signup_plan_id
					 WHERE c.company_id = '".$id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) {
			$free_plan_id	=	$this->get_free_plan_id();
			$sql	=	"INSERT INTO company_plan (company_id, signup_plan_id)
						 VALUES('".$_SESSION['company_id']."','".$free_plan_id."')";
			$query  =  $this->_db->query(Database::INSERT, $sql, true);
			$this->get_company_plan_by_id($_SESSION['company_id']);
		} else {
			return $result[0];
		}
	}
	public function get_free_plan_id()
	{
		$sql	=	"SELECT plan_id
					 FROM signup_plan
					 WHERE price = 0";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result[0])) {
			return $result[0]['plan_id'];
		} else {
			return 0;
		}
	}
	
	public function get_user_offline_info($post)
	{
		$sql	=	"SELECT c.service_token
					 FROM admin_users as a
					 LEFT JOIN company_plan as c
					 ON a.company_id = c.company_id
					 LEFT JOIN company as cm
					 ON cm.id = a.company_id
					 WHERE a.email = '".addslashes($_POST['UserEmail'])."'
					 AND a.password = AES_ENCRYPT('".addslashes($_POST['password'])."','".ENCRYPT_KEY."')
					 AND status_flag = 0";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	public function change_status($userid, $current_status)
	{
		$employee_m = new Model_Employee;
		$vendor_m = new Model_Vendor;
		
		$dharma_user = DB::select()->from('dharma_users')->where('id', '=', $userid)->execute()->as_array();
		
		if($current_status == 1) {
			$sql	=	"UPDATE dharma_users
						 SET status = 0
						 WHERE id = '".$userid."'";
						 
			// de-activating employee
			$db_key_value['active_status']=0; 
			if(isset($dharma_user[0]) && $dharma_user[0]['type']=='Employee'){
				$employee_m->update_employee_by_key($_SESSION['company_id'], $dharma_user[0]['record_id'], $db_key_value);
			} else if(isset($dharma_user[0]) && $dharma_user[0]['type']=='Vendor'){
				$vendor_m->update_vendor_by_key($_SESSION['company_id'], $dharma_user[0]['record_id'], $db_key_value);
			}
		} else {
			// get user information.
			$sql		=	"SELECT count(id) as total_users
							 FROM dharma_users
							 WHERE company_id = '".$_SESSION['company_id']."'
							 AND status = 1";
			$result_users	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			$sql	=	"SELECT s.user_limit
						 FROM company_plan as c
						 LEFT JOIN signup_plan_user_limit as s
						 ON c.signup_plan_id = s.plan_id
						 WHERE c.company_id = '".$_SESSION['company_id']."'";
			$user_limit		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		
			if($result_users[0]['total_users'] >= $user_limit[0]['user_limit']) {
				return false;
			}
			$sql	=	"UPDATE dharma_users
						 SET status = 1
						 WHERE id = '".$userid."'";
			// Activating employee
			$db_key_value['active_status']=1; 
			if(isset($dharma_user[0]) && $dharma_user[0]['type']=='Employee'){
				echo 'I am here'; 
				$employee_m->update_employee_by_key($_SESSION['company_id'], $dharma_user[0]['record_id'], $db_key_value);
			} else if(isset($dharma_user[0]) && $dharma_user[0]['type']=='Vendor'){
				$vendor_m->update_vendor_by_key($_SESSION['company_id'], $dharma_user[0]['record_id'], $db_key_value);
			}
			
		}
		
		$result	=	$this->_db->query(Database::INSERT, $sql, true);
		return true;
	}
	
	public function check_password($password)
	{
		$sql	=	"SELECT id
					 FROM admin_users
					 WHERE company_id = '".$_SESSION['company_id']."'
					 AND password = AES_ENCRYPT('".addslashes($password)."','".ENCRYPT_KEY."')";
		
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) {
			return false;
		} else {
			return true;
		}
	}
	
	public function reset_company()
	{
		$this->reset_users();
		$this->reset_activity_slips();
		//$this->reset_dropbox_files();
	}
	
	private function reset_users()
	{
		$sql	=	"DELETE 
					 FROM dharma_users
					 WHERE company_id = '".$_SESSION['company_id']."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		return true;
	}
	
	private function reset_activity_slips()
	{
		$sql	=	"DELETE 
					 FROM activity_slip_lists
					 WHERE company_id = '".$_SESSION['company_id']."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		return true;
	}
	
	public function check_downgrade()
	{
		$result		=	array();
		$new_plan	=	$_POST['changed_plan'];
		$cur_plan	=	$this->get_company_plan_by_id($_SESSION['company_id']);
		$sql		=	"SELECT s.price, s.name, sp.user_limit
						 FROM signup_plan as s
						 LEFT JOIN signup_plan_user_limit as sp
						 ON s.plan_id = sp.plan_id
						 WHERE s.plan_id = '".addslashes($new_plan)."'";
		$new_info	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		
		if($cur_plan['price'] == 0) {
			$sql		=	"SELECT count(id) as total_users 
							FROM dharma_users
							WHERE company_id = '".$_SESSION['company_id']."'
						 	AND status = 1";
			$user_info	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			if($user_info[0]['total_users'] > $new_info[0]['user_limit']) {
				$result[0]['success']		=	0;
				$result[0]['total_users']	=	$user_info[0]['total_users'];
				$result[0]['user_limit']	=	$new_info[0]['user_limit'];
				$result[0]['message']		=	"You are changing plans, please select the ".$result[0]['user_limit']." user(s) you wish to remain active.";
				//$result[0]['message']	=	"You want to down-grade from ".$cur_plan['name']." to ".$new_info[0]['name'];
				//$result[0]['message']  .=  	" Warning: number of active users has exceeded the limit of ".$new_info[0]['user_limit']." people. Please choose ".$result[0]['user_limit']." users you would like to keep active.";
			} else {
				$result[0]['success']	=	1;
				$result[0]['message']	=	"Please confirm you want to upgrade from ".$cur_plan['name']." to ".$new_info[0]['name'];
				//$result[0]['message']	=	"You want to down-grade from ".$cur_plan['name']." to ".$new_info[0]['name'];
				//$result[0]['message']  .=  	" It allows you to have ".$new_info[0]['user_limit']." active users. Are you sure you want to down-grade?";
			}
			$result[0]['upgrade']	=	1;
		} else if($cur_plan['price'] < $new_info[0]['price']) {
			$result[0]['success']	=	1;
			$result[0]['upgrade']	=	1;
			$result[0]['cur_plan']	=	$cur_plan['name'];
			$result[0]['message']	=	"Please confirm you want to upgrade from ".$cur_plan['name']." to ".$new_info[0]['name'];
			
		} else {
			$sql		=	"SELECT count(id) as total_users 
							FROM dharma_users
							WHERE company_id = '".$_SESSION['company_id']."'
							AND status = 1";
			$user_info	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			if($user_info[0]['total_users'] > $new_info[0]['user_limit']) {
				$result[0]['success']		=	0;
				$result[0]['total_users']	=	$user_info[0]['total_users'];
				$result[0]['user_limit']	=	$new_info[0]['user_limit'];
				$result[0]['message']		=	"You are changing plans, please select the ".$result[0]['user_limit']." user(s) you wish to remain active.";
				
			} else {
				$result[0]['success']	=	1;
				$result[0]['message']	=	"Please confirm you want to downgrade from ".$cur_plan['name']." to ".$new_info[0]['name'];
				
			}
			$result[0]['upgrade']	=	0;
			
		}
		echo json_encode($result);die;
	}
	
	public function get_plan_details($company_id = ""){
		$sql	=	"SELECT p.name, c.signup_plan_id from company_plan c
					 LEFT JOIN signup_plan p ON (c.signup_plan_id	=	p.plan_id)
					 WHERE company_id = '".$company_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result)){
			return strtolower($result[0]['name']); 
		}
	}
	
	public function get_plan_user_limit($company_id = "")
	{
		if(empty($company_id)) {
			$id = $_SESSION['company_id'];
		} else {
			$id = $company_id;
		}
		$sql	=	"SELECT s.user_limit
					 FROM company_plan as c
					 LEFT JOIN signup_plan_user_limit as s
					 ON c.signup_plan_id = s.plan_id
					 WHERE c.company_id = '".$id."'";
			
		$user_limit		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($user_limit)) return 0;
		return $user_limit;
	}
	
	public function get_last_synced_date()
	{
		$sql	=	"SELECT date
					FROM sync_users_log
					WHERE company_id = '".$_SESSION['company_id']."'
					ORDER BY date DESC
					LIMIT 0,1";
		$result =	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) {
			$sql	=	"SELECT active_date as date
						 FROM company
						 WHERE id = '".$_SESSION['company_id']."'";
			$result =	DB::query(Database::SELECT, $sql)->execute()->as_array();
		}
		return $result[0]['date'];
	}
	
	public function get_company_user_info($company_id = "")
	{
		if(empty($company_id)) {
			$id = $_SESSION['company_id'];
		} else {
			$id = $company_id;
		}
		$sql	=	"SELECT count(id) as active_usrs
					 FROM dharma_users
					 WHERE company_id = '".$id."'
					 AND status = 1";
		$result =	DB::query(Database::SELECT, $sql)->execute()->as_array();
		$user_info['active_users'] 	=	$result[0]['active_usrs'];
		
		$sql	=	"SELECT count(id) as inactive_usrs
					 FROM dharma_users
					 WHERE company_id = '".$id."'
					 AND status = 0";
		$result =	DB::query(Database::SELECT, $sql)->execute()->as_array();
		$user_info['inactive_users'] 	=	$result[0]['inactive_usrs'];

		$plan_user_limit				=	$this->get_plan_user_limit($id);//$user_info['active_users']+$user_info['inactive_users'];
		$user_info['total_users']		=	$plan_user_limit[0]['user_limit']-$user_info['active_users'];
		return $user_info;
	}
	
	public function get_company_payment_info()
	{
		$xmlrpc			=	new Model_Xmlrpc;
		//$token			=	$this->get_service_token($_SESSION['company_id']);
		//if(empty($token)) return null;
		try {
			$card_info		=	$xmlrpc->get_card_details();
		} catch(Exception $e) {
			return null;
		}

		return $card_info;
	}
	
	public function get_new_company_info()
	{
		if(isset($_POST['search'])) {
			$sql	=	"SELECT s.price, s.name, sp.user_limit
						 FROM signup_plan as s
						 LEFT JOIN signup_plan_user_limit as sp
						 ON s.plan_id = sp.plan_id
						 WHERE s.plan_id = '".addslashes($_POST['selected_new_plan'])."'";	
			
			$result =	DB::query(Database::SELECT, $sql)->execute()->as_array();
			return $result[0];
		} else {
			return null;
		}
	}
	
	public function get_company_user_limit($plan_id)
	{
			$sql	=	"SELECT s.user_limit
						 FROM signup_plan_user_limit as s
						 WHERE s.plan_id = '".$plan_id."'";
		//	echo $sql;
			
			$user_limit		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			return $user_limit;
	}
	
	public function get_company_search_list()
	{
		if(isset($_POST['search'])) {
			$query	=	"(SELECT c.id, c.name as name, 1 as status, c.serialnumber, c.city, cl.country, c.created_date as create_date, a.email, s.name as service_plan
						 FROM company as c
						 INNER JOIN admin_users as a
						 ON c.id = a.company_id
						 LEFT JOIN country_list as cl
						 ON c.country = cl.id
						 INNER JOIN company_plan as cp
						 ON c.id = cp.company_id
						 INNER JOIN signup_plan as s
						 ON cp.signup_plan_id = s.plan_id 	
						 WHERE 
						 	c.status_flag = 1 AND
						 	(
						 		c.name LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.serialnumber LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.address LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.address2 LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.city LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.state LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.phone LIKE '%".addslashes($_POST['search'])."%' ||
						 		a.email LIKE '%".addslashes($_POST['search'])."%' ||
						 		a.name LIKE '%".addslashes($_POST['search'])."%' ||
						 		a.lastname LIKE '%".addslashes($_POST['search'])."%'
						 	)
						 )";
			$query				=	DB::query(Database::SELECT, $query);
			$company_data		=	$query->execute()->as_array();
			$query	=	"
						 SELECT c.id, c.company_name as name, 2 as status, c.serialnumber, c.city, cl.country, 
						 c.created_date as create_date, c.email, s.name as service_plan
						 FROM temp_company_signup as c
						 INNER JOIN signup_plan as s
						 ON c.plan = s.plan_id
						 LEFT JOIN country_list as cl
						 ON c.country = cl.id
						 WHERE (
						 		c.company_name LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.serialnumber LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.address LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.address2 LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.city LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.state LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.phone LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.email LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.name LIKE '%".addslashes($_POST['search'])."%' ||
						 		c.last_name LIKE '%".addslashes($_POST['search'])."%' 
						 	)
						 AND c.status = '1'
						 ";
			$query				=	DB::query(Database::SELECT, $query);
			$temp_data			=	$query->execute()->as_array();
			
		} else {
			$query	=	"SELECT c.id, c.name as name, 1 as status, c.serialnumber, c.city, cl.country, c.created_date as create_date, a.email, s.name as service_plan
						 FROM company as c
						 INNER JOIN admin_users as a
						 ON c.id = a.company_id
						 LEFT JOIN country_list as cl
						 ON c.country = cl.id
						 INNER JOIN company_plan as cp
						 ON c.id = cp.company_id
						 INNER JOIN signup_plan as s
						 ON cp.signup_plan_id = s.plan_id 	
						 WHERE c.status_flag = 1";
		
			$query				=	DB::query(Database::SELECT, $query);
			$company_data		=	$query->execute()->as_array();
			
			$query	=		"
							 SELECT c.id, c.company_name as name, 2 as status, c.serialnumber, c.city, cl.country, 
							 c.created_date as create_date, c.email, s.name as service_plan
							 FROM temp_company_signup as c
							 INNER JOIN signup_plan as s
							 ON c.plan = s.plan_id
							 LEFT JOIN country_list as cl
							 ON c.country = cl.id
							 WHERE c.status = '1'
						 ";
			$query				=	DB::query(Database::SELECT, $query);
			$temp_data		=	$query->execute()->as_array();
		}
		$data		=	array_merge($company_data, $temp_data);
		return $data;
	}
	
	public function export_company_details()
	{
		if(isset($_POST['export_search']) && $_POST['export_search'] != "" && $_POST['export_search'] != "Search...") {
			$query	=	"(SELECT c.id, c.name as name, 1 as status, c.serialnumber, c.city, cl.country, 
						c.state, c.zipcode, c.phone, c.address as street1, c.address2 as street2, c.created_date as create_date, a.email, s.name as service_plan, s.price,
						db.device_name, db.email as dropbox_email
						 FROM company as c
						 INNER JOIN admin_users as a
						 ON c.id = a.company_id
						 LEFT JOIN country_list as cl
						 ON c.country = cl.id
						 INNER JOIN company_plan as cp
						 ON c.id = cp.company_id
						 INNER JOIN signup_plan as s
						 ON cp.signup_plan_id = s.plan_id 
						 LEFT JOIN dropbbox_account as db
						 ON c.id = db.company_id
						 WHERE 
						 	c.status_flag = 1 AND
						 	(
						 		c.name LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.serialnumber LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.address LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.address2 LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.city LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.state LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.phone LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		a.email LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		a.name LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		a.lastname LIKE '%".addslashes($_POST['export_search'])."%'
						 	)
						 
						 )";
			$query				=	DB::query(Database::SELECT, $query);
			$company_data		=	$query->execute()->as_array();
			$query	=	"
						 SELECT c.id, c.company_name as name, 2 as status, c.serialnumber, c.city, cl.country, 
						 c.state, c.zipcode, c.phone, c.address as street1, c.address2 as street2, c.created_date as create_date, c.email, s.name as service_plan, s.price,1,1
						 FROM temp_company_signup as c
						 INNER JOIN signup_plan as s
						 ON c.plan = s.plan_id
						 LEFT JOIN country_list as cl
						 ON c.country = cl.id
						 WHERE (
						 		c.company_name LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.serialnumber LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.address LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.address2 LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.city LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.state LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.phone LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.email LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.name LIKE '%".addslashes($_POST['export_search'])."%' ||
						 		c.last_name LIKE '%".addslashes($_POST['export_search'])."%' 
						 	)
						 ";
			$query				=	DB::query(Database::SELECT, $query);
			$temp_data			=	$query->execute()->as_array();
		} else {
			if(isset($_POST['export_users_date'])) {
				$arr_date	=	explode("/",addslashes($_POST['export_users_date']));
				$start_date	=	$arr_date[2]."-".$arr_date[0]."-".$arr_date[1]." 00:00:00";	
				$end_date	=	$arr_date[2]."-".$arr_date[0]."-".$arr_date[1]." 23:59:59";
				$date_query	=	" AND created_date BETWEEN '".$start_date."' AND  '".$end_date."'";
			} else {
				$date_query	=	"";
			}
			$query	=	"(
							SELECT c.id, c.name as name, 1 as status, c.serialnumber, c.city, cl.country, 
							c.state, c.zipcode, c.phone, c.address as street1, c.address2 as street2, c.created_date as create_date, a.email, s.name as service_plan, s.price,
							db.device_name, db.email as dropbox_email
							 FROM company as c
							 INNER JOIN admin_users as a
							 ON c.id = a.company_id
							 LEFT JOIN country_list as cl
							 ON c.country = cl.id
							 INNER JOIN company_plan as cp
							 ON c.id = cp.company_id
							 INNER JOIN signup_plan as s
							 ON cp.signup_plan_id = s.plan_id 
							 LEFT JOIN dropbbox_account as db
							 ON c.id = db.company_id
							 WHERE c.status_flag = 1
							 $date_query
						 )";
			$query				=	DB::query(Database::SELECT, $query);
			$company_data		=	$query->execute()->as_array();
			
			$query	=		"
							 SELECT c.id, c.company_name as name, 2 as status, c.serialnumber, c.city, cl.country, 
							 c.state, c.zipcode, c.phone, c.address as street1, c.address2 as street2, c.created_date as create_date, c.email, s.name as service_plan, s.price,1,1
							 FROM temp_company_signup as c
							 INNER JOIN signup_plan as s
							 ON c.plan = s.plan_id
							 LEFT JOIN country_list as cl
							 ON c.country = cl.id
							 WHERE 1=1
							 $date_query
						 ";
			$query				=	DB::query(Database::SELECT, $query);
			$temp_data		=	$query->execute()->as_array();
		}
		
		$data		=	array_merge($company_data, $temp_data);
		/*$query		=	DB::query(Database::SELECT, $query);
		$data		=	$query->execute()->as_array();*/
		return $data;
	}
	
	public function export_to_excel($header, $data)
	{
		$filename 	= 	"TimeTracker_Company_List_" . date('Ymd') . ".xls"; 
		header("Content-Disposition: attachment; filename=\"$filename\""); 
		header("Content-Type: application/vnd.ms-excel"); 
		$flag 		=	false;
		$head_count	=	count($header);
		$content	=	"";
		$content   .=   implode("\t", $header);
		$content   .=	"\n";
		
		foreach($data as $row) { 
			$content	.=	$this->cleanData(date("M-d-Y", strtotime($row[0])));
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[1]);
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[2]);
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[3]);
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[4]);
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[5]);
			
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[6]);
			
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[7]);
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[8]);
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[9]);
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[10]);
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[11]);

			$content	.=	"\t";
			$content	.=	$this->cleanData($row[12]);
			
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[13]);
			
			$content	.=	"\t";
			$content	.=	$this->cleanData($row[14]);
			
			$content	.=	"\n";
		}
		echo $content;
		die;
	}
	
	private function cleanData($str) 
	{
		$str = preg_replace("/\t/", "\\t", $str); 
		$str = preg_replace("/\r?\n/", "\\n", $str); 
		if(strstr($str, '"')) 
			$str = '"' . str_replace('"', '""', $str) . '"';
		return $str; 
	} 
	
	public function get_signup_email_content($post, $activation_link, $free=null)
	{
		if(!empty($free)) {
			$user_name	=	$_POST['company_name'];
		} else {
			$user_name	=	$_POST['name'];
		}
		$content = '
				<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<title>Cloud for AccountEdge - Admin</title>
<style type="text/css">

</style>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#EEEEEE" >

<STYLE>
 .headerTop { background-color:#EEEEEE; border-top:0px solid #000000; border-bottom:0px solid #FFCC66; text-align:right; }
 .adminText { font-size:10px; color:#FFFFCC; line-height:200%; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; text-decoration:none; }
 .headerBar { background-color:#FFFFFF; border-top:0px solid #FFFFFF; border-bottom:0px solid #333333; }
 .title { font-size:22px; font-weight:bold; color:#336600; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; line-height:110%; }
 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; }
 td { font-size:12px; color:#000000; line-height:150%; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; }
 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
 .footerText { font-size:10px; color:#333333; line-height:100%; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; }
 a { color:#0177d0; }
</STYLE>

	<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" bgcolor="#EEEEEE" >
		<tr>
			<td valign="top" align="center">

				<table width="550" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="2" align="center" style="background-color:#EEEEEE;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:right;">
							<div align="left">
								<div align="right">
									<span style="font-size:10px;color:#000000;line-height:200%;text-decoration:none;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">
										<a href="http://www.acclivitysoftware.com/" style="color:#0983d1;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;"><img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/timetracker.png" alt="Time Tracker" width="110" height="24" border="0" align="left"></a>.
									</span>
								</div>
							</div>
						</td>
					</tr>

					<tr>
						<td colspan="2" align="center" style="background-color:#EEEEEE;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:right;">&nbsp;</td>
					</tr>

					</table>

				<table width="600" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #fff; border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;">
					<tr>
						<td valign="top" bgcolor="#FFFFFF" style="font-size:12px;color:#000000;line-height:150%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">

							<table width="550" border="0" align="center" cellpadding="5" cellspacing="0">
								<tr>
									<td align="center" valign="middle" bgcolor="#FFFFFF" style="font-size:12px;color:#000000;line-height:200%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">
										<table width="550" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" align="center">
												<img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/hello.jpg" alt="Hello and Welcome" width="300" height="100" border="0" align="center">
												</td>
												<tr>
													<td height="50" valign="top" width="550" colspan="2">
														<img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/breaker.jpg" height="20" alt="" style="border: 0;" width="550" align="center" />
													</td>
												</tr>
											</tr>
											<tr>
												<td colspan="3" style="font-size:12px;color:#000000;line-height:200%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">
													<div align="left">Dear '.$user_name.',<br><br>Welcome to AccountEdge Cloud. This email includes important details about your account, so please hang on to it.<br><br> 1. 
													To get started, you\'ll need to configure AccountEdge and AccountEdge Cloud to connect to your Dropbox account. Get set up with a Dropbox account by going to
													 <a href="http://www.dropbox.com/" style="color:#0983d1"> Dropbox.com</a>. It\'s fast, it\'s easy and it\'s free.<br><br>2. Next, open AccountEdge and go to Setup > AccountEdge Cloud > Manage AccountEdge Cloud. Enter your Dropbox account info, then tab to link your company file Dropbox. 
													 Then create a Device that will be used by AccountEdge Cloud to access your AccountEdge data in Dropbox. A folder with this name will be created inside your AccountEdge folder on Dropbox.<br><br>3. Almost done. After you\'re set up with Dropbox and AccountEdge, click this <a href="'.$activation_link.'" style="color:#0983d1">'.$activation_link.'</a> and follow the directions. This is where you connect Time Tracker to Dropbox. <strong>Complete this step prior to logging into Time Tracker as an Admin for the first time</strong>.<br><br>4. Once the steps above are completed, you can log into Time Tracker as your company Admin using this link (we suggest that you bookmark this link for easier access in the future). <a href="'.SITEURL.'/admin" style="color:#0983d1"> '.SITEURL.'/admin</a> <br><br><span style="background-color:#ffff00">
													 Your Admin log in is: '.$post['UserEmail'].'<br></span><br><br>Please keep this email for your records. This user information is for your company Admin. Each employee or vendor you set up as a user will be sent their own unique log in credentials, including you if you will be both user and Admin.<br><br>That\'s it. If you need help, check out our online <a href="http://www.accountedge.com/timetrackerhelp/" style="color:#0983d1"> support center</a> for <a href="http://support.accountedge.com/kb/time-tracker" style="color:#0983d1"> FAQs</a>, the <a href="http://support.accountedge.com/discussions/time-tracker" style="color:#0983d1"> user forum</a> and <a href="http://www.accountedge.com/timetrackerhelp/" style="color:#0983d1"> tutorial videos</a>. If you have any additional questions, feel free to <a href="http://accountedge.com/help/" target="_blank" style="color:#0983d1"> contact us</a>.<br><br>Kind regards,<br>AccountEdge Cloud Team </div>
												</td>
											</tr>


        
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="600" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #eeeeee;">
					<tr>
						<td align="left" valign="top">
							<div align="center">
								<span style="font-size:9px;color:#333333;line-height:150%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">&copy;2011 Acclivity LLC. Check out our <a href="http://acclivity.tumblr.com/" style="color:#0983d1;"><strong>company blog</strong></a> and <a href="http://twitter.com/accountedge" style="color:#0983d1;"><strong>follow us</strong></a> on Twitter. <br>
									<tr>
										<td colspan="2" align="center" style="background-color:#EEEEEE;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:center;">
											<a href="http://twitter.com/accountedge" style="color:#0983d1;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;"><img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/twitter-follow.png" alt="Follow us" width="29" height="29" border="0"></a><a href="http://acclivity.tumblr.com/rss" style="color:#0983d1;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;"><img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/rss-follow.png" alt="Read our blog" width="29" height="29" border="0"></a>
										</td>
									</tr>
								</span>
							</div>
						</td>
					</tr>
				</table>
			</tr>
		</table>
</body>
</html>
		';
		return $content;
		
	}
	
	private function get_user_active_mail($post, $company, $active_id)
	{
		if($company['user_name'] == "") {
			$company_name	=	$company['email'];
		} else {
			//$name	=	$company['user_name'];
			$company_name   =   $company['user_name'].' at '.$company['company_name'];
		}
		$content	=	'
						<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">

<html>
	<title>AccountEdge Cloud for AccountEdge - Employee/Vendor</title>
<style type="text/css">
<!--
.style1 {font-family: "Lucida Grande", "Lucida Sans Unicode", Arial, Helvetica, sans-serif}
-->
</style>

<body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" bgcolor="#EEEEEE" >

<STYLE>
 .headerTop { background-color:#EEEEEE; border-top:0px solid #000000; border-bottom:0px solid #FFCC66; text-align:right; }
 .adminText { font-size:10px; color:#FFFFCC; line-height:200%; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; text-decoration:none; }
 .headerBar { background-color:#FFFFFF; border-top:0px solid #FFFFFF; border-bottom:0px solid #333333; }
 .title { font-size:22px; font-weight:bold; color:#336600; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; line-height:110%; }
 .subTitle { font-size:11px; font-weight:normal; color:#666666; font-style:italic; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; }
 td { font-size:12px; color:#000000; line-height:150%; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; }
 .footerRow { background-color:#FFFFCC; border-top:10px solid #FFFFFF; }
 .footerText { font-size:10px; color:#333333; line-height:100%; font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; }
 a { color:#0177d0; }
</STYLE>

	<table width="100%" cellpadding="10" cellspacing="0" class="backgroundTable" bgcolor="#EEEEEE" >
		<tr>
			<td valign="top" align="center">

				<table width="550" cellpadding="0" cellspacing="0">
					<tr>
						<td colspan="2" align="center" style="background-color:#EEEEEE;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:right;">
							<div align="left">
								<div align="right">
									<span style="font-size:10px;color:#000000;line-height:200%;text-decoration:none;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">
										<a href="http://www.acclivitysoftware.com/" style="color:#0983d1;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;"><img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/timetracker.png" alt="Time Tracker" width="110" height="24" border="0" align="left"></a>.
									</span>
								</div>
							</div>
						</td>
					</tr>

					<tr>
						<td colspan="2" align="center" style="background-color:#EEEEEE;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:right;">&nbsp;</td>
					</tr>

					</table>

				<table width="600" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #fff; border-right: 1px solid #ccc; border-left: 1px solid #ccc; border-top: 1px solid #ccc; border-bottom: 1px solid #ccc;">
					<tr>
						<td valign="top" bgcolor="#FFFFFF" style="font-size:12px;color:#000000;line-height:150%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">

							<table width="550" border="0" align="center" cellpadding="5" cellspacing="0">
								<tr>
									<td align="center" valign="middle" bgcolor="#FFFFFF" style="font-size:12px;color:#000000;line-height:200%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">
										<table width="550" border="0" cellspacing="0" cellpadding="0">
											<tr>
												<td valign="top" align="center">
												<img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/hello.jpg" alt="Hello and Welcome" width="300" height="100" border="0" align="center">
												</td>
												<tr>
													<td height="50" valign="top" width="550" colspan="2">
														<img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/breaker.jpg" height="20" alt="" style="border: 0;" width="550" align="center" />
													</td>
												</tr>
											</tr>
											<tr>
												<td colspan="3" style="font-size:12px;color:#000000;line-height:200%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">
													<div align="left">Dear '.$post['FirstName'].',<br><br>'.$company_name.' has provided you with a AccountEdge Cloud user account to record your activity slips and timesheets.<br><br>Your log in details are as follows:<br><br><span style="background-color:#ffff00">Your log in is: '.$post['Email'].'<br>Your temporary password is: '.$post['Password'].'</span><br><br>Please click this link to change password and start entering your activity: <a href="'.SITEURL.'/admin/resetpassword/'.$active_id.'" style="color:#0983d1">'.SITEURL.'/admin/resetpassword/'.$active_id.'</a><br><br>Please keep this email for your records. If you need help, check out our online <a href="http://www.accountedge.com/timetrackerhelp/" style="color:#0983d1"> support center</a> for <a href="http://support.accountedge.com/kb/time-tracker" style="color:#0983d1"> FAQs</a>, the <a href="http://support.accountedge.com/discussions/time-tracker" style="color:#0983d1"> user forum</a> and <a href="http://www.accountedge.com/timetrackerhelp/" style="color:#0983d1"> tutorial videos</a>. If you have any additional questions, feel free to <a href="http://accountedge.com/help/" style="color:#0983d1" target="_blank"> contact us</a>.<br><br>Kind regards,<br>AccountEdge Cloud Team </div>
												</td>
											</tr>


        
										</table>
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<table width="600" cellspacing="0" cellpadding="0" border="0" align="center" style="background: #eeeeee;">
					<tr>
						<td align="left" valign="top">
							<div align="center">
								<span style="font-size:9px;color:#333333;line-height:150%;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;">&copy;2011 Acclivity LLC. Check out our <a href="http://acclivity.tumblr.com/" style="color:#0983d1;"><strong>company blog</strong></a> and <a href="http://twitter.com/accountedge" style="color:#0983d1;"><strong>follow us</strong></a> on Twitter. <br>
									<tr>
										<td colspan="2" align="center" style="background-color:#EEEEEE;border-top:0px solid #000000;border-bottom:0px solid #FFCC66;text-align:center;">
											<a href="http://twitter.com/accountedge" style="color:#0983d1;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;"><img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/twitter-follow.png" alt="Follow us" width="29" height="29" border="0"></a><a href="http://acclivity.tumblr.com/rss" style="color:#0983d1;font-family:Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif;"><img src="http://promos.acclivitysoftware.com/promo/support_center_notices/timetracker/rss-follow.png" alt="Read our blog" width="29" height="29" border="0"></a>
										</td>
									</tr>
								</span>
							</div>
						</td>
					</tr>
				</table>
			</tr>
		</table>
</body>
</html>
						';
		return $content;
	}

	public function save_api_key($api)
	{
		$sql	=	"UPDATE superadmin_user
					 SET Service_API = '".$api."'
					 WHERE 1=1";
		$result	=	$this->_db->query(Database::INSERT, $sql, true);
		return true;
	}

	public function get_full_details($company_id)
	{
		$sql	=	"SELECT c.serialnumber, a.email, a.name as firstname, a.lastname
					 FROM company as c
					 LEFT JOIN admin_users as a
					 ON c.id = a.company_id
					 WHERE c.id = '".$company_id."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $data[0];
	}

	public function get_offline_company_plan($id)
	{
		$sql	=	"SELECT s.name as plan_name, s.price as plan_price
					 FROM temp_company_signup as t
					 LEFT JOIN signup_plan as s
					 ON t.plan = s.plan_id
					 WHERE t.id = '".addslashes($id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $data[0];
	}

	public function modify_expire_date($post)
	{
		$more_day	=	$_POST['date_to_add'];
		$company_id	=	$_POST['company_id'];
		$sql		=	"SELECT DATE_ADD(created_date, INTERVAL 30 DAY) as default_end_date, end_date, expire_date_modify
						 FROM company
						 WHERE id = '".$company_id."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if($result[0]['expire_date_modify'] == 1) {
			$current_end_date	=	$result[0]['end_date'];
		} else {
			$current_end_date	=	$result[0]['default_end_date'];
		}

		$sql		=	"UPDATE company
					 	 SET end_date = DATE_ADD('".$current_end_date."', INTERVAL ".$more_day." DAY),
					 	 expire_date_modify = 1
					 	 WHERE id = '".$company_id."'";
		//die($sql);
		$result	=	$this->_db->query(Database::INSERT, $sql, true);
		return true;
	}

	public function suspend_company($company_id)
	{
		$sql	=	"UPDATE company
					 SET suspend_status = 1,
					 suspend_resume_date = now()
					 WHERE id = '".addslashes($company_id)."'
					";
		$result	=	$this->_db->query(Database::INSERT, $sql, true);
		return true;
	}
	
	public function resume_company($company_id)
	{
		$sql	=	"UPDATE company
					 SET suspend_status = 0,
					 suspend_resume_date = now()
					 WHERE id = '".addslashes($company_id)."'
					";
		$result	=	$this->_db->query(Database::INSERT, $sql, true);
		return true;
	}

	public function get_company_reference_id($company_id)
	{
		$plan_info	=	array();
			$sql	=	"SELECT signup_plan_id, service_token
						 FROM company_plan
						 WHERE company_id = '".addslashes($company_id)."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) {
			throw new Exception("No company records found");
		} else {
			$plan_info['ref_id']	=	$result[0]['service_token'];
			$plan_info['plan']		=	$result[0]['signup_plan_id'];
		}
		return $plan_info;
	}

	/**
	 * function to change admin password
	 */
	public function change_password($new_password)
	{
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
			$sql	=	"UPDATE admin_users
						 SET password = AES_ENCRYPT('".addslashes($new_password)."', '".ENCRYPT_KEY."')
						 WHERE company_id = '".$_SESSION['company_id']."'";
		}	else{
			$sql	=	"UPDATE dharma_users
						 SET password = AES_ENCRYPT('".addslashes($new_password)."', '".ENCRYPT_KEY."')
						 WHERE company_id = '".$_SESSION['company_id']."'
						 AND record_id	= '".$_SESSION['employee_id']."'";
		}
		$result	=	$this->_db->query(Database::INSERT, $sql, true);
		return true;
	}
	
	/**
	 * check whether current paswword is correct or not
	 * @param $old_password : old password string
	 */
	public function valid_current_password($old_password)
	{
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
			$sql	=	"SELECT id, email
					 	FROM admin_users
					 	WHERE company_id = '".$_SESSION['company_id']."'
					 	AND password = AES_ENCRYPT('".addslashes($old_password)."', '".ENCRYPT_KEY."')";
		}else{
			$sql	=	"SELECT id, email
					 	FROM dharma_users
					 	WHERE company_id = '".$_SESSION['company_id']."'
					 	AND record_id	=	'".$_SESSION['employee_id']."'
					 	AND password = AES_ENCRYPT('".addslashes($old_password)."', '".ENCRYPT_KEY."')";	
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) {
			return false;
		} else {
			return true;
		}
	}
	
	/**
	 * function to insert the payment gateway tokens ids and passwords
	 * gateway_ach table
	 */
	public function enter_payment_details($post)
	{	
		$check	=	"SELECT * FROM gateway_ach
					WHERE employee_id='".$_SESSION['employee_id']."'
					AND company_id = '".$_SESSION['company_id']."'";
		$result	=	DB::query(Database::SELECT, $check)->execute()->as_array();
		
		if(!empty($result)){
			//die($result);
			$sql	=	"UPDATE gateway_ach
						set ach_gateway_id  = AES_ENCRYPT('".addslashes($post['ach_gateway_id'])."','".ENCRYPT_KEY."'),
						ach_gateway_password= AES_ENCRYPT('".addslashes($post['ach_gateway_password'])."','".ENCRYPT_KEY."'),
						apli_login_id  		= AES_ENCRYPT('".addslashes($post['apli_login_id'])."','".ENCRYPT_KEY."'),
						transaction_key  	= AES_ENCRYPT('".addslashes($post['transaction_key'])."','".ENCRYPT_KEY."'),
						status				= '1',
						created_date 		= now()
						WHERE employee_id 	= '".$_SESSION['employee_id']."'
						AND company_id 		= '".$_SESSION['company_id']."'
						";
			$result1	=	$this->_db->query(Database::INSERT, $sql, true);
			if(empty($result1)) {
				return false;
			} else {
				return true;
			}
		}
		else {
			$sql	=	"INSERT INTO gateway_ach
						(employee_id,company_id,ach_gateway_id,ach_gateway_password,
						apli_login_id,transaction_key,status,created_date)
						values('".$_SESSION['employee_id']."',
						'".$_SESSION['company_id']."',
						AES_ENCRYPT('".addslashes($post['ach_gateway_id'])."','".ENCRYPT_KEY."'),
						AES_ENCRYPT('".addslashes($post['ach_gateway_password'])."','".ENCRYPT_KEY."'),
						AES_ENCRYPT('".addslashes($post['apli_login_id'])."','".ENCRYPT_KEY."'),
						AES_ENCRYPT('".addslashes($post['transaction_key'])."','".ENCRYPT_KEY."'),
						'1',
						now()
						)";
			$result	=	$this->_db->query(Database::INSERT, $sql, true);
			if(empty($result)) {
				return false;
			} else {
				return true;
			}	
		}
	}

	/**
	 * get total slips which are created
	 */
	public function get_total_slips_count($company_id=0)
	{
		if($company_id != 0) {
			$sql	=	"SELECT count(*) as total_slips
						 FROM activity_slip_lists
						 WHERE company_id = '".addslashes($company_id)."'";	
			
		} else {
			$sql	=	"SELECT count(*) as total_slips
						 FROM activity_slip_lists
						 WHERE 1";	
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result[0]['total_slips'];
	}
	
	/**
	 * get synced slips count
	 */
	public function get_synced_slips_count($company_id=0)
	{
		if($company_id != 0) {
			$sql	=	"SELECT count(*) as total_synced_slips
						 FROM activity_slip_lists
						 WHERE sync_status = 1
						 AND company_id = '".addslashes($company_id)."'";
		} else {
			$sql	=	"SELECT count(*) as total_synced_slips
						 FROM activity_slip_lists
						 WHERE sync_status = 1";	
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result[0]['total_synced_slips'];
	}
	
	/**
	 * get synced slips count
	 */
	public function get_total_slips_in_week($start, $end,$company_id=0)
	{
		if($company_id != 0) {
			$sql	=	"SELECT count(*) as total_week_slips
						 FROM activity_slip_lists
						 WHERE created_date BETWEEN '".$start."' AND '".$end."'
						 AND  company_id = '".addslashes($company_id)."'";
		} else {
			$sql	=	"SELECT count(*) as total_week_slips
						 FROM activity_slip_lists
						 WHERE created_date BETWEEN '".$start."' AND '".$end."'";
		}	
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result[0]['total_week_slips'];
	}

	/**
	 * Function to fetch company activate id and return its activate url
	 *
	 * @param unknown_type $company_id
	 */
	public function get_company_active_url($company_id, $offline)
	{
		if($offline) {
			$sql	=	"SELECT activation_id
					 	 FROM temp_company_signup
					 	 WHERE id = '".$company_id."'";
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		} else {
			$sql	=	"SELECT activation_id
						 FROM company
						 WHERE id = '".$company_id."'";	
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		}
		if(!empty($result)) {
			return 	$result[0]['activation_id'];
		} else {
			return null;	
		}
	}


	/**
	 * Function to verify company admin password before reseting user password
	 *
	 * @param unknown_type $adm_password
	 */
	public function valid_admin_password($adm_password)
	{
		$sql	=	"SELECT *
					 FROM admin_users
					 WHERE password = AES_ENCRYPT('".addslashes($adm_password)."','".ENCRYPT_KEY."')
					 AND company_id = '".$_SESSION['company_id']."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) {
			//die("fail");
			return false;	
		} else {
			//die("pass");
			return true;
		}
	}
	
		/**
	 * Get user slip process information
	 *
	 */
	public function get_user_slip_information($user_id)
	{
		$slip_info	=	array();
		//get complete slip count
		$sql		=	"SELECT count(*) as total_slips
						 FROM activity_slip_lists
						 WHERE EmployeeRecordID = '".$user_id."'
						 AND company_id = '".$_SESSION['company_id']."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result)) {
			$slip_info[0]['total_slips']	=	$result[0]['total_slips'];
		} else {
			$slip_info[0]['total_slips']	=	0;
		}
		
		// get synced slip count
		$sql		=	"SELECT count(*) as total_synced_slips, max(sync_date) as last_sync_date
						 FROM activity_slip_lists
						 WHERE EmployeeRecordID = '".$user_id."'
						 AND company_id = '".$_SESSION['company_id']."'
						 AND sync_status = 1";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result)) {
			$slip_info[0]['total_synced_slips']	=	$result[0]['total_synced_slips'];
			$slip_info[0]['last_sync_date']		=	$result[0]['last_sync_date'];
		} else {
			$slip_info[0]['total_synced_slips']	=	0;
			$slip_info[0]['last_sync_date']		=	"";	
		}
		return $slip_info;
	}

	/**
	 * Function to update DB credentials by company id at first time login
	 */
	
	public function update_dropbox_tokens_by_company_id($device, $tokens, $company_id)
	{
		$sql	=	"UPDATE dropbbox_account
					 SET device_name  = '".addslashes($device)."',
					 	 token 		  = AES_ENCRYPT('".addslashes($tokens['token'])."','".ENCRYPT_KEY."'),
					 	 secret 	  = AES_ENCRYPT('".addslashes($tokens['token_secret'])."','".ENCRYPT_KEY."')
					 WHERE company_id = '".$company_id."'";
		$query  =  $this->_db->query(Database::INSERT, $sql, true);
		return true;
	}

	// function to get offline company reference id
	public function get_offline_company_service_token($company_id) {
		$sql	=	"SELECT service_token
					 FROM temp_company_signup
					 WHERE id = '".addslashes($company_id)."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) $reference_id	= "";
		else $reference_id	= $result[0]['service_token'];
		return $reference_id;
	}

	// Function to get country value from country code
	public function get_country_name($country_code) {
		$sql	=	"SELECT country
					 FROM country_list
					 WHERE id = '".$country_code."'
					 ";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return "";
		else return $result[0]['country'];
	}
	
	public function get_login_details($activation_id,$flag=1){
		if($flag==1){
			$sql	=	"SELECT email,AES_DECRYPT(password,'".ENCRYPT_KEY."')as password
						FROM company AS c, admin_users AS au
						WHERE c.activation_id	=	'".addslashes($activation_id)."'
						AND c.id=au.company_id
						";
		}else{
			$sql	=	"SELECT email,AES_DECRYPT(password,'".ENCRYPT_KEY."')as password
						FROM admin_users
						WHERE company_id='".addslashes($activation_id)."'
						";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result)){
			return $result[0];
		} else {
			return	false; 
		}
	}
	
	/**
	 * Function to delete data while dropbox device name is changed
	 *
	 * @param $company_id : company id of user
	 * @return true if deleted successfully
	 */
	public function database_delete_data_on_device_change($company_id){
		$table_name_array	=	array(
								0	=>	'activities_sync',
								1	=>	'accounts',
								2	=>	'activities',
								3	=>	'customers',
								4	=>  'dharma_users',
								5	=>	'custom_list_fields',
								6	=>	'custom_list_items',
								7	=>	'items',
								8	=>	'jobs',
								9	=>	'sales',
								10	=>	'sales_and_purchase',
								11	=>	'taxes',
								12  =>	'activity_slip_lists',
								13  =>	'employees',
								14  =>	'vendors'
							);
		foreach($table_name_array as $table_name){
			$sql	=	"DELETE FROM $table_name WHERE company_id = '".addslashes($company_id)."'";
			$query  =  $this->_db->query(Database::DELETE, $sql, true);
		}
		return true;
	}
}
?>