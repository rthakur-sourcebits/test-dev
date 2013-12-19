<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @File		:	login.php Controller
 * @Class		:	Controller_Login
 * @Date		:	31 Aug 2010
 * @Description	:	User login authentication module
 * * @Modified 		: 13.11.2013 - modifying login index function.
 *  				: 13.11.2013 - adding expiration function check before login.
 * Copyright (c) 2011 Acclivity Group LLC
 */
class Controller_Login extends Controller_Template {
	public $template = 'template';
	public $session = '';
	public $force_dropbox_path_update = false;
	/**
	 * @Function 		: action_index
	 * @Description 	: get user-name and password from user and pass to the model to  the user.
	 * @Modified 		: 13.11.2013 - adding expiration function check before login.
	 */
	public function action_index() {
		if (isset($_POST['submit'])) {
			
			$email = strtolower($_POST['username']);
			$password = $_POST['password'];
			$this -> template -> title = "Welcome Dharma";
			// check user expiration if expired: redirect to upgrade page.
			if(!$this->expiration_user($email, $password)){
				// check the user Credential: set session variable + logged user in the AEC system.
				$this -> validate_user($email, $password);
				
			}
		}
		elseif (isset($_SESSION['new_activation_status']) && ($_SESSION['new_activation_status'] == 1)) {
			$email = strtolower($_SESSION['hash_username']);
			$password = $_SESSION['hash_password'];
			session_destroy();
			session_start();
			$_SESSION['new_activation_status'] = 1;
			// check user expiration if expired: redirect to upgrade page.
			if(!$this->expiration_user($email, $password)){
				// check the user Credential: set session variable + logged user in the AEC system.
				$this -> validate_user($email, $password);
			}
		} else {
			// we need to check what is this case and modify accordingly. 
			// check user expiration if expired: redirect to upgrade page.
			$this -> template -> title = "Welcome Dharma";
			request::instance() -> redirect(SITEURL . '/activitysheet');
		}
	}

	/**
	 * @Access		:	Private
	 * @Function	:	Expiration_user
	 * @Description	:	To check if the user-plan is not expired.
	 * @Params		:	string, string
	 * @return		:	true : will redirect the user to plan upgrade page.
	 * 					false : will proceed with user login.
	 */
	private function expiration_user($email, $password) {
		$admin_m = new Model_Admin; 
		$user_data = $admin_m->get_admin_consumer($email, $password);
		
		// check expiration.
		if(isset($_SESSION) && !empty ($_SESSION)){
		session_destroy();
		session_start();
		}
		if(isset($user_data[0]['company_id'])){
			$_SESSION['company_id']	=	$user_data[0]['company_id'];
			$plan_name				=	$admin_m->get_plan_details($_SESSION['company_id']);
			$days_left 				=	$admin_m->expire_day_left($user_data[0]['company_id']);
			if($days_left<0 && $plan_name == FREE){
				request::instance() -> redirect(SITEURL . '/admin/upgrade');
			} else {
				return FALSE;
			}
		} 
	}
	
	/**
	 * @Access		:	Private
	 * @Function	:	Validate_user
	 * @Description	:	To validate the login user
	 * @Params		:	string, string
	 */
	private function validate_user($email, $password) {

		$data 		= new Model_data;
		$users 		= new Model_user;
		$dropbox 	= new Model_dropbox;
		$login 		= new Model_login;
		$admin_m 	= new Model_Admin;
		try {
			/******* Verify User-Name and Password & Set Session Variable *******/
			$result = $this -> admin_user_check($email, $password);
			// if user admin then do all validation and redirect back to main page
			if ($result) {//keep me logged in for 2 weeks (for admin)
				if (isset($_POST['remember']) && $_POST['remember'] == '1')// creating the cookies to make the user to login for 14days.
				{
					$data = array('employee_id' => base64_encode($_SESSION['employee_id']), 'company' => base64_encode($_SESSION['company_id']));
					setcookie("keep_loged", json_encode($data), time() + COOKIE_TIME, "/", $_SERVER['HTTP_HOST'], false, 1);
				} else {
					// destroying the cookies.
					setcookie("keep_loged", '', time() - COOKIE_TIME, "/", $_SERVER['HTTP_HOST'], false, 1);
				}
			}
		}
		catch(Exception $e) {
			die($e -> getMessage() . "\n" . $e -> getFile() . " line " . $e -> getLine());
		}
		if ($result == true) {
			request::instance() -> redirect(SITEURL);
		} else { // checking dharma users.
			$result = $users -> verify_users($email, $password);
			// Set Company Group.
		}
		
		if ($result) {
			if ($result[0]['status'] == 0) {
				request::instance() -> redirect(SITEURL . '/company/login/7');
			}
			if ($result[0]['suspend_status'] == 1)// get the company details
			{
				request::instance() -> redirect(SITEURL . '/company/login/9');
			}
			if ($result[0]['company_id'])// get the company details
			{
				$comapny_info = $users -> get_company($result[0]['company_id']);
			}
			if ($users -> user_expired($comapny_info[0]['id'])) {
				request::instance() -> redirect(SITEURL . '/company/login/8');
			}

			try {
				$data = new Model_data;
				/**
				 * Open dropbox authentication page
				 */
				if ($result[0]['token'] == "") {
					$_SESSION['logged_user_id'] = $result[0]['id'];
					request::instance() -> redirect(SITEURL . "/admin/activation/" . $result[0]['activation_id'] . "/1");
				} else {
					$_SESSION['oauth_tokens']['token'] = $result[0]['token'];
					$_SESSION['oauth_tokens']['token_secret'] = $result[0]['secret'];
					$_SESSION['company_id'] = $comapny_info[0]['id'];
					
					$data -> define_dropbox_constants($result[0]);
					$status = $login -> login_validate();
					if ($status) {
						$_SESSION['employee_id'] = $result[0]['record_id'];
						$_SESSION['employee_name'] = $result[0]['first_name'];
						$_SESSION['employee_lastname'] = $result[0]['last_name'];
						$_SESSION['company'] = $comapny_info[0]['name'];
						$_SESSION['display_rate'] = $result[0]['display_rate'];
						$_SESSION['synced_slips_view'] = 0;
						//display not synced slips
						$_SESSION['sync_alert_message'] = 1;
						$_SESSION['User_type'] = ($result[0]['type'] == "Employee") ? "Employees" : "Contractor";
						// store filename based on user type.
						$_SESSION['admin_user'] = 0;
						if ($users -> free_plan_user($comapny_info[0]['id'])) {
							$_SESSION['free_user'] = 1;
							$_SESSION['days_left'] = $admin_m -> expire_day_left($comapny_info[0]['id']);
						}
						$users -> create_user_log();
						request::instance() -> redirect(SITEURL . '/activitysheet');
					} else {
						request::instance() -> redirect(SITEURL . '/company/login/6');
					}
				}
				
			} catch(Exception $e) {
				session_destroy();
				request::instance() -> redirect(SITEURL . "/admin/activation/" . $result[0]['activation_id'] . "/1");
			}
		} else// invalid user name and password
		{
			session_destroy();
			request::instance() -> redirect(SITEURL . '/company/login/2');
		}
	}

	/**
	 * @Access		:	Public
	 * @Function	:	action_logout
	 * @Description	:	logout the user from the application.
	 * @Param		:	int
	 */
	public function action_logout($error_id = null) {
		// Destroying the cookies while user request to logout.
		$login = new Model_login;
		$login -> delete_user_log();
		setcookie("keep_loged", '', time() - COOKIE_TIME, "/", $_SERVER['HTTP_HOST'], false, 1);
		session_destroy();
		// redirecting to the login page of the company.
		if (empty($error_id)) {
			request::instance() -> redirect(SITEURL);
		} else {
			request::instance() -> redirect(SITEURL . '?e_flag=' . $error_id);
		}
	}

	/**
	 * @Access		:	Public
	 * @Function	:	action_loginasadmin
	 * @Description	:	Function to logout and redirect to the admin login page.
	 */
	public function action_loginasadmin() {
		$login = new Model_login;
		$login -> delete_user_log();
		setcookie("keep_loged", '', time() - COOKIE_TIME, "/", $_SERVER['HTTP_HOST'], false, 1);
		session_destroy();
		request::instance() -> redirect(SITEURL . "/admin");
	}

	/**
	 * @Access		:	Public
	 * @Function	:	admin_user_check
	 * @Description	:	Admin login functionality- validate if logged user is admin or not
	 * @Params		:	string, string
	 */
	public function admin_user_check($email, $password) {
		$data 		= new Model_Data;
		$admin_m 	= new Model_Admin;
		$users 		= new Model_user;
		$dropbox 	= new Model_dropbox;
		$action 	= "view";
		$key_info 	= $admin_m->get_admin_consumer($email, $password);
		// Set Company Group.
		//require_once Kohana::find_file('classes', 'library/Versioning');
		
		if (empty($key_info))	// getting the consumer-key and consumer secret-key based on the company name.
		{
			return false;
		} else {
			if ($key_info[0]['token'] == "") {
				$_SESSION['log_user_activation_id'] = $key_info[0]['activation_id'];
				request::instance() -> redirect(SITEURL . '/admin/activation/' . $key_info[0]['activation_id'] . '/2');
			} else {
				$_SESSION['oauth_tokens']['token'] = $key_info[0]['token'];
				$_SESSION['oauth_tokens']['token_secret'] = $key_info[0]['secret'];
				$_SESSION['company_id'] = $key_info[0]['company_id'];
				
				if(isset($_SESSION['country'])) {unset($_SESSION['country']);}
				$_SESSION['country']=$key_info[0]['country'];
				
				// define constant message file.
				if($_SESSION['country']==2){
					$_SESSION['CONSTANT_MSG_FILE']= 'english-uk';
				} else {
					$_SESSION['CONSTANT_MSG_FILE']= 'english-usa';
				}
				
				$status1 = $data -> define_dropbox_constants($key_info[0]);
				// define DB device name
				$status = $this -> admin_dropbox_file_validate();
				if ($status) {
					$_SESSION['user_name'] = $email;
					$_SESSION['password'] = $password;
					$_SESSION['admin_email'] = $email;
					$_SESSION['company'] = $key_info[0]['company_name'];
					$_SESSION['admin_user'] = 1;

					$_SESSION['employee_id'] = $key_info[0]['id'];
					$_SESSION['employee_name'] = $key_info[0]['user_name'];
					$_SESSION['employee_lastname'] = $key_info[0]['lastname'];
					$_SESSION['display_rate'] = 1;
					$_SESSION['synced_slips_view'] = 0;
					//display not synced slips
					$_SESSION['sync_alert_message'] = 1;
					$_SESSION['User_type'] = "Admin";
					$users = new Model_user;
					if ($users -> free_plan_user($key_info[0]['company_id'])) {
						$_SESSION['free_user'] = 1;
						$_SESSION['days_left'] = $admin_m -> expire_day_left($key_info[0]['company_id']);
					}
					$users -> create_user_log();
					return true;
				} else {
					$_SESSION['log_user_activation_id'] = $key_info[0]['activation_id'];
					request::instance() -> redirect(SITEURL . '/admin/activation/' . $key_info[0]['activation_id'] . '/2');
				}
			}
		}
	}

	/**
	 * @Access		:	Public
	 * @Function	:	admin_dropbox_file_validate
	 * @Description	:	Function validate dropbox files at login time
	 */
	public function admin_dropbox_file_validate() {
		$user = new Model_User;
		$fail = false;
		$result = false;
		$data = new Model_data;

		try {
			$true_session = $user -> create_json_sessions();
		} catch(Exception $e) {
			$fail = true;
			$fail_message = $e -> getMessage();
			die($fail_message);
		}

		if ($fail)// check whether if any error occured while reading any json file.
		{
			return false;
			session_destroy();
			request::instance() -> redirect(SITEURL . '/admin/index/6');
		} else {
			return true;
		}
	}

} // End Login
