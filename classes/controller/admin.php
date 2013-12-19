<?php defined('SYSPATH') or die('No direct script access.');
include_once DOCUMENT_ROOT.'/application/classes/vendors/Dropbox/session.php';
//include DOCUMENT_ROOT.'/application/classes/vendors/Dropbox/rest.php';
include_once DOCUMENT_ROOT.'/application/classes/vendors/Dropbox/client.php';
/**
 * @Class: Controller_Admin
 * @Created: 2-Sep-2010
 * @Modified: 21-Dec-2010
 * @Description: This class file holds the operations of admin login, user creation, user edit and activate and inactivate user.
 * Copyright (c) 2011 Acclivity Group LLC
 * @Modified 		: 	27.11.2013 	-	Added a function action_ccp + create a view file ffor ACH form and CCP button.
 *  
 */
class Controller_Admin extends Controller_Admintemplate
{
	private $redirect_url			=	'/admin/edit/';
	private $dropbox_access_status	=	0;
	
	/**
	* @Function 	 : action_index
	* @Description   : Displays the login and welcome page.
	*/
	public function action_index($flag=0)
	{
		try {
			$admin_model       			=	new Model_Admin;
			$this->template->title      = 	"Welcome Admin";
			$action			   			=	"view";
			$user_type		   			=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
			
			if(empty($_SESSION['admin_email']))
			{	
				$this->template->content 	= 	View::factory( 'admin/login')
														->set( 'header_user_list',	null)
														->set( 'company_entry',	1)
														->set( 'error_flag', $flag);
			} else{
				if(isset($_SESSION['superadmin']))
				{ 
					request::instance()->redirect(SITEURL.'/admin/home');
				}
				$company_users	   			=	$admin_model->get_users($user_type);
				if(isset($company_users[0]['RecordID'])){
					request::instance()->redirect(SITEURL.'/admin/edit/'.$company_users[0]['RecordID']);
				}
				$header            			=	View::factory("admin/header-list")
														->set('users', $admin_model->get_users($user_type))
														->set('user_name', null)
														->set('welcome', 1)
														->set('action', $action);
				$this->template->content 	= 	View::factory('admin/welcome')
														->set( 'header_user_list',	$header);
			}
		} catch(Exception $e){ 
			die($e->getMessage());
		}
	}
	
	/**
	 * @Access			:	Public
	 * @Function		:	action_upgrade
	 * @Description		:	Function expired: upgrade plan page.
	*/
	public function action_upgrade($error='')
	{
		$this->template->title		=	"Welcome AEC";
		$xmlrpc_m		= 	new Model_Xmlrpc;
		$admin_m 		= 	new Model_Admin;
		$dharmausers_m 	=	new Model_Dharmausers;
		if(!isset($_SESSION['company_id'])){
			request::instance()->redirect(SITEURL);
		}
		if(!empty($error)){
			$error = 'Email already exists in Rerun. Please contact the Administrator';
		}
		try {
			$data['payment_stream'] 	= $xmlrpc_m->get_signup_plans();
			$data['cur_plan'] 			= $admin_m->get_current_plan();
			$data['cur_plan_max_user'] 	= $admin_m->get_plan_user_limit();
			$data['user_limit'] 		= $admin_m->get_plan_user_limit();
			$data['total_users'] 		= $dharmausers_m->get_total_company_users();
			$data['new_company_info'] 	= null;
			$data['company_users'] 		= $dharmausers_m->get_timetracker_users();
			$data['plan_change_message']= "Your ";
			$data['plan_change_message1']= " Plan is Expired. Please Upgrade your Plan.";
			$data['rerun_error']		=	$error;
			$this->template->content  	=	new View("admin/upgrade_expired_plan", $data);
												
		} catch(Exception $e) {die($e->getMessage());}
		
	}
	
	/**
	 *	@Function 	: 	action_login
	 *	@Description: 	Accepts user name and password and verifry the user.
	 *	@param		:	company name from the url.
	 */
	public function action_login($company = "", $consumer="", $info = array())
	{
		try { 
		$this->check_admin('1');
		$admin_model	=   new Model_Admin;
		$dropbox		=   new Model_dropbox;
		
		
		if(isset($_POST['submit']) OR $consumer == "autologin")  		// admin entered username and password
		{
			if($consumer == "autologin"){
				$email	    =   $info['username'];
				$password   =   $info['password'];
			} else{
				$email	    =   $_POST['username'];
				$password   =   $_POST['password'];
			}
			
			/*************First check whether user is super admin or not*************/
			if($admin_model->check_super_admin($email, $password)){
				$_SESSION['superadmin_session']	=	1;
				$company_select_id	=	$admin_model->get_company_default();
				if(empty($company_select_id)){
					request::instance()->redirect(SITEURL.'/admin/home/');
				} else {
					$_SESSION['selected_link_type'] = 1;
					request::instance()->redirect(SITEURL.'/admin/edit_company/'.$company_select_id);
				}
			} else {
				session_destroy();
				request::instance()->redirect(SITEURL.'/admin/index/1');
			}
			
			/***********Super Admin checking done - now check for company admin********/
			$data		=	new Model_Data;
			$action		=	 "view";
			
			$key_info	=	$admin_model->get_admin_consumer($email, $password);
			if(empty($key_info)){		// getting the consumerkey and consumer secretkey based on the comapny name.
				session_destroy();
				request::instance()->redirect(SITEURL.'/admin/index/1');
			} elseif($key_info[0]['status_flag'] == 0) {
				$xmlrpc			=	new Model_Xmlrpc;
				$this->template->content	= 	View::factory('admin/signup')
														->set('country_list', $admin_model->get_country_list())
														->set('payment_stream', $xmlrpc->get_signup_plans())
														->set('resignup', 1)
														->set('resignup_ref_id', $admin_model->get_service_token($key_info[0]['company_id']))
														->set('form', null);
			}	else {
				if($key_info[0]['token'] == "") {
					$_SESSION['log_user_activation_id']		=	$key_info[0]['activation_id'];
					request::instance()->redirect(SITEURL.'/admin/activation/'.$key_info[0]['activation_id'].'/2');
				} else {
					$_SESSION['oauth_tokens']['token']			=	$key_info[0]['token'];
					$_SESSION['oauth_tokens']['token_secret']	=	$key_info[0]['secret'];
					$_SESSION['company_id']			=	$key_info[0]['company_id'];
					$data->define_dropbox_constants($key_info[0]); // define DB device name
					$dropbox->connection(null,1); // make DB connection
					$status	=	$this->admin_dropbox_file_validate();
					if($status)  {
						$_SESSION['user_name']			=	$email;
						$_SESSION['password']			=	$password;
						$_SESSION['admin_email']		=	$email;
						$_SESSION['company']			=	$key_info[0]['company_name'];
						
						$users							=   new Model_user;
						
						if($users->free_plan_user($key_info[0]['company_id'])) {
							$_SESSION['free_user']	=	1;
							$_SESSION['days_left']	=	$admin_model->expire_day_left($key_info[0]['company_id']);
						}
						request::instance()->redirect(SITEURL.'/admin');
					} else {
						$_SESSION['log_user_activation_id']		=	$key_info[0]['activation_id'];
						request::instance()->redirect(SITEURL.'/admin/activation/'.$key_info[0]['activation_id'].'/2');
					}
				}				
			}
		}
		else 
		{
			if(isset($_SESSION['admin_id'])) // welcome page if session available
			{
				$user_type		  			=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
				$header						=	View::factory("admin/header-list")
													->set('users', $admin_model->get_users($user_type))
													->set('user_name', null)
													->set('action', $action)
													->set('welcome', 1);
				$this->template->content 	= 	View::factory('admin/welcome')
													->set( 'header_user_list',	$header);
			}
			else							// if no admin session then go to login page.
			{
				if(isset($_GET['e_flag']) && $_GET['e_flag'] == 1)
				{
					$error	=	"Invalid Company name or token.";
				}
				elseif(isset($_GET['e_flag']) && $_GET['e_flag'] == 2)
				{
					$error	=	"Invalid user email or password.";
				}
				else
				{
					$error	=	null;
				}
				$this->template->content = View::factory('admin/login')
												->set('company', $company)
												->set('auth_error', $error);
			}	
		}} catch(Exception $e){
			die($e->getMessage());
		}	
	}

	/**
	 * @Method		:	admin_dropbox_file_validate
	 * @Description	:	Function validate dropbox files at login time
	 * @return 		:	unknown
	 */
	public function admin_dropbox_file_validate()
	{
		$user       			=   new Model_User;
		$fail					=	false;
		$result					=	false;
		$data					=	new Model_data;
		
		try 
		{
			$true_session	=	$user->create_json_sessions();
			
		}
		catch(Exception $e)	
		{
			$fail 			=	true;
			$fail_message	=	$e->getMessage();
		}

		if($fail)  // check whether if any error occured while reading any json file.
		{
			return false;
			session_destroy();
			request::instance()->redirect(SITEURL.'/admin/index/6');
		}
		else
		{ 
			return true;			
		}
	}
	
	/**
	 * @Function	:	action_validate
	 * @Description	:	this function checkes the username and password and returns the user data.
	 * @param1		:	Dropbox model object
	*/
	public function action_validate($dropbox_model)
	{	
		try { 	
			$user       			=   new Model_User;
			$fail					=	false;
			$result					=	true;
			$this->template->title	=	"Welcome Dharma";
			$data					=	new Model_data;
			$admin_model			=   new Model_Admin;
			
			// if the call back is defined then enable the session and redirect back to the call backurl.
			if(isset($_REQUEST['callback']))
			{
				try {
					$true_session	=	$user->create_json_sessions();
				}
				catch(Exception $e)	{
					print_r($e);
				}
				$employee_data = $user->get_employee($_SESSION['employee_id']);
				$_SESSION['employee_name']  =	$employee_data[0]['FirstName'];
				request::instance()->redirect(SITEURL.''.$_REQUEST['callback']);	
				return;
			}
						
					
			if($result)
			{
				
				try {
					$true_session	=	$user->create_json_sessions(1);
				}
				catch(Exception $e)	{
					$fail 			=	true;
					$fail_message	=	$e->getMessage();
				}
						
				if($fail)  // check whether if any error occured while reading any json file.
				{
					$company = $_SESSION['company'];
					session_destroy();
					request::instance()->redirect(SITEURL.'/admin/index/3');
				}
				else
				{
					$_SESSION['admin_email']	=	$_SESSION['user_name'];
					$action			   			=	 "view";
					$user_type		  			=    empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
					$header						=	View::factory("admin/header-list")
														->set('users', $admin_model->get_users($user_type))
														->set('user_name', null)
														->set('welcome', 1)
														->set('action', $action);
					$this->template->content 	= 	View::factory('admin/welcome')
														->set( 'header_user_list',	$header);
				}
			}
			else 
			{
				$company = $_SESSION['company'];
				session_destroy();
				request::instance()->redirect(SITEURL.'/admin/index/2');
			}
		} catch(Exception $e){
			die($e->getMessage());
		}	
				
	}
	   
	/*
     * @Function 	  :	action_emailCheck
	 * @Description   : Checks whether email exists or not.
	 */
	public function action_emailCheck()
	{
	    try { 
			$email 	= $_POST['email'];
	        //$email 	= "you@me.com";
		    $objadmin	=	new Model_admin;
		    $email		=	$objadmin->check_email($email);
		    if(!empty($email))
	       	{
	        	echo "1";
	           	die;
	       	}
		    else
		    {
		    	echo "0";
		        die;
		    }
	    } catch(Exception $e){
	    	die($e->getMessage());
	    }
		    
	}
	
   /*
	* @Function 	 : action_logout
	* @Description   : logout the user from the system.
	*/
	public function action_logout()
	{
		if(empty($_SESSION['company']))
		{
			session_destroy();
			request::instance()->redirect(SITEURL."/admin");
		}
		$company = $_SESSION['company'];
		session_destroy();
		request::instance()->redirect(SITEURL.'/admin');
	}

	/*
	 * @Function 	: 	action_users 
	 * @Description	: 	Display the user created.
	 * @param1	 	:	User type (employee or vendor)
	 */
	public function action_users($user_type="")
	{
		try { 
			$this->check_admin();
			if( $user_type != 1 AND $user_type != 2 AND $user_type !=3 )
			{
				Request::instance()->redirect(SITEURL.'/admin');
			}
			$this->template->title	 	= 	"Dharma Admin - Users List";
			$this->template->styles	 	= 	array();
			$this->template->scripts	= 	array();
			$admin_model 				= 	new Model_Admin;
			$action						=	"view";
			$_SESSION['selected_user_type']	=	$user_type;
			request::instance()->redirect(SITEURL.'/admin');
			$header						=	View::factory("admin/header-list")
														->set('users', $admin_model->get_users($user_type))
														->set('user_name', null)
														->set('welcome', 1)
														->set('action', $action);
			$this->template->content	=	View::factory('admin/welcome')
														->set( 'header_user_list',	$header);
		} catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	/*
	 * @Function : action_add 
	 * @Description: Display the user account creation form.
	 */
	public function action_edit($user_id="", $form_error = "")
	{
		try{
			$this->check_admin();
			if(empty($user_id))
			{
				Request::instance()->redirect(SITEURL.'/admin');	
			}
			
			$this->template->title 		= "Dharma Admin - Edit User";
			$this->template->styles 	= array();
			$this->template->scripts 	= array();
			$admin_model	= new Model_Admin;
			$json			= new Model_Json;
			$employee_m		= new Model_Employee;
			$vendor_m 		= new Model_Vendor;
			$dharmausers_m	= new Model_Dharmausers;
			
			$user			= array();
			$user 			= $dharmausers_m->get_user_info($user_id, $_SESSION['company_id']);
			$emp_user_info	=	$employee_m->get_employee($user_id, $_SESSION['company_id']);
			
			$contract_user_info	= $vendor_m->get_vendor($user_id, $_SESSION['company_id']);
			
			if(empty($emp_user_info) AND empty($contract_user_info))
			{
				Request::instance()->redirect(SITEURL.'/admin/users?fail=2');
			}
			/****Check whether user is Employee or Contractor*******/
			if(!empty($emp_user_info)) // employee user
			{
				$type			=	"Employee";
				$emp_user		=	array_merge(array(), $emp_user_info);
				if(isset($emp_user[0]['first_name']))
				{
					$json_first_name =	$emp_user[0]['first_name'];
					$json_last_name	 =	$emp_user[0]['company_or_last_name'];//"";
				}
				else
				{
					$json_first_name =	$emp_user[0]['company_or_last_name'];
					$json_last_name	 =	"";
				}
				if(isset($emp_user[0]['email']))
				{
					$json_user_email	=	$emp_user[0]['email'];
				} else {
					$json_user_email	=	"";
				}
			}
			elseif(!empty($contract_user_info)) // contractor(vendor) user
			{
				$type			=	"Vendor";
				$contract_user	= 	array_merge(array(), $contract_user_info);
				if(isset($contract_user[0]['FirstName'])) // check if field has first name or not
				{
					$json_first_name =	$contract_user[0]['first_name'];
					$json_last_name	 =	$contract_user[0]['company_or_last_name'];//"";
				}
				else
				{
					$json_first_name =	$contract_user[0]['company_or_last_name'];
					$json_last_name	 =	"";
				}
				if(isset($contract_user[0]['email']))
				{
					$json_user_email	=	$contract_user[0]['email'];
				} else {
					$json_user_email	=	"";
				}
			}
			
			if(empty($user)) {
				$rate_display	=	0;
				$show_jobs		=	0;
			} else {
				$rate_display	=	$user[0]['display_rate'];
				$show_jobs		=	$user[0]['show_jobs'];
			}
			
			/****Check user upto here*******/
			/**get user slip processed information**/
			if(!empty($user[0]['id'])) {
				$user_slip_info	=	$admin_model->get_user_slip_information($user_id);
			} else {
				$user_slip_info	=	"";
			}
			$action		=	"edit";
			$user_type	=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
			$header     =	View::factory("admin/header-list")
										->set('id',  empty($user[0]['id'])?null:$user[0]['id'])
										->set('users', $admin_model->get_users($user_type))
										->set('user_name', ucfirst($json_first_name))
										->set('selectedUser', $user_id)
										->set('action', $action)
										->set('card_type', $type);
										
			$this->template->content	=	View::factory('admin/edit')
											->set('firstname', $json_first_name)
											->set('lastname',  $json_last_name)
											->set('password',  empty($user[0]['password'])?null:$user[0]['password'])
											->set('email',     empty($user[0]['email'])?$json_user_email:$user[0]['email'])
											->set('type',      empty($user[0]['type'])?null:$user[0]['type'])
											->set('recordID',  empty($user_id)?null:$user_id)
											->set('id',  empty($user[0]['id'])?null:$user[0]['id'])
											->set('rate_display', $rate_display)
											->set('show_jobs', $show_jobs)
											->set('user_slip_info', $user_slip_info)
											->set('type',  $type)
											->set('error', empty($form_error)?null:$form_error)
											->set( 'header_user_list',	$header);
		} catch(Exception $e){
			die($e->getMessage());
		}
	}								

	/**
	 * @Function : action_reload 
	 * @Description: Reload the user account creation form.
	 */
	public function action_reload($user_id="", $form_error = "")
	{
		try{
			//$this->check_admin();
			if(empty($user_id))
			{
				Request::instance()->redirect(SITEURL.'/admin');	
			}
			
			$admin_model		= 	new Model_Admin;
			$json				= 	new Model_Json;
			$employee_m 		= 	new Model_Employee;
			$vendor_m			=	new Model_Vendor;
			$dharmausers_m		=	new Model_Dharmausers;
			
			$user				= 	array();
			$user 				= 	$dharmausers_m->get_user_info($user_id, $_SESSION['company_id']);
			
			// $json->file_content	= $_SESSION['Employees'];
			// $emp_user_info		= $json->JSON_Query(array("*"), array("RecordID" => $user_id)); // check if input id belongs to valid employee..
			$emp_user_info		=	$employee_m->get_employee($user_id, $_SESSION['company_id']);
			
			
			// $json->file_content	= $_SESSION['Contracts'];
			// $contract_user_info	= $json->JSON_Query(array("*"), array("RecordID" => $user_id)); // check if input id belongs to valid contract..
			$contract_user_info	= $vendor_m->get_vendor($user_id, $_SESSION['company_id']);
			
			if(empty($emp_user_info) AND empty($contract_user_info))
			{
				Request::instance()->redirect(SITEURL.'/admin/users?fail=2');	
			}
			
			/****Check whether user is Employee or Contractor*******/
			if(!empty($emp_user_info)) // employee user
			{
				$type			=	"Employee";
				$emp_user		=	array_merge(array(), $emp_user_info);
				if(isset($emp_user[0]['first_name']))
				{
					$json_first_name =	$emp_user[0]['first_name'];
					$json_last_name	 =	$emp_user[0]['company_or_last_name'];//"";
				}
				else
				{
					$json_first_name =	$emp_user[0]['company_or_last_name'];
					$json_last_name	 =	"";
				}
				if(isset($emp_user[0]['email']))
				{
					$json_user_email	=	$emp_user[0]['email'];
				} else {
					$json_user_email	=	"";
				}
			} elseif(!empty($contract_user_info)) {	// contractor(vendor) user
				$type			=	"Vendor";
				$contract_user	= 	array_merge(array(), $contract_user_info);
				if(isset($contract_user[0]['first_name'])) // check if field has first name or not
				{
					$json_first_name =	$contract_user[0]['first_name'];
					$json_last_name	 =	$contract_user[0]['company_or_last_name'];//"";
				}
				else
				{
					$json_first_name =	$contract_user[0]['company_or_last_name'];
					$json_last_name	 =	"";
				}
				if(isset($contract_user[0]['email']))
				{
					$json_user_email	=	$contract_user[0]['email'];
				} else {
					$json_user_email	=	"";
				}
			}
			
			if(empty($user)) {
				$rate_display	=	0;
				$show_jobs		=	0;
			} else {
				$rate_display	=	$user[0]['display_rate'];
				$show_jobs		=	$user[0]['show_jobs'];
			}
			
			/****Check user upto here*******/
			/**get user slip processed information**/
			if(!empty($user[0]['id'])) {
				$user_slip_info	=	$admin_model->get_user_slip_information($user_id);
			} else {
				$user_slip_info	=	"";
			}
			$action		=	"edit";
			$user_type	=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
										
			$content	=	View::factory('admin/reload')
											->set('firstname', $json_first_name)
											->set('lastname',  $json_last_name)
											->set('password',  empty($user[0]['password'])?null:$user[0]['password'])
											->set('email',     empty($user[0]['email'])?$json_user_email:$user[0]['email'])
											->set('type',      empty($user[0]['type'])?null:$user[0]['type'])
											->set('recordID',  empty($user_id)?null:$user_id)
											->set('id',  empty($user[0]['id'])?null:$user[0]['id'])
											->set('rate_display', $rate_display)
											->set('show_jobs', $show_jobs)
											->set('user_slip_info', $user_slip_info)
											->set('type',  $type)
											->set('error', empty($form_error)?null:$form_error);
		} catch(Exception $e){
			die($e->getMessage());
		}							
	}	
	
	/*
	 * @Function 	: action_create
	 * @Decription	: Display the Registration.
	 */
	public function action_create()
	{  
		$this->check_admin();
		if(empty($_POST['user_id']))
		{
			Request::instance()->redirect(SITEURL.'/admin');
		}
		$admin_model = new Model_Admin;
		$validations = new Model_Validations;
		$json		 = new Model_Json;
		$dropbox	 = new Model_Dropbox;
		$result 	 = array();
		$fail		 = false;
		try
		{
			$validations->validate_registration($_POST); // validation of fields
		} 
		catch(Exception $e)
		{
			if(!empty($_POST['id'])) {
				$user_slip_info	=	$admin_model->get_user_slip_information($_POST['user_id']);
			} else {
				$user_slip_info	=	"";
			}
			$form_error		= 	$e->getMessage();
			$action			=	"view";
			$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
			$header     	=	View::factory("admin/header-list")
										->set('users', $admin_model->get_users($user_type))
										->set('user_name', empty($user[0]['FirstName'])?null:ucfirst($user[0]['FirstName']))
										->set('selectedUser', $_POST['user_id'])
										->set('action', $action);
			$this->template->content	=	View::factory('admin/edit')
											->set('firstname', empty($_POST['FirstName'])?null:$_POST['FirstName'])
											->set('lastname',  empty($_POST['LastName'])?null:$_POST['LastName'])
											->set('email',     empty($_POST['UserEmail'])?null:$_POST['UserEmail'])
											->set('password',     "")
											->set('id',  empty($_POST['id'])?null:$_POST['id'])
											->set('type',      empty($_POST['type'])?null:$_POST['type'])
											->set('recordID',  empty($_POST['user_id'])?null:$_POST['user_id'])
											->set('rate_display', empty($_POST['rate_display'])?0:1)
											->set('show_jobs', empty($_POST['show_job'])?0:1)
											->set('user_slip_info', $user_slip_info)
											->set('error', $form_error)
											->set( 'header_user_list',	$header);
			$this->template->title 		=   "Dharma Admin - Edit User";
			$fail	=	true; 
		}
		if(!$fail) // if validation is success
		{
			$firstname			=	$_POST['FirstName'];
			$lastname			=	$_POST['LastName'];
			
			$params = array(
							'FirstName' 	=> $firstname,
							'LastName' 		=> $lastname,
							'Email' 		=> $_POST['UserEmail'],
							'Password' 		=> $this->temp_rand_password(),	
							'RecordID' 		=> $_POST['user_id'],
							'Type' 			=> $_POST['type'],
							'rate_display' 	=> empty($_POST['rate_display'])?0:$_POST['rate_display'],
							'show_jobs' 	=> empty($_POST['show_job'])?0:$_POST['show_job']
						);
						
			/*
			 * Make call to model to insert data.
			 * Save slip event then based on the slip id save the start time and end time. 
			 */
			try
			{ 
				$return_value	=	$admin_model->update_employees($params, 1); // update employee details in database
				/*******Update latest session from dropbox*******/
				if($return_value) 
				{
					$json->filter_json_array();
					if($return_value == 2) {
						$statusMessage	=	"4";
					} else {
						$statusMessage 	=	"1";
					}
					$user_id	=	$_POST['user_id'];
					Request::instance()->redirect(SITEURL.'/admin/edit/'.$user_id.'?statusMessage='.$statusMessage);
				}
				else
				{
					$statusMessage ='3';
					$user_id	=	$_POST['user_id'];
					Request::instance()->redirect(SITEURL.'/admin/edit/'.$user_id.'?statusMessage='.$statusMessage);
				}
				/*******session overwritten*******/
			}
			catch(Exception $e)
			{
				$statusMessage = '3';
				$this->action_edit($_POST['user_id'], $e->getMessage());
			}
		}
	}
	
	/**
	 * @Access		:	private
	 * @Method		:	temp_rand_password
	 * @Description	:	Function to generate temporary random password
	 * @Return		:	string
	 */
	private function temp_rand_password()
	{
		$chars = "abcdefghijkmnopqrstuvwxyz023456789";
	    srand((double)microtime()*1000000);
	    $i = 0;
	    $pass = '' ;
	    while ($i <= 7) {
	        $num = rand() % 33;
	        $tmp = substr($chars, $num, 1);
	        $pass = $pass . $tmp;
	        $i++;
	    }
	    return $pass;
	}

	/**
	 * @Access		:	Public
	 * @Function 	: 	action_view
	 * @Decription	: 	Display the User infromation.
	 */
	public function action_view($user_id="")
	{
		$this->check_admin();
		if(empty($user_id))
		{
			Request::instance()->redirect(SITEURL.'/admin');	
		}
		$this->template->title 		= "Dharma Admin - View User";
		$admin_model	= new Model_Admin;
		$json			= new Model_Json;
		$employee_m		= new Model_Employee;
		$vendor_m		= new Model_Vendor;
		$dharmausers_m 	= new Model_Dharmausers;
		
		$user			= array();
		$user 			= $dharmausers_m->get_user_info($user_id, $_SESSION['company_id']);
		if(empty($user)) {
			Request::instance()->redirect(SITEURL.'/admin/edit/'.$user_id);
		}
		try {
			
			// $json->file_content	= $_SESSION['Employees'];
			// $emp_user_info		= $json->JSON_Query(array("*"), array("RecordID" => $user_id)); // check if input id belongs to valid employee..
			$emp_user_info		=	$employee_m->get_employee($user_id, $_SESSION['company_id']);
			
			// $json->file_content	= $_SESSION['Contracts'];
			// $contract_user_info	= $json->JSON_Query(array("*"), array("RecordID" => $user_id)); // check if input id belongs to valid contract..
			$contract_user_info	= $vendor_m->get_vendor($user_id, $_SESSION['company_id']);
			if(empty($emp_user_info) AND empty($contract_user_info))
			{
				Request::instance()->redirect(SITEURL.'/admin/users?fail=2');
			}
			/****Check whether user is Employee or Contractor*******/
			if(!empty($emp_user_info))
			{
				$type		=	"Employee";
				$emp_user	= 	array_merge(array(), $emp_user_info);
			}
			elseif(!empty($contract_user_info))
			{
				$type			=	"Vendor";
				$contract_user	= 	array_merge(array(), $contract_user_info);
			}
			/*
			/****Check user upto here*******/
			$action			=	"view";
			$user_type		=    empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
			$header      	=	View::factory("admin/header-list")
											->set('users', $admin_model->get_users($user_type))
											->set('user_name', empty($emp_user[0]['first_name'])?ucfirst($contract_user[0]['company_or_last_name']):ucfirst($emp_user[0]['first_name']))
											->set('selectedUser', $user_id)
											->set('action', $action)
											->set('card_type', $type);
	
			$this->template->content	=	View::factory('admin/view')
											->set('firstname', empty($user[0]['first_name'])?null:$user[0]['first_name'])
											->set('lastname',  empty($user[0]['last_name'])?null:$user[0]['last_name'])
											->set('email',     empty($user[0]['email'])?null:$user[0]['email'])
											->set('password',  empty($user[0]['password'])?null:$user[0]['password'])
											->set('type',      empty($user[0]['type'])?null:$user[0]['type'])
											->set('user_name', empty($emp_user[0]['first_name'])?ucfirst($contract_user[0]['company_or_last_name']):ucfirst($emp_user[0]['first_name']))
											->set('recordID',  empty($user[0]['record_id'])?$user_id:$user[0]['record_id'])
											->set('type',  $type)
											->set( 'header_user_list',	$header);
		} catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function 	: 	action_delete
	 * @Decription	: 	Delete the user details from Employee.json and DharmaUsers.json.
	 */
	public function action_delete()
	{
		$dharmausers_m = new Model_Dharmausers;
		$this->check_admin();
		if(empty($_POST['user_id']))
		{
			Request::instance()->redirect(SITEURL.'/admin');	
		}
		$admin_model	= new Model_Admin;
		$user			= array();
		$user_id		= $_POST['user_id'];
		try
		{
			$true 	= $dharmausers_m->delete_user($user_id);
			$true	=	true;
		}
		catch(Exception $e)
		{
			$true	=	false;
		}
		
		Request::instance()->redirect(SITEURL.'/admin');	
	}
	/**
	 * @Access		:	Public
	 * @Function 	: 	check_admin
	 * @Decription	: 	checks for the admin session.
	 */
	public function check_admin($flag = null){
		
		if(empty($_SESSION['admin_email']) && $flag!= '1'){ // redirect to login page if user is not logged in
			request::instance()->redirect(SITEURL);
		} 
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_home
	 * @Description	:	Super admin home page, this page shows list of company is left panel add company form on right side.
	 */
	public function action_home($request="")
	{
		if(empty($_SESSION['superadmin_session'])) {
			request::instance()->redirect(SITEURL.'/admin');
		}
		$_SESSION['selected_link_type']	=	2;
		$admin_model	= 	new Model_Admin;
		$xmlrpc			=	new Model_Xmlrpc;
		$this->template->title 		= "Dharma Super Admin";
		try {
			$company_list	=	$admin_model->get_company_list();
			$this->template->content 	= 	View::factory('admin/welcome')
														->set( 'superadmin',1)
														->set( 'company',View::factory('admin/company_list')->set('company_list', $company_list))
														->set( 'company_form',View::factory('admin/superadmin_home')
																				->set('slip_details',$this->get_processed_slip_details())
																				->set('company_list', $company_list));
		} catch(Exception $e) {die($e->getMessage());}
		}
	
	/**
	 * @Access		:	Private
	 * @function	:	get_processed_slip_details
	 * @description	:	get full details about activity slips
	 */
	private function get_processed_slip_details()
	{
		try{
			$admin_model		= 	new Model_Admin;
			$company_id			=	isset($_POST['company'])?$_POST['company']:0;
			$total_slips		=	$admin_model->get_total_slips_count($company_id);
			$synced_slips		=	$admin_model->get_synced_slips_count($company_id);
			$tstr				=	mktime(0, 0, 0, date('m'), date('d') - date('w'), date('Y'));
			$tend				=	mktime(0, 0, 0, date('m'), date('d') - date('w')+7, date('Y'));
			$start_date			=	date("Y-m-d",$tstr)." 00:00:00";
			$end_date			=	date("Y-m-d",$tend). " 23:59:59";
			$week_total_slips	=	$admin_model->get_total_slips_in_week($start_date,$end_date,$company_id);
			$day_start			=	date("Y-m-d")." 00:00:00";
			$day_end			=	date("Y-m-d")." 23:59:59";
			$day_total_slips	=	$admin_model->get_total_slips_in_week($day_start,$day_end,$company_id);
			$slip_info[0]['total_slips']	=	$total_slips;
			$slip_info[0]['synced_slips']	=	$synced_slips;
			$slip_info[0]['unsynced_slips']	=	$total_slips-$synced_slips;
			$slip_info[0]['week_total']		=	$week_total_slips;
			$slip_info[0]['day_total']		=	$day_total_slips;
			return $slip_info;
		} catch(Exception $e){
			 die($e->getMessage());
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_company_add
	 * @Description	:	Function to add company
	*/
	public function action_company_add()
	{
		$this->check_admin();
		$admin_model	= 	new Model_Admin;
		$validate		=	new Model_Validations;
		$xmlrpc			=	new Model_Xmlrpc;
		//$this->template->title 		= "Dharma Super Admin";
		$token			=	false;
		$status			=	false;
		try
		{
			$validate->validate_user_update($_POST);
			// Call API to update customer details
			if(!empty($_POST['company_id'])) {
				if(isset($_POST['offline'])) { 
					$reference_id	=	$admin_model->get_offline_company_service_token($_POST['company_id']);
				} else {
					$reference_id	=	$admin_model->get_service_token($_POST['company_id']);
				}
				if($reference_id != "") {
					$country_value	=	$admin_model->get_country_name($_POST['country']);
					$update_rerun	=	$xmlrpc->update_customer_api($_POST, $reference_id, $country_value);
					if($update_rerun[0]['response_status'] == 0) {
						throw new Exception($update_rerun[0]['response_description']);
					}
				}
			}
			
			$status	=	$admin_model->submit_company_info($_POST);
			
			$status	=	true;
			if(empty($_POST['company_id']))
			{
				request::instance()->redirect(SITEURL.'/admin/home/2');
			}
			else
			{
				request::instance()->redirect(SITEURL.'/admin/home/4');
			}
		}
		catch(Exception $e)
		{
			$edit_company_view			=	empty($_SESSION['readonly_super_admin'])?'admin/new_company':'admin/readonly_admin_view';
			if(empty($_POST['company_id'])) // check if adding new company
			{

				$this->template->content 	= 	View::factory('admin/welcome')
													->set( 'superadmin',1)
													->set( 'company',View::factory('admin/company_list')->set('company_list',$admin_model->get_company_list()))
													->set( 'company_form',View::factory($edit_company_view)
																								->set( 'form', $_POST)
																								->set('payment_stream', $xmlrpc->get_signup_plans())
																								->set('country_list',$admin_model->get_country_list())
																								->set( 'edit', '0')
																								->set('company_user_info',$admin_model->get_company_user_info($_POST['company_id']))
																								->set('current_plan', $admin_model->get_current_plan($_POST['company_id']))
																								->set('company_active_url', $admin_model->get_company_active_url($_POST['company_id'], 0))
																								->set('error',$e->getMessage())
													);
			}
			else // to edit company
			{
				$company_info	=	$admin_model->get_admin_settings($_POST['company_id']); // get company details from table.
				if($company_info)
				{
					$dropbox	=	$admin_model->get_company_info($_POST['company_id']);
					$this->template->content 	= 	View::factory('admin/welcome')
														->set( 'superadmin',1)
														->set( 'company',View::factory('admin/company_list')->set('company_list',$admin_model->get_company_list())
																											->set('selectedCompany', $_POST['company_id']))
														->set( 'company_form',View::factory($edit_company_view)->set( 'form', $company_info)
																												->set('payment_stream', $xmlrpc->get_signup_plans())
																												->set('country_list',$admin_model->get_country_list())
																												->set( 'error', $e->getMessage())
																												->set('dropbox',$dropbox[0])
																												->set('company_user_info',$admin_model->get_company_user_info($_POST['company_id']))
																												->set('current_plan', $admin_model->get_current_plan($_POST['company_id']))
																												->set('company_active_url', $admin_model->get_company_active_url($_POST['company_id'], 0))
																												->set( 'edit', '1')
																												->set( 'edit', '1'));
				}
				else
				{
					request::instance()->redirect(SITEURL.'/admin/home');
				}
			}
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_edit_company
	 * @Description	:	Display edit company form
	 * @Param		:	company_id 
	*/
	public function action_edit_company($company_id)
	{
		$this->check_admin();
		$_SESSION['selected_link_type'] = 1;
		$admin_model	= 	new Model_Admin;
		$xmlrpc			=	new Model_Xmlrpc;
		$this->template->title 	=   "Dharma Super Admin";
		
		$company_info	=	$admin_model->get_company_info($company_id); // get company details from database
		$form			=	$admin_model->get_admin_settings($company_id); // get company admin user details
		
		if(empty($form))
		{
			request::instance()->redirect(SITEURL.'/admin/home');
		}try {
		if($company_info)
		{
			$edit_company_view			=	empty($_SESSION['readonly_super_admin'])?'admin/new_company':'admin/readonly_admin_view';
			$this->template->content 	= 	View::factory('admin/welcome')
													->set('company_info', $company_info[0])
													->set( 'superadmin',1)
													->set( 'company',View::factory('admin/company_list')->set('company_list',$admin_model->get_company_list())
																									    ->set('selectedCompany', $company_id))
													->set( 'company_form',View::factory($edit_company_view)->set( 'form', $form)
																										    ->set('dropbox',$company_info[0])
																											->set('country_list',$admin_model->get_country_list())
																											->set('company_user_info',$admin_model->get_company_user_info($company_id))
																											->set('current_plan', $admin_model->get_current_plan($company_id))
																											->set('company_active_url', $admin_model->get_company_active_url($company_id, 0))
																											->set( 'edit', '1'));
		}
		else // redirect to home image if no company to display
		{
			request::instance()->redirect(SITEURL.'/admin/home');
		}} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_delete_company
	 * @Description	:	To delete a company from the list.
	 * @Param		:	company_id
	*/
	public function action_delete_company($company_id)
	{
		$this->check_admin();
		$admin_model	= 	new Model_Admin;
		$xmlrpc			=	new Model_Xmlrpc;
		$this->template->title 	=   "Dharma Super Admin";
		$response		=	false;
		try
		{
			$response		=	$xmlrpc->delete_company_paystream($company_id);
			if($response) {
				$status		=	$admin_model->delete_company($company_id);
			}
		}
		catch(Exception $e)
		{ die($e->getMessage());
			request::instance()->redirect(SITEURL.'/admin/home');
		}
		if($status)
		{
			request::instance()->redirect(SITEURL.'/admin/home/3');
		}
		else
		{
			request::instance()->redirect(SITEURL.'/admin/home');
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_key_entry
	 * @Description	:	Key entry while company logging in to the admin system for the first time. (consumer key and consumer secret)
	*/
	public function action_key_entry()
	{
		$admin_model	=	new Model_Admin;
		if(empty($_POST))
		{
			request::instance()->redirect(SITEURL.'/admin');
		}
		try
		{
			$data	=	$admin_model->save_consumer($_POST); // save consumer key into the database
			if($data)
			{
				$this->action_login(null, "autologin", $data[0]); // and then login automatically
			}
		}
		catch(Exception $e)
		{
			echo $e->getMessage();
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_signup
	 * @Description	:	Company signup form
	*/
	public function action_signup()
	{
		$this->signup_form();
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	signup_form
	 * @Description	:	Display registration form
	 * @Params		:	array,string
	*/
	private function signup_form($form_data=null, $error=null)
	{
		$admin_model	=	new Model_Admin;
		$xmlrpc			=	new Model_Xmlrpc;
		if(empty($form_data))
		{
			$form_data	=	null;
			$error		=	null;
		} 
		try {
			if(isset($_SESSION['company_id']))
			{
				$form_data		=		$admin_model->get_admin_settings($_SESSION['company_id']);
				$this->template->content	= 	View::factory('admin/signup')
														->set('country_list',$admin_model->get_country_list())
														->set('payment_stream', $xmlrpc->get_signup_plans())
														->set('form', $form_data)
														->set('error', $error);
			}
			else { 
				$this->template->content	= 	View::factory('admin/signup')
														->set('country_list',$admin_model->get_country_list())
														->set('payment_stream', $xmlrpc->get_signup_plans())
														->set('form', $form_data)
														->set('error', $error);
			}
		} catch(Exception $e) {
			$this->template->content	= 	View::factory('admin/signup')
													->set('country_list',$admin_model->get_country_list())
													->set('payment_stream', "")
													->set('form', $form_data)
													->set('error', $e->getMessage());
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_register
	 * @Description	:	submit registration form
	 */
	public function action_register()
	{
		$admin_model	=	new Model_Admin;
		$validate		=	new Model_Validations;
		$xmlrpc			=	new Model_Xmlrpc;
		$status			=	false;
		$fail			=	false;
		$token			=	false;

		try 
		{
			$validate->validate_user_registration($_POST);
		} 
		catch(Exception $e)
		{
			$fail	=	true;
			$this->signup_form($_POST, $e->getMessage());
			$result[0]['error']	=	1;
			$result[0]['error_message']	=	$e->getMessage();
		}
		if(!$fail)
		{
			try
			{
				if(isset($_POST['plan']) && $_POST['plan'] == 1) {
					$status	=	$admin_model->submit_company_info($_POST);
					if($status)
					{
						request::instance()->redirect(SITEURL.'/admin/success');
						//$result[0]['success']	=	1;
					}
				} 
				else {
					$token		=	$xmlrpc->get_token_value($_POST);
					$_SESSION['company_signup_data']	=	$_POST;
					if(!empty($token)) {
						$this->payment_ach_form($_POST, $xmlrpc, $token['id']);
					} else {
						throw new Kohana_Exception(Kohana::message('error', 'service_token'));
					}
				}$status	=	true;
			}
			catch(Exception $e)
			{
				$result[0]['error']	=	1;
				$result[0]['error_message']	=	$e->getMessage();
				return $this->signup_form($_POST, $e->getMessage());
			}
			
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_success
	 * @Description	:	Success page after finishing the registration page.
	 */
	public function action_success()
	{
		$this->template->title 		=   "Dharma Company Signup";
		$this->template->content	= 	View::factory('admin/success');
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_activation
	 * @Description	:	Function to display company activate form.
	 * @Params		:	string,int
	 */
	public function action_activation($active_id, $redirect_source=0)
	{
		$admin_model	=	new Model_Admin;
		if(empty($active_id)) {
			$this->check_activation_return_url($redirect_source);
		}
		if(empty($_SESSION['log_user_activation_id'])) {
			$_SESSION['log_user_activation_id']	=	$active_id;
		}
		if($redirect_source == '9' || $redirect_source == '8')
			$this->dropbox_access_status	=	0;
		else{
			$this->dropbox_access_status	=	$redirect_source;
		}
		
		if($redirect_source == '9'){
			$this->activate_company_form($active_id,'','Please give access to AccountEdge Cloud to connect to DROPBOX');	
		} else if($redirect_source == '8'){
			$this->activate_company_form($active_id,'','Device name not found. Please provide a valid device name');
		}else {
			$this->activate_company_form($active_id);
		}
		
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	activate_company_form
	 * @Description	:	Company activation form
	 * @Params		:	string,array,string
	 */
	private function activate_company_form($active_id, $form_data=null, $error=null)
	{ 	
		if(empty($form_data))
		{
				$form_data	=	null;
				//$error		=	null;
		}
		if(empty($error))
		{
			$error = null;
		}
		
		$this->template->title 		=   "Dharma Activate Company";	
		$this->template->content	= 	View::factory('admin/activate')
											->set('activate_id', $active_id)
											->set('form', $form_data)
											->set('error', $error)
											->set('access_status', $this->dropbox_access_status);
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_activateuser_old(version 0)
	 * @Description	:	activate the company by linking to dropbox account.
	 */
	public function action_activateuser_old()
	{
		$admin_model	=	new Model_Admin;
		$validate		=	new Model_Validations;
		$data_model		=	new Model_Data;
		$user_model		=	new Model_User;
		$login			=   new Model_login;
		$status			=	false;
		$true_save		=	false;
		$true_sync		=	false;
		try {
		if(empty($_POST))
		{
			request::instance()->redirect(SITEURL.'/admin');
		}
		try {
			$status	=	$validate->validate_dropbox_account($_POST); // validate drop-box credential and device name
		}
		catch(Exception $e)
		{
			return $this->activate_company_form($_POST['activate_id'], $_POST, $e->getMessage());
		}
		if($status)
		{
			//save dropbox details in session for futue use
			$_SESSION["dropbox_username"]	=	 $_POST['DropboxEmail'];
			$_SESSION["dropbox_password"]	=	 $_POST['DropboxPassword'];
			$db_device['device_name']	=	$_POST['device_name'];
			try {
				$data_model->define_dropbox_constants($db_device);
				if($_POST['redirect_source'] == 0) {
					$true_save	=	$admin_model->save_dropbox_info($_POST);
				}
				
				if($_POST['redirect_source'] == 0 || $_POST['redirect_source'] == 2) { // check if admin login or activate after registration - 2- admin login, 0- after signup 
					$admin_user		=	$admin_model->get_company_by_activity($_POST['activate_id']);
					if(empty($admin_user) || $_POST['activate_id'] != $_SESSION['log_user_activation_id']) { // check if same user tried to login first
						session_destroy();
						request::instance()->redirect(SITEURL.'/admin');
					} else {
						$_SESSION['user_name']			=	$admin_user[0]['username'];
						$_SESSION['company']			=	$admin_user[0]['company_name'];
						$_SESSION['company_id']			=	$admin_user[0]['company_id'];
						$_SESSION['admin_email']		=	$admin_user[0]['username'];
						if($user_model->free_plan_user($admin_user[0]['company_id'])) {  // check if this user is freely registered then get the expire day information
							$_SESSION['free_user']	=	1;
							$_SESSION['days_left']	=	$admin_model->expire_day_left($admin_user[0]['company_id']);
						}
						$admin_model->update_dropbox_tokens_by_company_id($_POST['device_name'], $_SESSION['oauth_tokens'], $admin_user[0]['company_id']);
					}
					
					$_SESSION['employee_id']   		=	0; // As its admin login - we will reset $_SESSION['employee_id']
				} else { // check if user login (employee/vendor)
					$result	=	$user_model->get_user_by_logged_id($_SESSION['logged_user_id']);
					if($result == false) {
						$this->check_activation_return_url($_POST['redirect_source']);
					}
					$_SESSION['employee_id']   		=	$result[0]['record_id'];
					$_SESSION['employee_name']  	=	$result[0]['first_name'];
					$_SESSION['employee_lastname']  =	$result[0]['last_name'];
					if($result[0]['company_id']) {	// get the company details
						$comapny_info	=	$user_model->get_company($result[0]['company_id']);
					}
					$_SESSION['company']			=	$comapny_info[0]['name'];
					$_SESSION['company_id']			=	$comapny_info[0]['id'];	
					$_SESSION['display_rate']		=	$result[0]['display_rate'];
					$_SESSION['synced_slips_view']	=	0; //display not synced slips
					$_SESSION['sync_alert_message']	=	1;
					$_SESSION['User_type']			=	($result[0]['type'] == "Employee") ? "Employees":"Contractor"; // store filename based on user type.
					
					if($user_model->free_plan_user($comapny_info[0]['id'])) { // check if this user is freely registered then get the expire day information
						$_SESSION['free_user']	=	1;
						$_SESSION['days_left']	=	$admin_model->expire_day_left($comapny_info[0]['id']);
					}
					$admin_model->update_dropbox_tokens_by_company_id($_POST['device_name'], $_SESSION['oauth_tokens'], $comapny_info[0]['id']);
					$user_model->create_user_log();
				}
			
				try {
					$val_status	=	$user_model->create_json_sessions();
				} catch(Exception $e) {
					return $this->activate_company_form($_POST['activate_id'], $_POST, $e->getMessage());
				}
				if($val_status) {
					$this->check_activation_return_url($_POST['redirect_source']);
				}
			}catch(Exception $e)
			{
				// go back to activate form if credentials are invalid
				return $this->activate_company_form($_POST['activate_id'], $_POST, $e->getMessage());
			}
		}
			if($true_save){
				// get the company and admin login details from database and login automatically to the admin system.
				$company_info	=	$admin_model->get_company_by_activity($_POST['activate_id']);
				if(!empty($company_info)){
					$this->action_login(null, "autologin", $company_info[0]); // and then login automatically
				} else {
					request::instance()->redirect(SITEURL.'/admin');
				}
			}
		}catch(Exception $e) {die($e->getMessage());}
	}
	
   /**
	 * @Access		:	Public
	 * @Function	:	action_activateuser
	 * @Description	:	activate the company by linking to dropbox account.
	 * @Params		:	string,string
	 */
	public function action_activateuser($device,$act_id)
	{
		$admin_model	=	new Model_Admin;
		$validate		=	new Model_Validations;
		$data_model		=	new Model_Data;
		$user_model		=	new Model_User;
		$login			=   new Model_login;
		$status			=	false;
		$true_save		=	false;
		$true_sync		=	false;
		try {
			if(!empty($device) && !empty($act_id)){
				$_SESSION['device_name']			=	$device;
				$_SESSION['log_user_activation_id']	=	$act_id;
			}
			$session 	= new DropboxSession(CONSUMER_KEY,CONSUMER_SECRET,'dropbox');
			$client 	= new DropboxClient($session);
			$config["app"]["root"] = ((!empty($_SERVER["HTTPS"])) ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . "/admin/callback_url_redirect/";

			if(isset($_SESSION['oauth_token_secret'])){unset($_SESSION['oauth_token_secret']);}
		
 	 		if ($request_token = $session->obtainRequestToken()) {
	            // The request token must be subdivided in the two components oauth_token_secret and oauth_token and kept in the session because is needed in the next step
	            parse_str($request_token, $token);
	          	$_SESSION['oauth_token_secret']		=	$token['oauth_token_secret'];
				
				$url = $session->buildAuthorizeURL(
													$token, 
	         									    $config["app"]["root"],
	 								                "en-US"
	         									  );
				request::instance()->redirect($url);
	     	}
	 	} catch(Exception $e) {die($e->getMessage());}
	}

   /**
	 * @Access		:	Public
	 * @Function	:	action_callback_url_redirect
	 * @Description	:	Function to retrieve the tokens from the dropbox (callback_url function)
	 */
	public function action_callback_url_redirect()
	{
		$admin_model	=	new Model_Admin;
		$validate		=	new Model_Validations;
		try {
			$session 	= new DropboxSession(CONSUMER_KEY,CONSUMER_SECRET,'dropbox');
			$client 	= new DropboxClient($session);
			if(isset($_GET['not_approved']) && $_GET['not_approved']){
				echo "<script type='text/javascript'>window.opener.dropboxAuthHanlder('not_approved',".json_encode($_SESSION).");window.close();</script>";
			}else {
				if(!empty($_GET["oauth_token"]) && !empty($_GET["uid"])) {
					
					$uid = $_GET["uid"];
	        		$token = array(
	        	    	"oauth_token" => $_GET["oauth_token"],
	        	    	"oauth_token_secret" => ""
	        		);
	
	        		if (!empty($_SESSION["oauth_token_secret"])) {
		    	   	        $token["oauth_token_secret"] 	=	$_SESSION["oauth_token_secret"];
		    	    }
						
					if ($access_token = $session->obtainAccessToken($token)) {
						parse_str($access_token, $token);
	            		$access_token = $token;
						unset($_SESSION['oauth_tokens']);
						$_SESSION['oauth_tokens']['token'] 			=	$token['oauth_token'];
						$_SESSION['oauth_tokens']['token_secret'] 	=	$token['oauth_token_secret'];
						$data['device_name']						=	$_SESSION['device_name'];
						$data['activate_id']						=	$_SESSION['log_user_activation_id'];
						$status										=	$validate->validate_dropbox_account($data);
						if($status){
							$true_save	=	$admin_model->save_dropbox_info($data);
							if($true_save){
								$details							=	$admin_model->get_login_details($data['activate_id']);
								if($details!= false){
									$_SESSION['hash_username']		=	$details['email'];
									$_SESSION['hash_password']		=	$details['password'];
									$_SESSION['new_activation_status']		=	1;
								}
								echo "<script type='text/javascript'>window.opener.dropboxAuthHanlder('success');window.close();</script>";
							}
						} else{
							echo "<script type='text/javascript'>window.opener.dropboxAuthHanlder('device_not_found',".json_encode($_SESSION).");window.close();</script>";
						}	
	       			 }
				}
			}
		} catch(Exception $e){die($e->getMessage());}
		
	}

	/**
	 * @Access		:	Private
	 * @Function	:	check_activation_return_url
	 * @Description	:	Function to check return url while doing dropbox activation
	 * @Params		:	int(optional)
	 */
	private function check_activation_return_url($redirect_index=0)
	{
		switch($redirect_index) {
			case 1:request::instance()->redirect(SITEURL.'/login');
			case 2:request::instance()->redirect(SITEURL.'/admin');
			default:request::instance()->redirect(SITEURL.'/admin');
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_company
	 * @Description	:	Display company details in edit form
	 */
	public function action_company()
	{
		$_SESSION['selected_link_type'] = 1;
		$admin_model	=	new Model_Admin;
		$company_id		=	$admin_model->get_company_default();
		if(empty($company_id))
			request::instance()->redirect(SITEURL.'/admin/home');
		else
			request::instance()->redirect(SITEURL.'/admin/edit_company/'.$company_id);
			
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	payment_ach_form
	 * @Description	:	Function to display the payment ach form
	 */	
	public function payment_ach_form($post, $xmlrpc, $token="", $error="")
	{
		$payment_method		=	$xmlrpc->payment_form();
		$this->template->content	= 	View::factory('admin/payment_form')
											->set('payment_url', $payment_method['result'])
											->set('error', $error);
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_payment
	 * @Description	:	Payment function
	 */
	public function action_payment()
	{ try {
		$xmlrpc			=	new Model_Xmlrpc;
		$payment_status = 	$xmlrpc->doPayment($_POST);
		$status			=	false;
		if($payment_status == 1) {
			$admin_model	=	new Model_Admin;
			$status			=	$admin_model->submit_company_info($_SESSION['company_signup_data']);
		} else {
			$status			=	false;
			$this->payment_ach_form($_SESSION['company_signup_data'], $_POST['token_id'], $payment_status);
		}
		if($status)
		{
			session_unset($_SESSION['company_signup_data']); 
			request::instance()->redirect(SITEURL.'/admin/success');
		}
	} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_signup_validate
	 * @Description	:	Function to validate the sign_up page.
	 */
	public function action_signup_validate($page)
	{
		$validate	 =	new Model_Validations;
		$admin_model =	new Model_Admin;
		$result		 =	array();
		//die($page);
		try 
		{ 
			$validate->validate_user_registration($_POST, $page);
			
			if($page == 1) {
				$result_company	=	$admin_model->get_user_offline_info($_POST);
				if(!empty($result_company)) {
					$result[0]['service_token']	=	$result_company[0]['service_token'];
				} else {
					$result[0]['service_token']	=	"";
				}
			}
			$result[0]['success']	=	1;
		} 
		catch(Exception $e)
		{
			if($e->getMessage() == "Email already exists in our records.") {
				if($page == 1) { 
					$result_company	=	$admin_model->get_user_offline_info($_POST);
					if(!empty($result_company)) {
						$result[0]['service_token']	=	$result_company[0]['service_token'];
						$result[0]['success']		=	1;
						echo json_encode($result); 
						die;
					} 
				}				
			}
			$result[0]['error']	=	1;
			$result[0]['error_message']	=	$e->getMessage();
		}
		echo json_encode($result); 
		die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_updatecard
	 * @Description	:	Function to update the card details.
	 */
	public function action_updatecard()
	{
		$this->check_admin();
		$xmlrpc			=	new Model_Xmlrpc;
		$admin_model	=	new Model_Admin;
		
		if(isset($_POST['card_name'])) {
			try {
				$updates_tatus	=	$xmlrpc->update_payment_info($_POST);
				if($updates_tatus) {
					$result			=	$xmlrpc->get_card_details();
					$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
					$update_card				= 	View::factory('admin/update_card')
															->set('card_details', $result)
															->set('message', $updates_tatus);
					
					$this->template->content 	= 	View::factory('admin/companysettings')
															->set('content', $update_card)
															->set('setting', $this->get_settings_content(2));
				} else {
					throw new Kohana_Exception("Error occured while updating credit card details. Please try again.");
				}
			} catch(Exception $e) {
				$result			=	$xmlrpc->get_card_details();
				$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
				$update_card				= 	View::factory('admin/update_card')
													->set('card_details', $result)
													->set('error', $e->getMessage());
				
				$this->template->content 	= 	View::factory('admin/companysettings')
															->set('content', $update_card)
															->set('setting', $this->get_settings_content(2));
			}
		} else {
			try { 
				
				$result			=	$xmlrpc->get_card_details();
				
				$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
				$update_card				= 	View::factory('admin/update_card')
														->set('card_details', $result);
				die($update_card);
				
				$this->template->content 	= 	View::factory('admin/companysettings')
															->set('content', $update_card)
															->set('setting', $this->get_settings_content(2));
			}
			catch(Exception $e) { //die("error ".$e->getMessage());
				$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
				$update_card				= 	View::factory('admin/update_card')
														->set('error',$e->getMessage())
														->set('card_details', null);
				die($update_card);
				
				$this->template->content 	= 	View::factory('admin/companysettings')
															->set('content', $update_card)
															->set('setting', $this->get_settings_content(2));
			}
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_submitcard
	 * @Description	:	Function to updating the credit card details.
	 */
	public function action_submitcard()
	{
		$this->check_admin();
		$xmlrpc			=	new Model_Xmlrpc;
		$admin_model	=	new Model_Admin;
		$result			=	$xmlrpc->get_card_details();
		try {
			$updates_tatus	=	$xmlrpc->update_payment_info($_POST);
			if($updates_tatus) {
				$result			=	$xmlrpc->get_card_details();
				$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
				$update_card				= 	View::factory('admin/update_card')
														->set('card_details', $result)
														->set('message', $updates_tatus);
				
				$this->template->content 	= 	View::factory('admin/companysettings')
															->set('content', $update_card)
															->set('setting', $this->get_settings_content(2));
			} else {
				throw new Kohana_Exception("Error occured while updating credit card details. Please try again.");
			}
		} catch(Exception $e) {
			$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
			$update_card				= 	View::factory('admin/update_card')
												->set('card_details', $result)
												->set('error', $e->getMessage());
			
			$this->template->content 	= 	View::factory('admin/companysettings')
															->set('content', $update_card)
															->set('setting', $this->get_settings_content(2));
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_changeplan
	 * @Description	:	Function to change the existing plan.
	 */
	public function action_changeplan($ref="", $onclick=0)
	{	if(isset($_GET['flag'])){
			$this->check_admin($_GET['flag']);
		} else {
			$this->check_admin();
		}
		$xmlrpc			=	new Model_Xmlrpc;
		$admin_model	=	new Model_Admin;
		$dharmausers_m	=	new Model_Dharmausers;
		if(!empty($ref) && $ref == 2) {
			try {
			$plan_change_msg			=	"You have successfully upgraded to";
			
			$upgrade_form		 	 	=	View::factory('admin/upgrade_plan')
															->set('payment_stream', $xmlrpc->get_signup_plans())
															->set('cur_plan', $admin_model->get_current_plan())
															->set('cur_plan_max_user', $admin_model->get_plan_user_limit())
															->set('user_limit', $admin_model->get_plan_user_limit())
															->set('total_users', $dharmausers_m->get_total_company_users())
															->set('new_company_info', null)
															->set('company_users', $dharmausers_m->get_timetracker_users())
															->set('plan_change_message', $plan_change_msg);	
			
			$this->template->content	= 	View::factory('admin/companysettings')
															->set('content', $upgrade_form)
															->set('setting', $this->get_settings_content(3));
			} catch(Exception $e) {die($e->getMessage());}
		} elseif(isset($_POST['plan'])) {
			try {
				if(empty($_POST['plan'])) throw new Exception("Please select new plan.");
				$result	=	$xmlrpc->change_plan_api($_POST, $_POST['upgrade_current_plan']);
				if($result == 2) { // show sign-up form if user is freely registered.
					$admin_model	=	new Model_Admin;
					$form_data		=		$admin_model->get_admin_settings($_SESSION['company_id']);
					$this->template->content	= 	View::factory('admin/signup')
														->set('country_list',$admin_model->get_country_list())
														->set('registered_free', 1)
														->set('form', $form_data)
														->set('company_id', $_SESSION['company_id'])
														->set('plan', $_POST['plan']);
				}
				elseif($result) {
					$admin_model	=	new Model_Admin;
						$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
						if($_POST['upgrade_current_plan']) {
							
						}
						$plan_change_msg			=	$_POST['upgrade_current_plan'] == 1?"You have successfully upgraded to":"You have successfully downgraded to";
						$upgrade_form		 	 	=	View::factory('admin/upgrade_plan')
															->set('payment_stream', $xmlrpc->get_signup_plans())
															->set('cur_plan', $admin_model->get_current_plan())
															->set('cur_plan_max_user', $admin_model->get_plan_user_limit())
															->set('success',$result)
															->set('user_limit', $admin_model->get_plan_user_limit())
															->set('total_users', $dharmausers_m->get_total_company_users())
															->set('new_company_info',  null)
															->set('company_users', $dharmausers_m->get_timetracker_users())
															->set('plan_change_message', $plan_change_msg);	

						$this->template->content	= 	View::factory('admin/companysettings')
															->set('content', $upgrade_form)
															->set('setting', $this->get_settings_content(3));
				}
			} catch(Exception $e) {
				$this->change_plan_form($xmlrpc, $e->getMessage(), $onclick);
			}
		}
		else { try {
				if(isset($_GET['flag']) && $_GET['flag']==1){
					request::instance()->redirect(SITEURL.'/admin/upgrade');
				} 
				$this->change_plan_form($xmlrpc, "", $onclick);
			} catch(Exception $e) {die($e->getMessage());}
		}
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	change_plan_form
	 * @Description	:	Form to change the plan.
	 */
	private function change_plan_form($xmlrpc, $error="", $onclick=0)
	{
		$admin_model	=	new Model_Admin;
		$dharmausers_m 	= 	new Model_Dharmausers;
		try {
			if(isset($error)) {
				$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
				$upgrade_form				= 	View::factory('admin/upgrade_plan')
													->set('payment_stream', $xmlrpc->get_signup_plans())
													->set('cur_plan', $admin_model->get_current_plan())
													->set('cur_plan_max_user', $admin_model->get_plan_user_limit())
													->set('company_users', $dharmausers_m->get_timetracker_users())
													->set('user_limit', $admin_model->get_plan_user_limit())
													->set('total_users', $dharmausers_m->get_total_company_users())
													->set('new_company_info',  $admin_model->get_new_company_info())
													->set('error', $error);
				
				if($onclick==1){
					die($upgrade_form);
				}
				$this->template->content	= 	View::factory('admin/companysettings')
															->set('content', $upgrade_form)
															->set('setting', $this->get_settings_content(3));
														
				
			} else {
				$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
				$upgrade_form				= 	View::factory('admin/upgrade_plan')
													->set('payment_stream', $xmlrpc->get_signup_plans())
													->set('company_users', $dharmausers_m->get_timetracker_users())
													->set('cur_plan_max_user', $admin_model->get_plan_user_limit())
													->set('user_limit', $admin_model->get_plan_user_limit())
													->set('total_users', $dharmausers_m->get_total_company_users())
													->set('new_company_info',  $admin_model->get_new_company_info())
													->set('cur_plan', $admin_model->get_current_plan());
				if($onclick==1){
					die($upgrade_form);
				}
				$this->template->content	= 	View::factory('admin/companysettings')
															->set('content', $upgrade_form)
															->set('setting', $this->get_settings_content(3));
			}
		} catch(Exception $e){ die($e->getMessage());}
		
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_register_free_user
	 * @Description	:	Function to register for free user.
	 */
	public function action_register_free_user()
	{
		$validate	=	new Model_Validations;
		$admin_model=	new Model_Admin;
		$result		=	array();
		try 
		{	
			$validate->validate_free_user_registration($_POST);
			$activation_id	=	$admin_model->register_free_user($_POST);
			$result[0]['success']	=	1;
			$result[0]['active_id']	=	$activation_id;
		} 
		catch(Exception $e)
		{
			$result[0]['error']	=	1;
			$result[0]['error_message']	=	$e->getMessage();
		}
		echo json_encode($result); 
		die;	
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_setup
	 * @Description	:	Function to display the settings page for user to update the dropbox device name.
	 * @Params		:	string,boolean
	 */
	public function action_setup($device,$flag=false)
	{
		$admin_model =	new Model_Admin;
		if(!empty($device)) {
			//die($_POST['device_name']);
			$validate	=	new Model_Validations;
			try {
			    if(!empty($device)) {
					$_SESSION['device_name']	=	$device;	
				}
				$session 	= new DropboxSession(CONSUMER_KEY,CONSUMER_SECRET,'dropbox');
				$client 	= new DropboxClient($session);
				$config["app"]["root"] = ((!empty($_SERVER["HTTPS"])) ? "https" : "http") . "://" . $_SERVER["HTTP_HOST"] . "/admin/update_dropbox/";
				
				if(isset($_SESSION['oauth_token_secret'])){unset($_SESSION['oauth_token_secret']);}		
	 	 		
				if ($request_token = $session->obtainRequestToken()) {
		            // The request token must be subdivided in the two components 
		            // oauth_token_secret and oauth_token and kept in the session
		            // because is needed in the next step
		            parse_str($request_token, $token);
		          	$_SESSION['oauth_token_secret'] = $token['oauth_token_secret'];
					$_SESSION['old_tokens']	=	$_SESSION['oauth_tokens'];
					$url = $session->buildAuthorizeURL(
		                $token, 
		                $config["app"]["root"],
		                "en-US");
		                 
			  	request::instance()->redirect($url);
				}
			} catch(Exception $e) {
				$dropbox	 	=  $admin_model->get_dropbox_info();
				$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
											
				$dropbox_setup			 = 	View::factory('admin/edit_dropbox')
												->set('error', $e->getMessage())
												->set('dropbox', $dropbox);
				
				$this->template->content = 	View::factory('admin/companysettings')
												->set('content', $dropbox_setup)
												->set('setting', $this->get_settings_content(4));
			}
		} else {
			$dropbox	 	=  $admin_model->get_dropbox_info();
			
			if($flag) {
				$success = true; 
			} else {
				$success = false; 
			}
			$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
			$header			=	View::factory("admin/header-list")
											->set('users', $admin_model->get_users($user_type))
											->set('setup', 1);
													
			$dropbox_setup	= 	View::factory('admin/edit_dropbox')
												->set('dropbox', $dropbox)
												->set('success',$success );
			die($dropbox_setup);
			$this->template->content = 	View::factory('admin/companysettings')
												->set('content', $dropbox_setup)
												->set('setting', $this->get_settings_content(4));
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_update_dropbox
	 * @Description	:	Function to update the dropbox device name after submitting.
	 */
	public function action_update_dropbox()
	{
		$admin_model	=	new Model_Admin;
		$validate		=	new Model_Validations;
		$data_model		=	new Model_Data;
		$user_model		=	new Model_User;
		$login			=   new Model_login;
		$status			=	false;
		$true_save		=	false;
		$true_sync		=	false;
		try {
			$session 	= new DropboxSession(CONSUMER_KEY,CONSUMER_SECRET,'dropbox');
			$client 	= new DropboxClient($session);
			if(isset($_GET['not_approved']) && $_GET['not_approved']){
				echo "<script type='text/javascript'>window.opener.editDropboxAuthHandler('not_approved');window.close();</script>";
			} else{
				 if(!empty($_GET["oauth_token"]) && !empty($_GET["uid"])) {
					$uid = $_GET["uid"];
		        	$token = array(
		         				   	"oauth_token" => $_GET["oauth_token"],
		            				"oauth_token_secret" => ""
		 				    	  );
					
			    	if (!empty($_SESSION['oauth_token_secret'])) {
						$token["oauth_token_secret"] = $_SESSION['oauth_token_secret'];
			    	}
										
		 			if ($access_token = $session->obtainAccessToken($token)) {
	        			parse_str($access_token, $token);
	            		$access_token = $token;
						unset($_SESSION['oauth_tokens']);
						$_SESSION['oauth_tokens']['token'] 			= 	$token['oauth_token'];
						$_SESSION['oauth_tokens']['token_secret'] 	= 	$token['oauth_token_secret'];
						$_POST['device_name']						=	$_SESSION['device_name'];
	 					$status										=	$validate->edit_drobox_validate($_POST);
						
	 					if($status){
							$true_save	=	$admin_model->update_dropbox($_POST);
	 						if($true_save){
	 							$details	=	$admin_model->get_login_details($_SESSION['company_id'],0);
								
	 							if($details!= false){
									$_SESSION['hash_username']		=	$details['email'];
									$_SESSION['hash_password']		=	$details['password'];
									$_SESSION['new_activation_status']		=	1;
								}
								echo "<script type='text/javascript'>window.opener.editDropboxAuthHandler('success');window.close();</script>";
							}	
						} else{
							$_SESSION['oauth_tokens']	=	$_SESSION['old_tokens'];
							echo "<script type='text/javascript'>window.opener.editDropboxAuthHandler('device_not_found');window.close();</script>";
						}
					}
				}
			}
		} catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_resync
	 * @Description	:	This function is used to sync again from the dropbox.
	 */
	public function action_resync()
	{
		$this->check_admin();
		$data	=	new Model_Data;
		$device['device_name']	=	$_SESSION['new_dropbox_device_name'];
		$data->define_dropbox_constants($device);
		request::instance()->redirect(SITEURL.'/admin/setup/1');
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_offlinecompany
	 * @Description	:	Function is used to fetch the data of a company who are not yet activated.
	 */
	public function action_offlinecompany($id)
	{
		$this->check_admin();
		$_SESSION['selected_link_type'] = 1;
		$admin_model	= 	new Model_Admin;
		$xmlrpc			=	new Model_Xmlrpc;
		$this->template->title 	=   "Dharma Super Admin";
		$company		=	$admin_model->get_offline_company_name($id);
		$company_info	=	$admin_model->get_offline_company_info($id); // get company details from database
		$edit_company_view		=	empty($_SESSION['readonly_super_admin'])?'admin/new_company':'admin/readonly_admin_view';
		if($company_info)
		{
			$this->template->content 	= 	View::factory('admin/welcome')
													->set('company_info', $company)
													->set( 'superadmin',1)
													->set('offline', 1)
													->set( 'company',View::factory('admin/company_list')
													->set('company_list',$admin_model->get_company_list())
												    ->set('selectedCompany', $id)
												    ->set('offline', 1))
													->set( 'company_form',View::factory($edit_company_view)
													->set('form',$company_info)
													->set('country_list',$admin_model->get_country_list())
													->set('current_plan', $admin_model->get_offline_company_plan($id))
													->set( 'edit', '1')
													->set('company_active_url', $admin_model->get_company_active_url($id, 1))
													->set('offline', '1'));
		}
		else // redirect to home image if no company to display
		{
			request::instance()->redirect(SITEURL.'/admin/home');
		}
	}	
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_deleteoffline
	 * @Description	:	Deleting the user who are not activated.
	 */
	public function action_deleteoffline($id)
	{
		$this->check_admin();
		$admin_model	= 	new Model_Admin;
		try {
			$admin_model->delete_offline_company($id);
			request::instance()->redirect(SITEURL.'/admin/home');
		} catch(Exception $e) {
			request::instance()->redirect(SITEURL.'/admin/home');
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_editusers
	 * @Description	:	To edit the users list.
	 */
	public function action_editusers($flag = 0)
	{
		$this->check_admin();
		$admin_model	= 	new Model_Admin;
		$dharmausers_m 	=	new Model_Dharmausers;	
		$edit_users				 =	View::factory('admin/editusers')
											->set('company_users', $dharmausers_m->get_timetracker_users())
											->set('user_limit', $admin_model->get_plan_user_limit())
											->set('total_users', $dharmausers_m->get_total_company_users());
		if($flag == "1") {
			die($edit_users);
		}
		$content = 	View::factory('admin/companysettings')
											->set('content', $edit_users)
											->set('setting', $this->get_settings_content(1));
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_changestatus
	 * @Description	:	To activate a user from admin section
	 */
	public function action_changestatus()
	{
		try {
			$result			=	array();
			$user_id		=	addslashes($_POST['user_id']);
			$current_status	=	$_POST['currentstatus'];
			$admin_model	= 	new Model_Admin;
			$dharmausers_m 	= 	new Model_Dharmausers;
		
			$status	=	$admin_model->change_status($user_id, $current_status);
			if($status == false) {
				$result[0]['success']	=	0;
			} else {
				$result[0]['success']	=	1;
				$total_users			=	$dharmausers_m->get_total_company_users();
				$result[0]['users']		=	$total_users[0]['total_users'];
				if($_POST['selected_plan'] != 0) {
					$limit_users			=	$admin_model->get_company_user_limit($_POST['selected_plan']);
					if($result[0]['users'] <= $limit_users[0]['user_limit']) {
						$result[0]['valid_user_count']	=	1;
					} else {
						$result[0]['valid_user_count']		=	0;
						$result[0]['plan_user_to_inactive']	=	$result[0]['users']-$limit_users[0]['user_limit'];
					}
				}
			}
			echo json_encode($result);die;
		} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_reset
	 * @Description	:	To reset the password of admin
	 */
	public function action_reset()
	{
		$this->check_admin();
		$admin_model			 = 	new Model_Admin;
		
		if(isset($_POST['password'])) {
			$valdiation_result	=	$admin_model->check_password($_POST['password']);
			if($valdiation_result) {
				$admin_model->database_delete_data_on_device_change($_SESSION['company_id']);
				$obj_user	=	new Model_User;
				$obj_user->create_customer_json_sessions();
				$reset				 	 =	View::factory('admin/reset')
												->set('success','Your account has been successfully reset');	
				$this->template->content = 	View::factory('admin/companysettings')
												->set('content', $reset)
												->set('setting', $this->get_settings_content(5));
			} else {
				$reset				 	 =	View::factory('admin/reset')
												->set('error','Incorrect Password');	
				$this->template->content = 	View::factory('admin/companysettings')
												->set('content', $reset)
												->set('setting', $this->get_settings_content(5));
			}
		} else {
			$reset				 	 =	View::factory('admin/reset');
			die($reset);
			$this->template->content = 	View::factory('admin/companysettings')
												->set('content', $reset)
												->set('setting', $this->get_settings_content(5));
		}									
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_downgrade
	 * @Description	:	To downgrade the user's plan.
	 */
	public function action_downgrade()
	{
		$admin_model		 = 	new Model_Admin;
		$downgrade_flag		 =	$admin_model->check_downgrade();
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_activate_all_user
	 * @Description	:	To activate all users in the company.
	 */
	public function action_activate_all_user()
	{
		$dharmausers_m 	=	new Model_Dharmausers;
		
		$result			=	array();
		$status			=	$dharmausers_m->activate_all_users();
		if($status) {
			$result[0]['success']	=	1;
		} else {
			$result[0]['success']	=	0;
		}
		echo json_encode($result);
		die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_deactivate_all_user
	 * @Description	:	To deactivate all users
	 */
	public function action_deactivate_all_user()
	{
		$dharmauser_m 	= new Model_Dharmausers;
		$result			=	array();
		$status			=	$dharmauser_m->deactivate_all_users();
		if($status) {
			$result[0]['success']	=	1;
		} else {
			$result[0]['success']	=	0;
		}
		echo json_encode($result);
		die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_settings
	 * @Description	:	Function to display the settings section of admin
	 */
	public function action_settings()
	{
		require_once Kohana::find_file('classes', 'library/Versioning');
		$version_lib 	= 	new Versioning;
		
		request::instance()->redirect(SITEURL.'/admin/sync');
		$this->check_admin();
		$admin_model	= 	new Model_Admin;
		$gatewayAch_m	= 	new Model_Gatewayach;
		$company_m		=	new Model_Company;
		$keys 			= 	array('ccp');
		$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
		$gateway_details=	$gatewayAch_m->get_merchant_personal_ach_details($_SESSION['company_id']);
		if(!empty($company_data)&&$company_data['ccp']=='1'){
			$ccp_enable = 1;
		} else {
			$ccp_enable	= 0;
		}
		$settings_content = 	View::factory('admin/settings')
										->set('last_sync', 		$admin_model->get_last_synced_date())
										->set('user_details', 	$admin_model->get_company_user_info())
										->set('ccp_enable',		$ccp_enable)
										->set('card_details', 	$admin_model->get_company_payment_info())
										->set('plan_name', 		$admin_model->get_company_plan_by_id($_SESSION['company_id']))
										->set('sales_group_enable', $version_lib->access_to_module(SALES, $_SESSION['company_id']));
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_get_settings_content
	 * @Description	:	Get setting page content
	 */
	public function get_settings_content($page)
	{
		require_once Kohana::find_file('classes', 'library/Versioning');
		$version_lib 	= 	new Versioning;
		$admin_model	= 	new Model_Admin;
		$company_m		=	new Model_Company;
		$keys 			= 	array('ccp');
		$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
		if(!empty($company_data)&&$company_data['ccp']=='1'){
			$ccp_enable = 1;
		} else {
			$ccp_enable	= 0;
		}
		
		$settings_content 	= 	View::factory('admin/settings')
										->set('last_sync', 		$admin_model->get_last_synced_date())
										->set('user_details', 	$admin_model->get_company_user_info())
										->set('ccp_enable',		$ccp_enable)
										->set('card_details', 	$admin_model->get_company_payment_info())
										->set('plan_name', 		$admin_model->get_company_plan_by_id($_SESSION['company_id']))
										->set('page', 			$page)
										->set('sales_group_enable', $version_lib->access_to_module(SALES, $_SESSION['company_id']));
		return $settings_content;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_searchuser
	 * @Description	:	Function to search the user.
	 */
	public function action_searchuser()
	{
		$this->check_admin();
		$admin_model	= 	new Model_Admin;
		$dharmausers_m	=	new Model_Dharmausers;
		$search_value			 =	empty($_POST['search'])?"":$_POST['search'];
		try {
		$this->template->content =	View::factory('admin/searchuser')
											->set('company_users', $dharmausers_m->get_timetracker_users(1))
											->set('user_limit', $admin_model->get_plan_user_limit())
											->set('total_users', $dharmausers_m->get_total_company_users())
											->set('search_value', $search_value);
		} catch(Exception $e) {die($e->getMessage());}	
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_searchcompany
	 * @Description	:	submit registration form
	 */
	public function action_searchcompany()
	{
		try {
			$admin_model			 = 	new Model_Admin;
			$search_value			 =	empty($_POST['search'])?"Search...":$_POST['search'];
			$this->template->content =	View::factory('admin/searchcompany')
												->set('company_list', $admin_model->get_company_search_list())
												->set('search_value', $search_value);
		} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_export
	 * @Description	:	To export
	 */
	public function action_export()
	{
		$this->check_admin();
		try {
			$admin_model		= 	new Model_Admin;
			$data				=	array();
			$arr_list			=	$admin_model->export_company_details();
			$header				=	array("Created Date", "Company Name", "Serial Number", "Email", "Street 1", "Street 2", "City"
										  ,"State", "Country", "Zip/Postal Code", "Phone", "Dropbox Device Name", "Dropbox Email","Service Plan","Service Price");
			$i	=	0;
			foreach($arr_list as $company) {
				$data[$i][0]		=	$company['create_date'];
				$data[$i][1]		=	$company['name'];
				$data[$i][2]		=	$company['serialnumber'];
				$data[$i][3]		=	$company['email'];
				
				$data[$i][4]		=	$company['street1'];
				$data[$i][5]		=	$company['street2'];
				$data[$i][6]		=	$company['city'];
				$data[$i][7]		=	$company['state'];
				$data[$i][8]		=	$company['country'];
				$data[$i][9]		=	$company['zipcode'];
				$data[$i][10]		=	$company['phone'];
				
				if($company['status'] == 1) {
					$data[$i][11]		=	$company['device_name'];
					$data[$i][12]		=	$company['dropbox_email'];
				} else {
					$data[$i][11]		=	"NILL";
					$data[$i][12]		=	"NILL";
				}
				$data[$i][13]		=	$company['service_plan'];
				$data[$i][14]		=	$company['price']."$";
				
				
				$i++;
			}
			$admin_model->export_to_excel($header, $data);
		} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_change_rate_status
	 * @Description	:	To change the rate status
	 */
	public function action_change_rate_status()
	{
		$result			=	array();
		$user_id		=	addslashes($_POST['user_id']);
		$current_status	=	$_POST['currentstatus'];
		$admin_model	= 	new Model_Admin;
		$dharmausers_m	=	new Model_Dharmausers;
		 
		try {
		$status	=	$dharmausers_m->change_user_rate_status($user_id, $current_status);
		} catch(Exception $e) {die($e->getMessage());}
		$result[0]['success']	=	1;
		echo json_encode($result);
		die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_loginasuser
	 * @Description	:	logging out and redirecting to site url.	
	 */	
	public function action_loginasuser()
	{
		session_destroy();
		request::instance()->redirect(SITEURL);
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_save_api_key
	 * @Description	:	Function to save the api key.
	 */
	public function action_save_api_key()
	{
		$admin_model	= 	new Model_Admin;
		$result			=	array();
		$api	=	addslashes($_POST['api_key']);
		try {
			if(preg_match('/^([a-zA-Z0-9]*)$/i', $api)) {
				$status	=	$admin_model->save_api_key($api);
				if($status) {
					$result[0]['success']	=	1;
				}
			} else {
				$result[0]['error']	=	1;
				$result[0]['description']	=	"Please enter valid API key.";
			}
		} catch(Exception $e) {
				$result[0]['error']	=	1;
				$result[0]['description']	=	$e->getMessage();
		}
		die(json_encode($result));
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_get_api_key
	 * @Description	:	Function to fetch the api key.
	 */
	public function action_get_api_key()
	{
		$result				=	array();
		$admin_model		= 	new Model_Admin;
		$api				=	$admin_model->get_api_key();
		$result[0]['api']	=	$api;
		echo json_encode($result);die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_supportadmin
	 * @Description	:	Function to give email support form to the user.
	 */
	public function action_supportadmin()
	{ 
		if(empty($_SESSION['company_id'])) {
			request::instance()->redirect(SITEURL.'/login');
		}
		$admin_model	= 	new Model_Admin;
		$this->template->title		=	"Welcome Dharma";
		$this->template->content 	= 	View::factory('admin/email_support')
													->set( 'company_info',	$admin_model->get_full_details($_SESSION['company_id']));
	
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_submit_email_supportadmin
	 * @Description	:	Validating and processing the email support form.
	 */
	public function action_submit_email_supportadmin()
	{ 
		$users		=	new Model_User;
		$data_model	=	new Model_Data();
		$admin_model	= 	new Model_Admin;
		$result		=	array();
		try {
			if($_POST['firstname'] == "") throw new Exception("Please enter your First Name");
			if($_POST['lastname'] == "") throw new Exception("Please enter your Last Name");
			if($_POST['email'] == "") throw new Exception("Please enter your Email");
			if($_POST['serialnumber'] == "") throw new Exception("Please enter your Serial Number");
			if($_POST['description'] == "") throw new Exception("Please enter Description");
			
			if(!preg_match("/^([a-z\s\'])*$/i", $_POST['firstname'])) throw new Exception("Please enter valid First Name");
			
			if(!preg_match("/^([a-z\s\'])*$/i", $_POST['lastname'])) throw new Exception("Please enter valid Last Name");
			
			if(!preg_match("/^([0-9])*$/i", $_POST['serialnumber'])) throw new Exception("Please enter valid Serial Number");
			if(!preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/", $_POST['email'])) throw new Exception("Please enter valid Email");
			if(strlen($_POST['serialnumber']) != 12) throw new Exception("Please enter valid Serial Number");
			
			$users->save_email_supportadmin_data($_POST);
			
			$company_info				=	$users->get_company($_SESSION['company_id']);
			if(empty($company_info[0]['name'])) {
				$company_name	=	"";
			} else {
				$company_name	=	$company_info[0]['name'];
			}
			$mail_content['to']			=	"shijith4u@gmail.com";
			$mail_content['subject']	=	"AccountEdge Cloud Email Support";
			$mail_content['from']		=	$_POST['firstname']." ".$_POST['lastname'];
			$mail_content['fromemail']	=	$_POST['email'];
			$mail_content['content']	=	"Dear AccountEdge Cloud Admin,<br/><br/>
														".$_POST['description']."<br/><br/>
														User Details<br/>-------------------<br/>
														<table cellpadding='0' cellspacing='0'>
															<tr><td width='25%' align='left'>First Name</td><td width='3%' align='left'>:</td><td align='left'>".$_POST['firstname']."</td></tr>
															<tr><td width='25%' align='left'>Last Name</td><td width='3%' align='left'>:</td><td align='left'>".$_POST['lastname']."</td></tr>
															<tr><td width='25%' align='left'>Email</td><td width='3%' align='left'>:</td><td align='left'>".$_POST['email']."</td></tr>
															<tr><td width='25%' align='left'>AccountEdge Serial Number</td><td align='left' width='3%'>:</td><td align='left'>".$_POST['serialnumber']."</td></tr>
															<tr><td width='25%' align='left'>Company Name</td><td align='left' width='3%'>:</td><td align='left'>".$company_name."</td></tr>
														</table>		<br/><br/>				
														Sincerely,<br/><br/>
														".$_POST['firstname']." ".$_POST['lastname']."
											";
			$data_model->send_email($mail_content);	// send email.
			$result[0]['success']	=	1;
		} catch(Exception $e) {
			$result[0]['error']	=	1;
			$result[0]['error_message']	=	$e->getMessage();
		} 
		echo json_encode($result);die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_modify_company_expire_date
	 * @Description	:	Function to modify the user's expire date.
	 */
	public function action_modify_company_expire_date()
	{
		$admin_model	= 	new Model_Admin;
		$result			=	array();
		try {
			if($_POST['date_to_add'] == 0 || $_POST['date_to_add'] == "") throw new Exception("Please select days from the list.");
			$admin_model->modify_expire_date($_POST);
			$result[0]['success']	=	1;
		} catch(Exception $e) {
			$result[0]['error']	=	$e->getMessage();
		}
		echo json_encode($result);die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_suspend
	 * @Description	:	suspend the company from the application.
	 */
	public function action_suspend($company_id)
	{
		$admin_model	= 	new Model_Admin;
		$xmlrpc			=	new Model_Xmlrpc;
		try {
			$admin_model->suspend_company($company_id);
			$plan_info	=	$admin_model->get_company_reference_id($company_id);
			$plan_info['op_val']	=	1;
			$xmlrpc->suspend_resume_plan($plan_info);			
		} catch(Exception $e) {
			die($e->getMessage());
			request::instance()->redirect(SITEURL.'/admin');
		}
		request::instance()->redirect(SITEURL.'/admin/edit_company/'.$company_id.'?s=1');
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_resume
	 * @Description	:	Resuming the paused state of a company
	 */
	public function action_resume($company_id)
	{
		$admin_model	= 	new Model_Admin;
		$xmlrpc			=	new Model_Xmlrpc;
		try {
			$admin_model->resume_company($company_id);
			$plan_info	=	$admin_model->get_company_reference_id($company_id);
			$plan_info['op_val']	=	0;
			$xmlrpc->suspend_resume_plan($plan_info);
		} catch(Exception $e) {
			die($e->getMessage());
			request::instance()->redirect(SITEURL.'/admin');
		}
		request::instance()->redirect(SITEURL.'/admin/edit_company/'.$company_id.'?r=1');
	}
	
	/**
	 * @Access		:	Public
	 * @Function 	:	action_changepassword
	 * @Description	:	change password for the user
	 */
	public function action_changepassword()
	{
		$admin_model	= 	new Model_Admin;
		$ret_result		=	array();
		try {
			if(isset($_POST['submit'])) {
				try {
					if($_POST['current_password'] == "") {
						throw new Exception("Enter current password");
					} elseif($_POST['new_password'] == "") {
						throw new Exception("Enter new password");
					} elseif($_POST['confirm_password'] == "") {
						throw new Exception("Confirm new password");
					} elseif(!$admin_model->valid_current_password($_POST['current_password'])) {
						throw new Exception("Current password you have entered is invalid.");
					} elseif($_POST['new_password'] !== $_POST['confirm_password']) {
						throw new Exception("Passwords do not match");
					}  elseif(strstr($_POST['new_password'], " ")) {
						throw new Exception("Password should not contain blank space");
					} elseif(strlen($_POST['new_password'])<5 || strlen($_POST['new_password'])>15) {
						throw new Exception(Kohana::message('error', 'valid_password'));
					} else {
						$admin_model->change_password($_POST['new_password']);	
						$ret_result[0]['success']	=	1;
						$ret_result[0]['desc']		=	"Password successfully updated";		
					}
				} catch(Exception $e) {
					throw new Exception($e->getMessage());
				}
			} else {
				$this->template->content 	= 	View::factory('admin/change_password');	
			}
		} catch(Exception $e) {
			$ret_result[0]['error']		=	1;
			$ret_result[0]['desc']		=	$e->getMessage();
		}
		die(json_encode($ret_result));
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_change_payroll_status
	 * @Description	:	change user payroll category field status.
	 */
	public function action_change_payroll_status()
	{
		$result			=	array();
		$user_id		=	addslashes($_POST['user_id']);
		$current_status	=	$_POST['currentstatus'];
		$dharmausers_m	= 	new Model_Dharmausers;
		try {
			$status	=	$dharmausers_m->change_user_payroll_status($user_id, $current_status);
		} catch(Exception $e) {die($e->getMessage());}
		$result[0]['success']	=	1;
		echo json_encode($result);
		die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_resetpassword
	 * @Description	:	Function to reset user password form
	 * @param 		:	String($active_id)
	 */
	public function action_resetpassword($active_id)
	{
		if(isset($_SESSION)){
			session_destroy();
		}
		$this->template->content 	= 	View::factory('admin/change_user_password')
													->set('activate_id', $active_id);
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_enterpaymentdetails
	 * @Description	:	Function to store the payment gateway details of admin
	 */
	public function action_enterpaymentdetails() 
	{
		$admin_model					=	new Model_Admin;
		$result							=	array();
		$merchant_ach_gateway_details	=	array();
		
		try {
			if($_POST['submit']) {
				try {
						if($_POST['ach_gateway_id'] == "") {
							throw new Exception("Enter gateway id");
						} elseif($_POST['ach_gateway_password'] == "") {
							throw new Exception("Enter gateway password");
						} elseif($_POST['confirm_gateway_password'] == "") {
							throw new Exception("confirm gateway password");
						} elseif($_POST['ach_gateway_password'] !== $_POST['confirm_gateway_password']) {
							throw new Exception("Passwords do not match");
						} elseif($_POST['apli_login_id'] == "") {
							throw new Exception("Enter Apli login Id");
						} elseif($_POST['transaction_key'] == "") {
							throw new Exception("Enter Transaction Key");
						} else {
							try {
								require Kohana::find_file('classes', 'library/Ach');
								$merchant_ach_gateway_details[0]['ach_gateway_id'] 			= $_POST['ach_gateway_id'];
								$merchant_ach_gateway_details[0]['ach_gateway_password'] 	= $_POST['ach_gateway_password'];
								$merchant_ach_gateway_details[0]['apli_login_id'] 			= $_POST['apli_login_id'];
								$merchant_ach_gateway_details[0]['transaction_key']			= $_POST['transaction_key'];
								//$merchant_details	=	json_decode(json_encode($merchant_ach_gateway_details));
								$ach_object 		= 	new Ach($merchant_ach_gateway_details[0], true);
								$response			=	$ach_object->auth_merchant_details();
								if($response == "Authentication is invalid."){
									$result[0]['error']		=	1;
									$result[0]['desc']		=	"Authentication is invalid";
								} else {
									$password_check		=	$ach_object->check_merchant_password();
									if($password_check['response_code'] != 'U13'){
										$result[0]['error']		=	1;
										$result[0]['desc']		=	$password_check['response_descripiton'];
									} else {
										$check_result		=	$admin_model->enter_payment_details($_POST);
										if($check_result){	
											$result[0]['success']	=	1;
											$result[0]['desc']		=	"Success";
										} else{
											$result[0]['error']		=	1;
											$result[0]['desc']		=	"Insertion Failed";
										}
									}
								}
							} catch(Exception $e){
								$result[0]['error']		=	1;
								$result[0]['desc']		=	$e->getMessage();
							}
						}
					} catch(Exception $e) {
						throw new Exception($e->getMessage());
					}
			} else {
				$result[0]['error']		=	1;
				$result[0]['desc']		=	$e->getMessage();
			}
		} catch(Exception $e){
			$result[0]['error']		=	1;
			$result[0]['desc']		=	$e->getMessage();
		}
		die(json_encode($result));
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_change_user_password
	 * @Description	:	Function to reset password
	 */
	public function action_change_user_password()
	{	
		$dharmausers_m	=	new Model_Dharmausers;
		
		$ret_result		=	array();
		try {
			if($_POST['cur_password'] == "") {
				throw new Exception("Enter current password");
			} elseif($_POST['new_password'] == "") {
				throw new Exception("Enter new password");
			} elseif($_POST['confirm_password'] == "") {
				throw new Exception("Confirm new password");
			} elseif(!$dharmausers_m->current_password_success($_POST['cur_password'])) {
				throw new Exception("Invalid current password.");
			} elseif(strstr($_POST['new_password'], " ")) {
				throw new Exception("Password should not contain blank space");
			} elseif(strlen($_POST['new_password'])<5 || strlen($_POST['new_password'])>15) {
				throw new Exception(Kohana::message('error', 'valid_password'));
			} elseif($_POST['new_password'] !== $_POST['confirm_password']) {
				throw new Exception("Passwords do not match");
			}
			$status	=	$dharmausers_m->change_user_password();
			
			if($status) {
				if(isset($_POST["ajax"])) {
					$ret_result[0]['success']	=	1;
					$ret_result[0]['desc']		=	"Password successfully updated";
					echo json_encode($ret_result);
					die();
				} else {
					$this->template->content 	= 	View::factory('admin/change_user_password')
														->set('activate_id', $_POST['activate_id'])
														->set('success', 1);
				}
			}
		} catch(Exception $e) {
			if(isset($_POST["ajax"])) {				
				$ret_result[0]['error']		=	1;
				$ret_result[0]['desc']		=	$e->getMessage();
				echo json_encode($ret_result);
				die();
			} else {
				
				$this->template->content 	= 	View::factory('admin/change_user_password')
														->set('activate_id', $_POST['activate_id'])
														->set('error', $e->getMessage());
			}
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_reset_user_password
	 * @Description	:	Reset user password and send temp password via email
	 */
	public function action_reset_user_password()
	{
		$admin_model	= 	new Model_Admin;
		$data_model		=	new Model_Data;
		$dharmausers_m	=	new Model_Dharmausers;
		$ret_result		=	array();
		$mail_content	=	array();
		$admin_password	=	$_POST['admin_password'];
		$user_id		=	$_POST['user_id'];
		try {
			if(!$admin_model->valid_admin_password($admin_password)) { //check whether admin password in correct
				throw new Exception("Invalid Password");
			}
			$temp_password	=	$this->temp_rand_password(); //create temp random password
			$active_id		=	$dharmausers_m->get_user_active_id($user_id); //get user active id to send temp email and store
			$dharmausers_m->reset_user_password($user_id, $temp_password, $active_id); //change user password
			$user_info		=	$dharmausers_m->get_user_info_by_active_id($active_id);
			$link			=	SITEURL."/admin/resetpassword/".$active_id;
			$mail_content['to']			=	$user_info['email'];
			$mail_content['subject']	=	"AccountEdge Cloud User Password Reset";
			$mail_content['content']	=	"Dear ".ucfirst($user_info['first_name']).",<br/><br/>
														Your password has been reset by the administrator.<br/>
														New password is: ".$temp_password."<br/><br/>
														Please click on this link to change this password:<br/>
														<a href='".$link."'>".$link."</a><br/><br/>
														Sincerely,<br/><br/>
														AccountEdge Cloud Team
											";
			$data_model->send_email($mail_content); 
			$ret_result[0]['success']	=	1;
			$ret_result[0]['desc']		=	"Password successfully updated";
			echo json_encode($ret_result);
			die();
		} catch(Exception $e) {
			$ret_result[0]['error']	=	1;
			$ret_result[0]['desc']	=	$e->getMessage();
			echo json_encode($ret_result);
			die();
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_sync
	 * @Description	:	Sync user functionality window
	 */
	public function action_sync($flag = 0)
	{
		$this->check_admin();
		$admin_model	= 	new Model_Admin;
		if(isset($flag) && ($flag == "2" || $flag == '3')){
			$dropbox	=  $admin_model->get_dropbox_info();
			
			if($flag) {
				$success = true;
			} else {
				$success = false;
			}
			$user_type		=   empty($_SESSION['selected_user_type'])?2:$_SESSION['selected_user_type'];
			$header			=	View::factory("admin/header-list")
											->set('users', $admin_model->get_users($user_type))
											->set('setup', 1);
			$msg			=	'';
			if($flag == "2"){										
				$msg = "Please allow AccountEdge Cloud to connect to Dropbox";
			}
			if($flag == "3"){										
				$msg = "Device name not found. Please provide a valid device name";
			}
			$dropbox_setup	= 	View::factory('admin/edit_dropbox')
												->set('dropbox', $dropbox)
												->set('error',$msg);
			$this->template->content = 	View::factory('admin/companysettings')
												->set('content', $dropbox_setup)
												->set('setting', $this->get_settings_content(4));
		} else {									
			$sync				 	 =	View::factory('admin/sync_users')
												->set('last_sync', $admin_model->get_last_synced_date());
			if(isset($flag) && $flag == "1") {
				die($sync);
			}
			$this->template->content = 	View::factory('admin/companysettings')
												->set('content', $sync)
												->set('setting', $this->get_settings_content(6));
		}
	}
	
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_ccp
	 * @Description	:	This function will load the form for credit-card-processing
	 */
	public function action_ccp($flag = 0)
	{
		$this->check_admin();
		$gatewayAch_m	= 	new Model_Gatewayach;
		$company_m		=	new Model_Company;
		$keys 			= 	array('ccp');
		$company_data	= 	$company_m->get_company_by_key( $keys, $_SESSION['company_id']);
		$gateway_details=	$gatewayAch_m->get_merchant_personal_ach_details($_SESSION['company_id']);
		
		if(!empty($company_data)&&$company_data['ccp']=='1'){
			$ccp_enable = 1;
		} else {
			$ccp_enable	= 0;
		}
		if(!empty($gateway_details) && $gateway_details[0]['status']=='1'){
			$status = 1;
		} else {
			$status = 0;
		}
		$ccp_setup	= 	View::factory('admin/ccp') // view for ACH form.
											->set('ccp_enable', $ccp_enable)
											->set('status', $status);
		
		if(isset($flag)&&$flag == "1") { // show ACH-Form
			die($ccp_setup);
		}	
		$this->template->content = 	View::factory('admin/companysettings')
											->set('content', $ccp_setup)
											->set('setting', $this->get_settings_content(7)); 
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_deposit_account
	 * @Description	:	This function will load the form for Deposit Account
	 */
	public function action_deposit_account($flag = 0)
	{
		$deposit_view	= 	View::factory('admin/deposit_account') // view for ACH form.
											->set('deposit_account_number', '1-1160');
											
		if($flag == "1") { // show ACH-Form
			die($deposit_view);
		}	
		$this->template->content = 	View::factory('admin/companysettings')
											->set('content', $deposit_view)
											->set('setting', $this->get_settings_content(8));
	}

	/**
	 * @Access		:	Public
	 * @Function	:	action_syncusers
	 * @Description	:	Function to display page where user can sync users
	 */
	public function action_syncusers()
	{
		$this->check_admin();
		$admin_model	=	new Model_Admin;
		$admin_model->sync_users_from_aed();
		request::instance()->redirect(SITEURL.'/admin/sync');
	}	
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_act
	 * @Description	:	Function to activate the user.
	 */
	public function action_act() {
		try {
			$this->template->content = 	View::factory('admin/activate')
											->set('activate_id', '1234')
											->set('access_status', '1');
		} catch(Exception $e){die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_sync_all
	 * @Description	:	Function to sync all
	 */
	public function action_sync_all() {
		try{			
			$response2 	= 	Request::factory("jobs/sync_all_jobs")->execute()->response;
			$response3	=	Request::factory("customer/sync_all_customers")->execute()->response;
			$response1	=	Request::factory("sales/syncall")->execute()->response;
			
		} catch(Exceptin $e){
				die($e->getMessage());
		}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	delete_data_on_device_change
	 * @Description	:	Function to Delete old records while updating dropbox
	 * @Used as an ajax call in admin.js
	 */
	public function action_delete_data_on_device_change() {
		$admin_model	=	new Model_Admin;
		$company_id		=	$_SESSION['company_id'];
		$result			=	$admin_model->database_delete_data_on_device_change($company_id);	
		echo $result;
		die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	Update CCP ON/OFF
	 * @Description	:	Function to Update CCP ON/OFF as 1/0
	 */
	public function action_update_ccp($val){
		$company_m		=	new Model_Company;
		$key_val['ccp'] =	$val;
		$result			=	$company_m->update($key_val, $_SESSION['company_id']);
		echo $result;
		die;
	}
}