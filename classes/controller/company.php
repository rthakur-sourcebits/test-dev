<?php defined('SYSPATH') or die('No direct script access.');
/**
 * @File		:	welcome.php (Home page controller)
 * @Class		:	Controller_Welcome
 * Copyright (c) 2011 Acclivity Group LLC
 * @Modified	:
 * 					: 13.11.2013 - added upgrade function.		
*/
class Controller_Company extends Controller_LoginTemplate
{
	public $template = 'logintemplate';
    public $session  = '';
	
	/**
	 * @Access			:	Public
	 * @Function		:	action_index
	 * @Description		:	Function executes when user successfully login into the system. Creates session and cookies.
	*/
	public function action_index($flag=0)
	{
		$this->template->title			=	"Welcome Dharma";
		$company_info['company_entry']	=	1;
		if($flag != 0) {
			$company_info['error']			=	$flag;
		}
		
		if(!empty($_SESSION['employee_id']))
		{
			if(!empty($_SESSION['admin_user'])){
				request::instance()->redirect(SITEURL.'/admin');
			}else{
				request::instance()->redirect(SITEURL.'/activitysheet');
			}
		}
		else
		{
			try
			{
				if (isset($_COOKIE['keep_loged']))	// checks for the cookie and then creates  the session objects.
				{
					$data			=	new Model_data;
					$data->check_cookie();
				}
				
				if (empty($_SESSION['employee_id'])) 			// for the first time user comes in 
				{
					$this->template->content  	=	new View("company/login1",$company_info);
				}
				else
				{
					if(!empty($_SESSION['admin_user'])){
						request::instance()->redirect(SITEURL.'/admin');
					}else{
						request::instance()->redirect(SITEURL.'/activitysheet');	
					}
				}
			}
			catch(Exception $e)
			{
				request::instance()->redirect(SITEURL.'/login/logout/4');
			}
		}
	}
	
	/**
	 * @Access			:	Public
	 * @Function		:	action_login
	 * @Description		:	Functiona display company login information.
	*/
	public function action_login($flag=0)
	{
	
		$this->template->title		=	"Welcome Dharma";
		$user_model					=	new Model_User;
				
		
		if (isset($_COOKIE['keep_loged']))	// checks for the cookie and then creates  the session objects.
		{
			$data			=	new Model_data;
			$data->check_cookie();
		}				
		
		if (empty($_SESSION['employee_id'])) // for the first time user comes in 
		{
			//$user['company']			=	"";
			if($flag != 0) {
				$user['error']			=	$flag;
			}
		    $this->template->content  	=	new View("company/login1",$user);
		} 
		else 	// if the user session is there then go to welcome page.
		{	
		    $user['employee_name']		=	$_SESSION['employee_name'];
		    request::instance()->redirect(SITEURL);
		    //$this->template->content	=	new View("company/welcome",$user);
		}
	}	
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_logout
	 * @Description	:	logout the user from the application.
	*/
	public function action_logout()
	{   
		// Destroying the cookies while user request to logout.
		setcookie("keep_loged",	'', time()-COOKIE_TIME, "/", $_SERVER['HTTP_HOST'], false, 1);
		// Destorying the sessions.
		if(empty($_SESSION['company']))
		{
			session_destroy();
			request::instance()->redirect(SITEURL);
		}
		$comany = $_SESSION['company'];
		session_destroy();
		// redirecting to the login page of the company.
		request::instance()->redirect(SITEURL.'/login');
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_forgot
	 * @Description	:	Display the forgot password form.
	 */
	public function action_forgot()
	{   
		$this->template->title		=	"Welcome Dharma";
		$this->template->content  	=	new View("company/forgot_password");
	}
		
	/**
	 * @Access		:	Public
	 * @Function	:	action_forget_save
	 * @Description	:	logout the user from the application.
	 */
	/*
	public function action_forgot_save()
		{   
			$email			=	strtolower($_POST['email']);						
			$data			=	new Model_data;
			$users			=	new Model_User;
			$result_status	=	array();
			$result	=	$users->validate_user_email($email); // validate user email for forgot password functionality
			
			if($result)
			{
				try {
					// send mail with password and other details
					$fname			=	$result[0]['first_name'];
					$to				=	$result[0]['email'];
					$new_password	=	$result[0]['pw'];
					$mail_content['fname']		=	$fname;
					$mail_content['to']			=	$to;
					$mail_content['subject']	=	"Your AccountEdge Cloud Password";
					$mail_content['content']	=	"Dear $fname,<br/><br/>
														Your have requested your AccountEdge Cloud Password, here is your log in details;<br/>
														Log in Email	:	$to<br/>
														Log in Password	:	$new_password<br/><br/>
														Sincerely,<br/>
														AccountEdge Cloud Team
													".$dump;
					//echo $mail_content['content'];die;
					$data->send_email($mail_content);		// send email.
					//request::instance()->redirect(SITEURL.'/company/forgot?msg=1');
					//request::instance()->redirect(SITEURL.'?f=1');
					$result_status[0]['error']	=	"0";
					
					$result_status[0]['msg']	= $dump;//	"Password successfully sent";
				  }
				  catch(Exception $e) {
					  $result_status[0]['error']	=	"1";
					$result_status[0]['msg']	=	$e->getMessage();
				  }
			} else {
				$result	=	$users->validate_admin_email($email); // validate user email for forgot password functionality
				//var_dump($result); die;
				if($result)
				{
					
					try {
						// send mail with password and other details
						$fname			=	trim($result[0]['first_name']." ".$result[0]['lastname']);
						$to				=	$result[0]['email'];
						$new_password	=	$result[0]['pw'];
						$mail_content['fname']		=	$fname;
						$mail_content['to']			=	$to;
						$mail_content['subject']	=	"Your AccountEdge Cloud Admin Password";
						$mail_content['content']	=	"Dear $fname,<br/><br/>
															Your have requested your AccountEdge Cloud Admin Password, here is your log in details;<br/>
															Login Email		:	$to<br/>
															Login Password	:	$new_password<br/><br/>
															Sincerely,<br/><br/>
															AccountEdge Cloud Team
														".$dump;
						//echo $mail_content['content'];die;
						$data->send_email($mail_content);		// send email.
						//request::instance()->redirect(SITEURL.'/company/forgot?msg=1');
						$result_status[0]['error']	=	"0";
						$result_status[0]['msg']	=	"Password successfully sent";
					  }
					  catch(Exception $e){
						  $result_status[0]['error']	=	"1";
						$result_status[0]['msg']	=	$e->getMessage();
					  }
				} else {
					$result_status[0]['error']	=	"1";
					$result_status[0]['msg']	=	"Invalid email";
				}
			}
			die(json_encode($result_status));
		}*/
	

	/**
	 * @Access		:	Public
	 * @Function	:	action_forget_save1
	 * @Description	:	make a proper link with the useris and the link will direct the user to reset the password.  
	 */
	public function action_forgot_save1()
	{
		$email			=	strtolower($_POST['email']);
		$data			=	new Model_data;
		$users			=	new Model_User;
		$result_status	=	array();
		$result	=	$users->validate_admin_email($email); // admin user
		if($result)
		{
			try {
				// send mail with password and other details
				$fname			=	trim($result[0]['first_name']." ".$result[0]['lastname']);
				$to				=	$result[0]['email'];
				$new_password	=	$result[0]['pw'];
				// creating link
				$encrypt_data = mt_rand(10000, 99999) . '-fgtpwd-' . $result[0]['id'] . '-' . mt_rand(1, 999) . '-au';//admin user.
				$auth_code = base64_encode($encrypt_data);
				$arr_update["auth_code"]=$auth_code;
				$data -> update_user("admin_users", $result[0]['id'], $arr_update);
				$link = SITEURL . '/company/auth_code/' . $auth_code;
				$mail_content['fname']		=	$fname;
				$mail_content['to']			=	$to;
				$mail_content['subject']	=	"Your AccountEdge Cloud Admin Password";
				$mail_content['content']	=	"Dear $fname,<br/><br/>
													Your have requested your AccountEdge Cloud Password, here is your activation Link to reset password:<br/>
													$link<br/>
													<br/>
													Sincerely,<br/><br/>
													AccountEdge Cloud Team
												";
				
				$data->send_email($mail_content);		// send email.
				$result_status[0]['error']	=	"0";
				$result_status[0]['msg']	=	"Password link sent to your Email-Id Successfully.";
	  		}
	  		catch(Exception $e){
	  			$result_status[0]['error']	=	"1";
				$result_status[0]['msg']	=	$e->getMessage();
	  		}
		} else {
			$result_status[0]['error']	=	"1";
			$result_status[0]['msg']	=	"Invalid email";
		}
		
		die(json_encode($result_status));
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_support
	 * @Description	:	Function to display the support form
	 */
	public function action_auth_code($auth_code = '') {

		$data			=	new Model_data;
		$dec_auth_code = base64_decode($auth_code);
		$auth_code_array = explode('-', $dec_auth_code);
		
		$invalid_auth_code = FALSE;
		if (count($auth_code_array) != 5) {
			$invalid_auth_code = TRUE;
		}
		if ($auth_code_array[1] != 'fgtpwd') {
			$invalid_auth_code = TRUE;
		}
		if ($auth_code_array[4] != 'du' && $auth_code_array[4] != 'au') {
			$invalid_auth_code = TRUE;
		}
		if (in_array('', $auth_code_array)) {
			$invalid_auth_code = TRUE;
		}
		if ($invalid_auth_code == FALSE) {
			$id = $auth_code_array[2];
			
			if ($auth_code_array[4] == 'du') { 	// dharma user
				$encrypt_data = mt_rand(10000, 99999) . '-fgtpwd-' . $id . '-' . mt_rand(1, 999) . '-du';
			
			} else { 							// admin user
				$encrypt_data = mt_rand(10000, 99999) . '-fgtpwd-' . $id . '-' . mt_rand(1, 999) . '-au';
				
			}
			
			$valid_user = $data -> get_user_by_id("admin_users",$id); // need to get the user profile here from id.
			
			if ($valid_user) {				
				if ($valid_user[0]['auth_code'] == $auth_code) {
					// show view for valid password change
					$this->template->title		=	"Welcome To AccountEdge Cloud";
					$arr_data['auth_code']=$auth_code;
					$this->template->content  	=	new View("company/reset_password1",$arr_data);
					
				} else {
					// show view for Password Link expired
					$this->template->title		=	"Welcome To AccountEdge Cloud";
					$user['error']=10;
					$this->template->content  	=	new View("company/login1",$user); // with error link expired.
					
				}
			} else {
				// show view for Invalid user details
				$this->template->title		=	"Welcome Dharma";
				$user['error']=11;
				$this->template->content  	=	new View("company/login1",$user); // with error use details is incorrect.
				
			}
		}
	}

	/**
	 * @Access		:	Public
	 * @Function	:	action_password_reset
	 * @date 		: 	21-08-2013
	 * @Description	:	Function to will check the password.
	 * 					check the link is expired.
	 * 					if the password is match then, it will give success 
	 * 					else give failure
	 * 					show the view/page for displaying the result.
	 */
	public function action_password_reset(){
		$data			=	new Model_data;
		$dec_auth_code = base64_decode($_POST['auth_code']);
		$auth_code_array = explode('-', $dec_auth_code);
		$invalid_auth_code = false;
		$id = $auth_code_array[2];
		
		if ($auth_code_array[4] == 'du') {
			$valid_user = $data->get_user_by_id("dharma_users", $id);
		} else {
			$valid_user = $data->get_user_by_id("admin_users", $id);
		} 
		
		$view_data=array();
		if ($valid_user) {			
			if ($auth_code_array[4] == 'du') {
				$encrypt_data = mt_rand(10000, 99999) . '-fgtpwd-' . $id . '-' . mt_rand(1, 999) . '-du';
			} else {
				$encrypt_data = mt_rand(10000, 99999) . '-fgtpwd-' . $id . '-' . mt_rand(1, 999) . '-au';
			}
	
			if ($valid_user[0]['auth_code'] == $_POST['auth_code']) {
				// updating the password and new outh key
				$new_auth_code = base64_encode($encrypt_data);
				$arr_data['auth_code'] = $new_auth_code;
				$arr_data['password'] = $_POST['reset-input1'];
				
				if ($auth_code_array[4] == 'du') {	
					$data->update_user("dharma_users", $id, $arr_data);
					
				}
				else{
					$data->update_user("admin_users", $id, $arr_data);
										
				}
				$view_data['result'] = TRUE;
				
			} else {// invalid outh
				$view_data['result'] = FALSE;
								
			}

		} else {// no user entry
			$view_data['result'] = FALSE;
			
		}
		// Result Page 
		$this->template->title		=	"Welcome To AccountEdge Cloud";
		$this->template->content  	=	new View("company/pwd_reset_success",$view_data);
		
	} 
	/**
	 * @Access		:	Public
	 * @Function	:	action_support
	 * @Description	:	Function to display the support form
	 */
	public function action_support()
	{ 
		if(empty($_SESSION['employee_id'])) {
			request::instance()->redirect(SITEURL.'/login');
		}
		$users						=	new Model_User;
		$this->template->title		=	"Welcome Dharma";
		$this->template->content 	= 	View::factory('company/email_support')
													->set( 'user_info',	$users->get_user($_SESSION['employee_id'], $_SESSION['company_id']))
													->set( 'serial_number',	$users->get_serail_number($_SESSION['company_id']));
	
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_submit_email_support
	 * @Description	:	Function on submitting the email support form.
	 */
	public function action_submit_email_support()
	{ 
		$users		=	new Model_User;
		$data_model	=	new Model_Data();
		$result		=	array();
		try {
			if($_POST['firstname'] == "") throw new Exception("Please enter your First Name");
			if($_POST['lastname'] == "") throw new Exception("Please enter your Last Name.");
			if($_POST['email'] == "") throw new Exception("Please enter your Email");
			if($_POST['serialnumber'] == "") throw new Exception("Please enter your Serial Number");
			if($_POST['description'] == "") throw new Exception("Please enter Description");
			
			if(!preg_match("/^([a-z\s\'])*$/i", $_POST['firstname'])) throw new Exception("Please enter valid First Name");
			
			if(!preg_match("/^([a-z\s\'])*$/i", $_POST['lastname'])) throw new Exception("Please enter valid Last Name");
			
			if(!preg_match("/^([0-9])*$/i", $_POST['serialnumber'])) throw new Exception("Please enter valid Serial Number");
			if(!preg_match("/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/", $_POST['email'])) throw new Exception("Please enter valid Email");
			if(strlen($_POST['serialnumber']) != 12) throw new Exception("Please enter valid Serial Number");
			
			$users->save_email_support_data($_POST);
			//die("here1");
			
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
	
	
} // End Welcome