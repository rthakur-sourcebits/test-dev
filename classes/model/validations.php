<?php defined('SYSPATH') or die('No direct script access.');
/*
 * @File: validations.php Model
 * @Class: Model_validations
 * @Author: 
 * @Created: 
 * @Description: This class file holds the operations of activity slips.
 */
class Model_Validations extends Model
{
	/*
	 * @Function	: validate_activity.
	 * @Param		: fields that should be validated before saving.
	 * @Description	: validates the activity slip fields and throughs a error on exception. 
	 */
	var $slip_max_length = 30;			// slip number max length.
	var $max_length		 = 20; 			// units and rate maximum length.
	
	/**
	 * Function to validate the activity slips
	 * @param unknown_type $params
	 * @param unknown_type $json
	 */
	public function validate_activity($params,$json)
	{
		$activity	=	new Model_Activity;
		$json->file_content =	$activity->get_activities_by_tt();
		//echo $params['activity_name'];die;
		$activity_list		= 	$json->JSON_Query(array("ActivityID"), array("ActivityID"=>$params['activity_name']));
		//echo "<pre>";print_r($activity_list);die;
		$json->file_content =	$activity->get_customers();

		
		if ($_SESSION['employee_id'] == null OR $_SESSION['employee_id'] == '')
		{
			$statusMessage = 'Please login.';
			throw new Kohana_Exception($statusMessage);
		}
		/*elseif ($params['is_non_hourly'] == 0 && ($params['customer_name'] == "Enter name..." OR $params['customer_name'] == ''))
		{
			$statusMessage = 'Cutomer id is not valid';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_cust_id'));
		}*/
		elseif ($params['slip_number'] == null OR $params['slip_number'] == '')
		{
			$statusMessage = 'Slip number is not valid';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_slip_num'));
		}
		elseif (count($params['slip_number']) >$this->slip_max_length)
		{
			$statusMessage = 'Please slip number to 30 characters.';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_slip_num_len'));
		}
		elseif ($params['activity_id'] == null OR $params['activity_id'] == '')
		{	
			$statusMessage = 'Activity id is not valid';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_activity_id'));							 
					 
		}

		elseif ($params['units'] == null OR $params['units'] == '')
		{
			$statusMessage = 'units value is empty';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_units'));
		}

		elseif (!$this->unit_validator($params['units']))
		{
			$statusMessage = 'units value is having special characters';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_unit_char'));
		}
		elseif ($params['rate'] == null OR $params['rate'] == '')
		{
			$statusMessage = 'rate value is empty';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_rate'));		 
		}
		elseif (count($params['rate']) >$this->max_length)
		{
			$statusMessage = 'Please rate value to 30 characters.';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_rate_len'));
		}
		elseif (!$this->unit_validator($params['rate']))
		{
			$statusMessage = 'rate value is having special characters';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_rate_char'));	
		}
		elseif ($params['total'] == null OR $params['total'] == '')
		{
			$statusMessage = 'total is empty';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_total'));	
		}
		elseif (!$this->unit_validator($params['total']))
		{
			$statusMessage = 'total value is having special characters';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_total_char'));	
		}
		/*elseif(empty($activity_list)) 
		{
			$statusMessage = 'Please select activity from the list.';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_act_list'));
		}*/
	}
	
	/*
	 * @Function	: validate_timesheet.
	 * @Param		: fields that should be validated before saving.
	 * @Description	: validates the time sheet fields and throughs a error on exception. 
	 */
	public function validate_timesheet($params)
	{
		
		if ($params['employee_id'] == null OR $params['employee_id'] == '')
		{
			$statusMessage = 'Please login.';
			throw new Kohana_Exception($statusMessage);
		}
		elseif (count($params['slip_number']) >$this->slip_max_length)
		{
			$statusMessage = 'Please slip number to 30 characters.';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_slip_num_len'));
		}
		/*elseif ($params['customer_id'] == null OR $params['customer_id'] == '')
		{
			$statusMessage = 'Cutomer id is not valid';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_cust_id'));
		}*/
		
		/*elseif ($params['activity_id'] == null OR $params['activity_id'] == '')
		{	
			$statusMessage = 'Activity id is not valid';
			throw new Kohana_Exception(Kohana::message('error', 'actslip_activity_id'));						 
		}*/
	}	
	
	/*
	 * @Function	: validate_registration.
	 * @Param		: fields that should be validated before saving.
	 * @Description	: validates the user creation fields and throughs a error on exception. 
	 */
	public function validate_registration($params)
	{
 		$return_value	=	true;
		$json			=   new Model_json;
		$email_status	=	$this->verify_email($params['UserEmail'], $params['user_id']);
		
		if( $params['UserEmail'] == "" OR !preg_match("/^([A-Za-z0-9_\-\.\+])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/",$params['UserEmail']))
		{
			$return_value = false;
			//throw new Kohana_Exception("Please enter valid Email.");
			throw new Kohana_Exception(Kohana::message('error', 'valid_email'));
		}
		elseif( $email_status ) // if edit form email is exists except current user
		{
			$return_value = false;
			//throw new Kohana_Exception("Email address already exist.");
			throw new Kohana_Exception(Kohana::message('error', 'email_exists'));
		}
		/*elseif( $params['Password'] == ""  OR strstr($params['Password'], " ") OR strlen($params['Password']) < 6 OR strlen($params['Password']) > 15) 
		{
			$return_value = false;
			//throw new Kohana_Exception("Please enter valid Password. Characters should be between 6 and 15 without blank space.");
			throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
		}*/
		if($return_value) 
		{
			return true;
		}	 
	}	
	
	/*
	 * @Function	: unit_validator.
	 * @Param		: $str - value that is to be checked.
	 * @Description	: checks username is integer or not. 
	 */
	public function unit_validator($str)
	{
		preg_match_all('/\D/', $str, $match_array);
		if(preg_match("/^([0-9\-\.]*)$/",$str)) {
			return true;
		} else {
			return false;
		}/*
		$match_string = implode(',' , $match_array[0]); 
		
		if($match_string !='.' && $match_string != null)
		{
			return false;
		}	
		return true;*/
	}
	
	/*
	 * @Function	: check_user_name.
	 * @Param		: $str- value that is to be checked.
	 * @Description	: checks wheather username contains alphabets and integers. 
	 */
	public function check_user_name($str)
	{
		preg_match_all('/\W/', $str, $match_array);
		$match_string = implode(',' , $match_array[0]); 
		
		if	($match_string)
		{
			if	($match_string!='_')
			{
				return false;
			}
			else
			{
				return true;
			}
		}
		else
		{
			return true;
		}
	}
	
	/*
	 * @Function	: check_user_name.
	 * @Param		: $str- value that is to be checked.
	 * @Description	: checks wheather name contains alphabets and '. 
	 */
	public function checkName($str)
	{
		preg_match_all('/\W/', $str, $match_array1);
		preg_match_all('/\d/', $str, $match_array2);
		$match_string1 = implode(',' , $match_array1[0]); 
		$match_string2 = implode(',' , $match_array2[0]);
				
		if($match_string1 || $match_string2)
		{
			if($match_string2)
			{
				return false;
			}
			if($match_string1!="'")
			{
				return false;
			}
			else
			{
				return true;
			} 
		}
		else
		{
			return true;
		}
	}
	
	/*
	 * @Function	: check_user_name.
	 * @Param		: $str- value that is to be checked.
	 * @Description	: checks wheather password has spaces. 
	 */
	public function checkPassword($str)
	{
		if(!strstr($str, " ")) 
		{
			return true;
		}
		else
		{
			return false;
		}
	}

	/*
	 * @Function	: check_user_name.
	 * @Param		: $str- value that is to be checked.
	 * @Description	: checks wheather password has spaces.  
	 */
	public function check_user_session()
	{
		if (isset($_COOKIE['keep_loged']))	// checks for the cookie and then creates  the session objects.
		{
			if(empty($_SESSION['employee_id']))
			{
				$data			=	new Model_data;
				$data->check_cookie();
			}
		}
		//die("emp ".$_SESSION['employee_id']);
		if (empty($_SESSION['employee_id'])) 
		{ // redirect to login page if user is not logged in
			if(isset($_SESSION['company']))
			{
				// redirecting to the login page of the comapany.
				request::instance()->redirect(SITEURL.'/login');
			}else{
				request::instance()->redirect(SITEURL);
			}
		}
		if(empty($_SESSION['Employees']))
		{
			request::instance()->redirect(SITEURL."/login/logout");
		}
		return true;
	}
	
	/**
	@Function	:	verify_email
	@Description:	verify email already exist or not
	 * modifying: 12.11.2013 : checking admin_user validation along with dharma_users
	*/
	private function verify_email($email, $user_id)
	{
		if(empty($_POST['id']))
		{
			$query	=	"(	SELECT id
					 	 	FROM dharma_users
					 	 	WHERE email = '".addslashes(strtolower(trim($email)))."'
					 	 ) UNION ALL (
					 	 	SELECT id
					 	 	FROM admin_users
					 	 	WHERE email = '".addslashes(strtolower(trim($email)))."'
					 	 )";
		}
		else 
		{
			$query	=	"(	SELECT id
						 	FROM dharma_users
						 	WHERE email = '".addslashes(strtolower(trim($email)))."'
						 	AND id 	!= '".$_POST['id']."'
						 ) UNION ALL (
						 	SELECT id
					 	 	FROM admin_users
					 	 	WHERE email = '".addslashes(strtolower(trim($email)))."'
					 	 )";
		}
		
		$query	=	DB::query(Database::SELECT, $query);
		$data	=	$query->execute()->as_array();
		if(empty($data))
		{
			return false;
		}
		else
		{
			return true;
		}
	}
	
	public function validate_user_update($post)
	{
		if($post['UserEmail'] == "")
			throw new Kohana_Exception(Kohana::message('error', 'valid_email'));
		if($post['password'] == "")
			throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
		if($post['serialnumber'] == "")
			throw new Kohana_Exception(Kohana::message('error', 'serial_num'));
		if(strlen($post['serialnumber']) != 12)
			throw new Kohana_Exception(Kohana::message('error', 'serial_num'));
		if(!preg_match("/^([0-9]*)$/", $post['serialnumber']))
			throw new Kohana_Exception(Kohana::message('error', 'serial_num'));
		if(!preg_match("/^([a-z0-9\s\'\-\.\#]*)$/i",$post['serialnumber']))
			throw new Kohana_Exception(Kohana::message('error', 'serial_num_char'));
		if($post['name'] == "")
				throw new Kohana_Exception(Kohana::message('error', 'billing_name'));
		if(!empty($post['address']) && !preg_match("/^([a-z0-9\s\'\#\.\/\,]*)$/i",$post['address']))
				throw new Kohana_Exception(Kohana::message('error', 'billing_address_char'));
		if($post['cname'] == "")
			throw new Kohana_Exception(Kohana::message('error', 'billing_comp_name'));
		if(!preg_match("/^([a-z0-9\s\.\'\"\,\&\!\@]*)$/i",$post['cname']))
			throw new Kohana_Exception(Kohana::message('error', 'billing_comp_name_char'));
		if($post['city'] == "")
			throw new Kohana_Exception(Kohana::message('error', 'billing_city'));
		if(!preg_match("/^([a-z0-9\s\']*)$/i",$post['city']))
			throw new Kohana_Exception(Kohana::message('error', 'billing_city_char'));
		if($post['state'] == "")
			throw new Kohana_Exception(Kohana::message('error', 'billing_state'));
		if(!preg_match("/^([a-z0-9\s\']*)$/i",$post['state']))
			throw new Kohana_Exception(Kohana::message('error', 'billing_state_char'));
		if($post['zipcode'] == "")
			throw new Kohana_Exception(Kohana::message('error', 'billing_zip'));
		if(!preg_match("/^([a-z0-9\s]*)$/i",$post['zipcode']))
			throw new Kohana_Exception(Kohana::message('error', 'billing_zip_char'));
	//	if($post['phone'] == "")
		//	throw new Kohana_Exception(Kohana::message('error', 'billing_phone'));
		if($post['country'] == "" || $post['country'] == 0)
			throw new Kohana_Exception(Kohana::message('error', 'billing_country'));
		if($post['country'] > 201)
			throw new Kohana_Exception(Kohana::message('error', 'billing_country_list'));
			
		if(!empty($post['phone']) && !$this->phone_number_validate($post['phone']))
			throw new Kohana_Exception(Kohana::message('error', 'billing_phone'));
		if(!$this->user_name_validate($post['name']))
			throw new Kohana_Exception(Kohana::message('error', 'billing_name'));
			
		if($post['password'] != $post['confirmpass'])
			throw new Kohana_Exception(Kohana::message('error', 'pass_mis_match'));
		if(!$this->checkPassword($post['password']))
			throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
		if(strlen($post['password']) <= 5)
			throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
		if($this->email_exists($post['UserEmail'], $post))
			throw new Kohana_Exception(Kohana::message('error', 'email_exists'));
		if(!$this->email_validate($post['UserEmail']))
			throw new Kohana_Exception(Kohana::message('error', 'valid_email'));
	}
	/**
	 * Validate user registration fields
	 * @param unknown_type $post : user input values
	 */
	public function validate_user_registration($post, $page = "")
	{
		if($page == 1) {
			
			if((isset($post['plan']) && $post['plan'] == 0) && empty($post['company_id']))
				throw new Kohana_Exception(Kohana::message('error', 'signup_plan'));
		//	if($post['company_name'] == "")
			//	throw new Kohana_Exception(Kohana::message('error', 'company_name'));
		//	if(!preg_match("/^([a-z0-9\s\'\-\.]*)$/i",$post['company_name']))
		//	throw new Kohana_Exception(Kohana::message('error', 'company_name_char'));
			if($post['UserEmail'] == "")
				throw new Kohana_Exception(Kohana::message('error', 'valid_email'));
			if($post['password'] == "")
				throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
			if($post['serialnumber'] == "")
				throw new Kohana_Exception(Kohana::message('error', 'serial_num'));
			if(strlen($post['serialnumber']) != 12)
				throw new Kohana_Exception(Kohana::message('error', 'serial_num'));
			if(!preg_match("/^([0-9]*)$/", $post['serialnumber']))
				throw new Kohana_Exception(Kohana::message('error', 'serial_num'));
			if(!preg_match("/^([a-z0-9\s\'\-\.\#]*)$/i",$post['serialnumber']))
				throw new Kohana_Exception(Kohana::message('error', 'serial_num_char'));
		}
		if($page == 2) {
			//if(empty($post['terms']) && empty($post['company_id']))
			//	throw new Kohana_Exception(Kohana::message('error', 'agree_terms'));
			if(isset($post['serialnumber']) && strlen($post['serialnumber']) != 12)
				throw new Kohana_Exception(Kohana::message('error', 'serial_num'));
			if($post['name'] == "")
				throw new Kohana_Exception(Kohana::message('error', 'billing_name'));
			if($post['lastname'] == "")
				throw new Kohana_Exception(Kohana::message('error', 'billing_lastname'));
			if(!preg_match("/^([a-z0-9\s\']*)$/i",$post['name']))
				throw new Kohana_Exception(Kohana::message('error', 'billing_name_char'));
			if(!preg_match("/^([a-z0-9\s\']*)$/i",$post['lastname']))
				throw new Kohana_Exception(Kohana::message('error', 'billing_last_name_char'));
			//if($post['address'] == "")
				//throw new Kohana_Exception(Kohana::message('error', 'billing_address'));
			if(!empty($post['address']) && !preg_match("/^([a-z0-9\s\'\#\.\/\,]*)$/i",$post['address']))
				throw new Kohana_Exception(Kohana::message('error', 'billing_address_char'));
			if($post['cname'] == "")
				throw new Kohana_Exception(Kohana::message('error', 'billing_comp_name'));
			if(!preg_match("/^([a-z0-9\s\.\'\"\,\&\!\@]*)$/i",$post['cname']))
				throw new Kohana_Exception(Kohana::message('error', 'billing_comp_name_char'));
			if($post['city'] == "")
				throw new Kohana_Exception(Kohana::message('error', 'billing_city'));
			if(!preg_match("/^([a-z0-9\s\']*)$/i",$post['city']))
				throw new Kohana_Exception(Kohana::message('error', 'billing_city_char'));
			if($post['state'] == "")
				throw new Kohana_Exception(Kohana::message('error', 'billing_state'));
			if(!preg_match("/^([a-z0-9\s\']*)$/i",$post['state']))
				throw new Kohana_Exception(Kohana::message('error', 'billing_state_char'));
			if($post['zipcode'] == "")
				throw new Kohana_Exception(Kohana::message('error', 'billing_zip'));
			if(!preg_match("/^([a-z0-9\s]*)$/i",$post['zipcode']))
				throw new Kohana_Exception(Kohana::message('error', 'billing_zip_char'));
		//	if($post['phone'] == "")
			//	throw new Kohana_Exception(Kohana::message('error', 'billing_phone'));
			if($post['country'] == "" || $post['country'] == 0)
				throw new Kohana_Exception(Kohana::message('error', 'billing_country'));
			if($post['country'] > 201)
				throw new Kohana_Exception(Kohana::message('error', 'billing_country_list'));
				
			if(!empty($post['phone']) && !$this->phone_number_validate($post['phone']))
				throw new Kohana_Exception(Kohana::message('error', 'billing_phone'));
			if(!$this->user_name_validate($post['name']))
				throw new Kohana_Exception(Kohana::message('error', 'billing_name'));
		}
		
		if($page == 1) {
			if($post['password'] != $post['confirmpass'])
				throw new Kohana_Exception(Kohana::message('error', 'pass_mis_match'));
			if(!$this->checkPassword($post['password']))
				throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
			if(strlen($post['password']) <= 5)
				throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
			if($this->email_exists($post['UserEmail'], $post))
				throw new Kohana_Exception(Kohana::message('error', 'email_exists'));
			if(!$this->email_validate($post['UserEmail']))
				throw new Kohana_Exception(Kohana::message('error', 'valid_email'));
		}

	}
	
	
	public function validate_free_user_registration($post)
	{   if((isset($post['plan']) && $post['plan'] == 0) && empty($post['company_id']))
			throw new Kohana_Exception(Kohana::message('error', 'signup_plan'));
		if($post['UserEmail'] == "")
			throw new Kohana_Exception(Kohana::message('error', 'valid_email'));
		if($post['company_name'] == "")
			throw new Kohana_Exception(Kohana::message('error', 'company_name'));
		if($post['password'] == "")
			throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
		if($post['password'] != $post['confirmpass'])
			throw new Kohana_Exception(Kohana::message('error', 'pass_mis_match'));
		if(!$this->checkPassword($post['password']))
			throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
		if(strlen($post['password']) <= 5)
			throw new Kohana_Exception(Kohana::message('error', 'valid_password'));
		if($this->email_exists($post['UserEmail'], $post)) // changing for check the employee email id.
			throw new Kohana_Exception(Kohana::message('error', 'email_exists'));
		if(!$this->email_validate($post['UserEmail']))
			throw new Kohana_Exception(Kohana::message('error', 'valid_email'));
	}
	/**
	 * Check for whether user email is already exists in system or not
	 * @param unknown_type $email
	 * @param unknown_type $post
	 * modifying : 12.11.2013 - adding email check for employee with admin-user
	 */
	private function email_exists($email, $post)
	{
		$email=addslashes(trim(strtolower($email))); 
		if(!empty($_SESSION['company_id'])) {
			$query	=	"
						(	SELECT id
							FROM admin_users
							WHERE email = '".$email."'
							AND company_id != '".$_SESSION['company_id']."'
						)UNION ALL
						(
							SELECT id
							FROM dharma_users
							WHERE email = '".$email."'
						)";
			
		}
		else {
			if(empty($_POST['company_id'])) // for new company
			{
				if(!empty($_POST['ref_id'])) { 
					$obj_register	=	new Model_Register;
					$comp_id	=	$obj_register->get_company_id_by_token($_POST['ref_id']);
					$query	=	"(
									SELECT id
								 	FROM admin_users
								 	WHERE email = '".$email."'
								 	AND company_id != '".$comp_id."'
								 )
								 UNION ALL
								 (
								 	SELECT id
									FROM temp_company_signup
									WHERE email = '".$email."'
									AND status = 1
								 )
								 UNION ALL
								 (
								 	SELECT id
									FROM dharma_users
									WHERE email = '".$email."'									
								 )
								 ";
				} else {
					$query	=	"(
									SELECT id
								 	FROM admin_users
								 	WHERE email = '".$email."'
								 )
								 UNION ALL
								 (
								 	SELECT id
									FROM temp_company_signup
									WHERE email = '".$email."'
									AND status = 1
								 )
								 UNION ALL
								 (
								 	SELECT id
									FROM dharma_users
									WHERE email = '".$email."'
								 )
								 ";
				}
				
			}
			else
			{
				$query	=	"(
								SELECT *
							 	FROM admin_users
							 	WHERE email = '".$email."'
							 	AND company_id != '".$_POST['company_id']."'
							)UNION ALL(
								SELECT id
								FROM dharma_users
								WHERE email = '".$email."'
							)";
			}
		}
		$query		=	DB::query(Database::SELECT, $query);
		$data		=	$query->execute()->as_array();
		
		if(empty($data)) 
			return false;
		else 
			return true;
	}
	
	/**
	 * Validate the email address
	 * @param unknown_type $email
	 * @return true if valid email else false
	 */
	private function email_validate($email)
	{
		if(!preg_match("/^([A-Za-z0-9_\-\.\+])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/",$email))
		{
			return false;
		}
		else {
			return true;
		}
	}
	
	/**
	 * Validate telephone number
	 * @param unknown_type $phone
	 */
	private function phone_number_validate($phone)
	{
		if(!preg_match("/^([0-9\+\-\s])*$/i",$phone))
		{
			return false;
		}
		else {
			return true;
		}
	}
	
	/**
	 * validate user name
	 * @param unknown_type $name
	 */
	private function user_name_validate($name)
	{
		if(!preg_match("/^([a-z\s\'])*$/i",$name))
		{
			return false;
		}
		else {
			return true;
		}
	}
	
	/**
	 * Validate dropbox email/password and device
	 * @param unknown_type $post
	 * @param unknown_type $flag
	 */
	public function validate_dropbox_account($post, $flag=false)
	{
		try {
			$status = 	$this->check_dropbox_device_name($post['device_name']);
			return $status;
		} catch(Exception $e){
			throw $e;
		}
	}
	
	public function edit_drobox_validate($post)
	{
		$old_db_tokens					=	$_SESSION['old_tokens'];
		try
		{		
			$status		=	$this->check_dropbox_device_name($post['device_name']);
			return $status;
		}
		catch(Exception $e)
		{
			$_SESSION['oauth_tokens']		=	$old_db_tokens;
			die($e->getMessage()." file ".$e->getFile()." line ".$e->getLine());
			if($e->getMessage() == "device_error")
				throw new Kohana_Exception(Kohana::message('error', 'dropbox_device_not_found'));
			else
				throw new Kohana_Exception(Kohana::message('error', 'dropbox_auth_error'));
		}
	}
	
	/**
	 * Validate dropbox device name
	 * @param $device_name	:	Dropbox device name
	 * @param $dropbox		:	Dropbox object
	 */
	private function check_dropbox_device_name($device_name)
	{
		$dropbox	=	new Model_Dropbox;
		//$device	 	=	str_replace("%2F", "/", rawurlencode($device_name));
		$device		=	$device_name;
		
		try {
				if($dropbox->apps_folder_exists($device,'/Apps/AccountEdge/')) { // check if user follow new dropbox path. Apps/AccounEdge/device
					return true;
				} else if($dropbox->apps_folder_exists($device,'/AccountEdge/')){
					return true;
				} else {
					return false;
				}			
		} catch(Exception $e) {
			die($e->getMessage());
			return false;		
		}
	}
}