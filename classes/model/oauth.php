<?php 
/**
* @File			: oauth.php - Model
* @Created		: <2013-06-13>
* @Updated		: <2013-06-28> (Flag Api's added) AND (Customized Login added)
 * 				  <2013-06-13> Set/Un-Set function for access flag.
 * 				  <2013-07-08> added new function access_user_flag
 * 				  <2013-09-02> added new function delete_user_by_user
* @Description	: Various models performing operations like login, register, access etc. 
* Copyright (c) 2013 Acclivity LLC 
*/
defined('SYSPATH') or die('No direct script access.');

class Model_Oauth extends Model {
	public $db;

	public function __construct() {
		parent::__construct();
		$this->db = Database::instance();
	}


/**
* @method       : check_key_exist
* @description	: Check if consumer key and secret key exists or not.
* @param        : username
* @return		: returns 'key already exist' if key is already present, else 0.
*/
	public function check_key_exist($email) {	
		$sql 		= 	"SELECT osr.`osr_usa_id_ref` FROM `oauth_server_registry` as osr JOIN admin_users as usr ON usr.id = osr.`osr_usa_id_ref` 
						where usr.email = ".$this->db->escape(strtolower($email))." AND osr.osr_requester_email = ".$this->db->escape(strtolower($email))."";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result)){
			$return["success"]=TRUE;			
		}
		else{
			$return["success"]=FALSE;			
		}
		return $return;
	}
		


/**
* @method       : login_user
* @description	: Login user, by returning Access tokens to access API's.
* @param        : username, password, consumer key and consumer secret for login
* @return		: userid and consumer key from oauth_server_registry table
*/
	public function login_user($email,$password,$consumer_key,$consumer_secret){	
		$sql 		= 	"SELECT usreg.`osr_usa_id_ref`,usreg.`osr_consumer_key` FROM `admin_users` as usr JOIN oauth_server_registry 
						as usreg ON usreg.`osr_usa_id_ref` = usr.`id` WHERE usr.`email` = ".$this->db->escape($email)." AND AES_DECRYPT(usr.password, '".ENCRYPT_KEY."') = '".$password."' 
						AND usreg.`osr_consumer_key` = '".$consumer_key."' AND usreg.`osr_consumer_secret` = '".$consumer_secret."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}


/**
* @method       : getaccessTokens
* @description	: getting access tokens without actual Login.
* @param        : username and password
* @return		: access_token and access_token_secret.
*/
	public function getaccessTokens($email,$password) {
		$sql 		= 	"SELECT oauth_consumer_token.oct_token, oauth_consumer_token.oct_token_secret FROM oauth_consumer_token JOIN admin_users ON 
						oauth_consumer_token.`oct_usa_id_ref` = admin_users.`id` where admin_users.email=".$this->db->escape($email)." AND AES_DECRYPT(admin_users.password, '".ENCRYPT_KEY."')='".$password."' AND 
						oauth_consumer_token.oct_token_type='access'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(isset($result[0]))
			$result=$result[0];
		else
			$result=FALSE; 
		return $result;
	}


/**
* @method       : check_user_access
* @description	: Verfy access tokens
* @param        : access_token and access_token_secret
* @return		: user_id if access tokens are correct, else 0.
*/
	public function check_user_access($access_token,$access_token_secret) {	
		$sql 		= 	"Select oauth_server_token.ost_usa_id_ref, oauth_consumer_token.oct_usa_id_ref 
						FROM oauth_server_token,oauth_consumer_token where oauth_server_token.ost_token = '".$access_token."' AND 
						oauth_consumer_token.oct_token = '".$access_token."' AND oauth_server_token.ost_token_secret = '".$access_token_secret."' AND 
						oauth_consumer_token.oct_token_secret =  '".$access_token_secret."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result) && ($result[0]['ost_usa_id_ref'] == $result[0]['oct_usa_id_ref'])){
			return json_encode($result[0]['ost_usa_id_ref']);
		}
		else{
			return '0';
		}
	}


/**
* @method       : old_tokens_verify
* @description	: Verfy if tokens are old
* @param        : access_token and access_token_secret
* @return		: 1 if tokens are old, else 0.
*/
	public function old_tokens_verify($access_token,$access_token_secret) {	
		$sql 		= 	"SELECT * from oauth_server_token JOIN oauth_consumer_token ON 
						oauth_consumer_token.oct_usa_id_ref = oauth_server_token.ost_usa_id_ref 
						WHERE oauth_consumer_token.oct_token != '".$access_token."' AND oauth_server_token.ost_token = '".$access_token."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}


/**
* @method       : register_user
* @description	: registers a user in oauth server and consumer registry tables
* @param        : email, userid, name, callback url, application url for registration
* @return		: All the entries from server registry and consumer registry tables, taking JOIN of both.
*/
	public function register_user($user_id) {	
		$sql 		= 	"SELECT * FROM oauth_server_registry INNER JOIN oauth_consumer_registry ON 
						oauth_server_registry.osr_usa_id_ref='".$user_id."' AND oauth_consumer_registry.ocr_usa_id_ref='".$user_id."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}


/**
* @method       : check_user_validation
* @description	: Validates if userid and email exists in user's record. 
* @param        : email, userid for registration
* @return		: Return 0 if validation is false, else 1.
*/
	public function check_user_validation($requester_email) {
		$sql 	= 	DB::SELECT('id','name')->FROM('admin_users')->WHERE('email', '=', $requester_email)->execute()->as_array();
		return $sql;
	}


/**
* @method       : check_valid_access
* @description	: Validates if user record is present in users table and not in access tables. 
* @param        : username and password
* @return		: Return true if record is present in users table and not in tokens table, else false
*/
	public function check_valid_access($email,$password) {
		$sql 		= 	"SELECT id from admin_users where email = ".$this->db->escape($email)." AND AES_DECRYPT(password, '".ENCRYPT_KEY."') = '".$password."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result)) {
			$sql 		= 	"SELECT * from oauth_consumer_token where oct_usa_id_ref = '".$result[0]['id']."'";
			$result1	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			return $result1;
		}
		else {
			return False;
		}
	}


/**
* @method       : unlink_account
* @description	: Unlink user by removing tokens from server and consumer tables.
* @param        : access_token and access_token_secret
* @return		: Unlink Information i.e. Account Unlinked or Invalid Tokens etc. in json format.
*/
	public function unlink_account($access_token,$access_token_secret) {
		$sql 		= 	"SELECT oauth_server_token.ost_usa_id_ref, oauth_server_token.ost_token_secret, oauth_consumer_token.oct_usa_id_ref,
						oauth_consumer_token.oct_token_secret FROM oauth_server_token, oauth_consumer_token WHERE 
						oauth_server_token.ost_token =  '".$access_token."' AND oauth_consumer_token.oct_token =  '".$access_token."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result) && ($result[0]['ost_usa_id_ref'] == $result[0]['oct_usa_id_ref']) && ($result[0]['ost_token_secret'] == $access_token_secret) && ($result[0]['oct_token_secret'] == $access_token_secret)){
			$delete_query 	=	"DELETE FROM oauth_server_token, oauth_consumer_token USING oauth_server_token INNER JOIN 
								oauth_consumer_token ON oauth_server_token.ost_usa_id_ref = oauth_consumer_token.oct_usa_id_ref WHERE 
								oauth_server_token.ost_usa_id_ref =  '".$result[0]['ost_usa_id_ref']."' AND oauth_consumer_token.oct_usa_id_ref =  '".$result[0]['oct_usa_id_ref']."'";
			$query  		=  $this->_db->query(Database::DELETE, $delete_query, true);
			if(!$query){
				return json_encode('Unlink is not possible');
			} 
			else {
				return json_encode('Account Unlinked');
			}
		}
		else{
			return json_encode('Invalid Tokens');
		}
	}
	
	
/**
* @method       : check_lockflag
* @description	: Returns the value of flag set
* @param        : Email
* @return		: Value of flag set i.e. 1 if lock or 0 if unlock
*/
	public function check_lockflag($access_token,$access_token_secret) {
		$sql 	= "SELECT oauth_consumer_token.flag, admin_users.company_id, oauth_consumer_token.oct_usa_id_ref FROM oauth_consumer_token JOIN admin_users
				on admin_users.id = oauth_consumer_token.oct_usa_id_ref where oauth_consumer_token.oct_token = '".$access_token."'
				AND oauth_consumer_token.oct_token_secret = '".$access_token_secret."' AND oct_token_type = 'access'";
			
		$result = DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
/**
* @method       : 	access_user_flag 
* @description	: 	Function to set the access flag.
* @param        : 	access_token,
* 					access_secret,
* 					flag
* 					flag : 	TRUE : set the flag
* 							FALSE : unset the flag
* @return		: 	Returns TRUE/FALSE
*/
	public function access_user_flag($access_token,$access_token_secret, $flag) {
		if($flag){
			$flag	= "flag = '1'";
		} else {
			$flag	= "flag = '0'";
		}
		$sql 		= " UPDATE oauth_consumer_token
				 SET ".$flag."
				 WHERE 	oct_token = '".$access_token."' AND oct_token_secret ='".$access_token_secret."' AND oct_token_type='access'";
		$result 	= DB::query(Database::UPDATE, $sql)->execute();
		return $result;

	}

/**
* @method       : 	delete_user_by_user 
* @description	: 	Function to delete the company entry from outh registry function. 
* @param        : 	user_id which is 'id from admin_users table'
* 					flag : 	TRUE : set the flag
* 							FALSE : unset the flag
* @return		: 	Returns TRUE/FALSE
*/
	public function delete_user_by_user($user_id) {
		$sql 		= "DELETE FROM oauth_consumer_registry, oauth_server_registry, oauth_consumer_token, oauth_server_token USING 
						oauth_consumer_registry JOIN oauth_server_registry ON oauth_consumer_registry.ocr_usa_id_ref = oauth_server_registry.osr_usa_id_ref 
						JOIN oauth_consumer_token ON oauth_consumer_registry.ocr_usa_id_ref = oauth_consumer_token.oct_usa_id_ref 
						JOIN oauth_server_token ON oauth_server_registry.osr_usa_id_ref = oauth_server_token.ost_usa_id_ref 
						WHERE oauth_consumer_registry.ocr_usa_id_ref ='$user_id'";
		$result 	= DB::query(Database::DELETE, $sql)->execute();
		return $result;
	}	
}