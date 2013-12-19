<?php
defined('SYSPATH') or die('No direct script access.');
/*
 * 	@File			: login.php Model
 * 	@Class			: Model_Login
 * 	@Created date	: 30/Aug/2010
 * 	@Descritpion	: Login dropbox username password validation.
 */
class Model_login extends Model {

	/*
	 * @Function	: login_validate
	 * @Description	: validate dropbox credential.
	 */

	public function login_validate()
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
			request::instance()->redirect(SITEURL.'?e_flag=6');
		}
		else
		{ 
			return true;
			//$this->create_user_log();
			//request::instance()->redirect(SITEURL.'/activitysheet'); // after successfull login redirect user to activity slip page
		}
	}
	
	
	
	/**
	 * remove the user log when logout
	 */
	public function delete_user_log()
	{
		$sql	=	"DELETE FROM user_log
					 WHERE session_id  = '".session_id()."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, False);
		return true;
	}
	/* End of class*/
}