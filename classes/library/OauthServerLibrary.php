<?php 
/**
* @File			: 	oauth_server.php - Library
* @Created		: 	<2013-06-13>
* @Updated		: 	<2013-06-28> (Flag Api's added) AND (Customized Login added)
 * 				  	<2013-06-13> Set/Un-Set function for access flag.
 * 				  	<2013-07-08> Added function access_user_flag
 * 
* @Description	: library performing various action like unlink_account, login, register, verify_access_tokens, authorize_tokens etc.
* Copyright (c) 2013 Acclivity LLC 
*/

include_once DOCUMENT_ROOT.'/application/classes/vendors/oauth/oauth_lib/server/core/init.php';
include_once DOCUMENT_ROOT.'/application/classes/vendors/oauth/library/OAuthStore.php';
include_once DOCUMENT_ROOT.'/application/classes/vendors/oauth/library/OAuthServer.php';
include_once DOCUMENT_ROOT.'/application/classes/vendors/oauth/library/OAuthRequester.php';

defined('SYSPATH') or die('No direct script access.');
class OauthServerLibrary {
private $APP_ID = '1';
/**
* @method       : action_unlink
* @description	: Unlink user by removing tokens from server and consumer tables.
* @param        : access_token and access_token_secret
*/
	public function unlink($access_token,$access_token_secret) {
		$Oauth 		= new Model_Oauth;
		$values		= $Oauth->unlink_account($access_token,$access_token_secret);
		return $values;
	}


/**
* @method       : action_check
* @description	: Check if consumer key and secret key exists or not.
* @param        : username
*/
	public function check($email) {
		$Oauth 		= new Model_Oauth;
		$values		= $Oauth->check_key_exist($email);
		return $values;
	}


/**
* @method       : action_access
* @description	: Verfy access tokens
* @param        : access_token and access_token_secret
* @return		: user_id if access tokens are correct, else 0.
*/
	public function access($access_token,$access_token_secret) {
		$Oauth 		= new Model_Oauth;
		$old_tokens	= $Oauth->old_tokens_verify($access_token,$access_token_secret);
		if(!empty($old_tokens)){
			return "You are verifying old access tokens";
		}
		else{
		$values		= $Oauth->check_user_access($access_token,$access_token_secret);
			return $values; 
		}
	}


/**
* @method       : action_register
* @description	: registers a user in oauth server and consumer registry tables
* @param        : email and userid of a user requesting for registration
* @return		: True if user registration is success else False.
*/
	public function register($requester_email) {
		$requester_name 	= 'AccountEdge Desktop';
		$callback_uri 		= '';
		$application_uri 	= '';
		$Oauth 				= new Model_Oauth;
		$requester_email	= strtolower($requester_email);
		$check_user_exist	= $Oauth->check_user_validation($requester_email);
		if(empty($check_user_exist)) {
			return 'Not a valid AccountEdge Cloud user';
		}
		else {
			$user_id		= $check_user_exist[0]['id'];
			$requester_name	= $check_user_exist[0]['name'];
    		$values			= $Oauth->register_user($user_id);
    		if($values){
    			return 'Email already registered';
    		}
    		else{
    			$store   			=  OAuthStore::instance();
    			$consumer 			=  $store->consumer_tokens($this->APP_ID);		//Accessing private variable APP_ID
    			$consumer_key 		=  $consumer['consumer_key'];
    			$consumer_secret	=  $consumer['consumer_secret'];
        		$server = array(
        		'consumer_key' 		=> $consumer_key,
        		'consumer_secret' 	=> $consumer_secret,
        		'server_uri' 		=> '',
        		'signature_methods' => array('HMAC-SHA1', 'PLAINTEXT'),
        		'request_token_uri' => SITEURL.'/requesttoken/request_token/'.$user_id,
        		'authorize_uri' 	=> '',
        		'access_token_uri' 	=> SITEURL.'/accesstoken/access_token'
        		);
        		$details_server = array(
        		'consumer_key'    	=> $consumer_key,
        		'consumer_secret' 	=> $consumer_secret,
        		'requester_email' 	=> $requester_email,
        		'requester_name'  	=> $requester_name,
        		'application_uri' 	=> $application_uri,
        		'callback_uri'    	=> $callback_uri
        		);
        		$register_server = $store->serverRegister($consumer_key, $consumer_secret, $user_id,$requester_email,$requester_name);
        		$update_server   = $store->updateServer($server, $user_id);
        		if(!empty($update_server) && !empty($register_server)){
        			return true;
        		}
        		else{
        			return false;
        		}
        	}
		}
	}


/**
* @method       : action_login
* @description	: Login user, by returning Access tokens to access API's.
* @param        : username and password of a user requesting for login
* @return		: Access Token and Access Token Secret.
*/
	public function login($email,$password) {
	$consumer_key 		= '512d9bdb0993915e143eb837eee28fe6051add945';
	$consumer_secret	= '52f852f8445c9949e94acadfe57f15ee';
	$email				= strtolower($email);
	$Oauth 				= new Model_Oauth;
	$result				= $Oauth->login_user($email,$password,$consumer_key,$consumer_secret);
		if(!empty($result)){
			$user_id 	= $result[0]['osr_usa_id_ref'];;
    		$token 		= OAuthRequester::requestRequestToken($consumer_key, $user_id);
    		$access_tokens = $this->authorize_request($user_id,$consumer_key);
    		return $access_tokens;
    		//exit();
		}
		else{
			return 'Cannot Login';
		}
	}


/**
* @method       : action_authorize_request
* @description	: Requseting for token authorization, if authorized redirect it to access.php
* @param        : user_id and consumer_key
* @return		: none
*/
	public function authorize_request($user_id , $consumer_key) {
		$store  	= OAuthStore::instance();
		$server 	= new OAuthServer();
		$token 		= $store->getAccessTokenToVerify($user_id);
		try {
			$rs 	= $server->authorizeVerify($token);
			$a 		= $server->authorizeFinish('1', $user_id, $token);
			if(!empty($a)){
				//Request::instance()->redirect(SITEURL.'/access/access_request/'.$user_id);
				//$output = Request::factory("access/access_request/{$user_id}")->execute()->response;
				$auth = $this->access_request($user_id,$token);
				return $auth;
			}
		}
		catch (OAuthException $e) {
			die($e->getMessage());
		}
	}


/**
* @method       : access_request
* @description	: Requesting for access tokens and getting it.
* @param        : none
* @return		: access_tokens
*/
	public function access_request($user_id,$token) {
        //$oauth_token 	= $_SESSION['verify_oauth_token'];
        //$user_id 		= $_GET['user_id'];
        $oauth_token		= $token;
		try {
        	$store  		= OAuthStore::instance();
        	$consumer_key	= $store->getConsumerKey($user_id);  
            OAuthRequester::requestAccessToken($consumer_key, $oauth_token, $user_id);
            $access_token 	= $store->fetch_access_tokens($user_id);
        	$access_token_new['access_token']			=	$access_token['oct_token'];
    		$access_token_new['access_token_secret']	=	$access_token['oct_token_secret'];
            $access_token_new['token_type']				=	$access_token['oct_token_type'];
    		return $access_token_new;
        }
        catch (OAuthException $e) {
       		echo '<strong>Error: ' . $e->getMessage() . '</strong><br />';
        }
	}


/**
* @method       : get_access_tokens
* @description	: Returns access tokens if it already exists.
* @param        : username and password
* @return		: access_token and access_token_secret
*/
	public function get_access_tokens($email,$password) {
        $email			= strtolower($email);
		$Oauth 			= new Model_Oauth;
		$check_valid	= $Oauth->check_valid_access($email,$password);
		if(!$check_valid) {
    		return 'Incorrect Login Credentials';
    	}
		else if(!empty($check_valid)) {
            $result		= $Oauth->getaccessTokens($email,$password);
			if(!empty($result)){
    			$result_new['access_token']	=	$result['oct_token'];
    			$result_new['access_token_secret']	=	$result['oct_token_secret'];
				return $result_new;
    		}
    	}
		else {
			return 'Please login once to get the access tokens';
		}
	}


/**
* @method       : sign_request
* @description	: Signing a request and verifying it
* @param        : access_token and access_token_secret
* @return		: true (if verified), else false
*/
	public function sign_request($access_token,$access_token_secret) {
		$store			= OAuthStore::instance();
		$user_id		= $store->getUserId($access_token);
		if(empty($user_id)) {
			return false;
		}
		//$request_uri = 'http://192.168.17.77:81/hello/api_access/'.$access_token.'/'.$access_token_secret;
		$request_uri	= $this->PageURL();
		$params 		= array(
							'method' => 'ping'
		);
		// Obtain a request object for the request we want to make
		$req 			= new OAuthRequester($request_uri, 'GET', $params);
		
		$result 		= $req->doRequest($user_id);
		if(isset($result)) {	
    		$authorized = false;
    		$server = new OAuthServer();
    		$server->setParam('oauth_consumer_key',		$req->getParam('oauth_consumer_key'));
    		$server->setParam('oauth_signature_method',	$req->getParam('oauth_signature_method'));
    		$server->setParam('oauth_timestamp',		$req->getParam('oauth_timestamp'));
    		$server->setParam('oauth_nonce',			$req->getParam('oauth_nonce'));
    		$server->setParam('oauth_signature',		$req->getParam('oauth_signature'));
    		$server->setParam('oauth_token',			$req->getParam('oauth_token'));
    		if ($server->verifyIfSigned()) {
    			$authorized = true;
    		}
    		return $authorized;
		}
	}


/**
* @method       : PageURL
* @description	: It will return the request URL of Server
* @param        : none
* @return		: Current Page URL 
*/
	public function PageURL() {
 		$pageURL = 'http';
 		if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) {
 			$pageURL .= "s";
 		}
 		$pageURL .= "://";
 		if ($_SERVER["SERVER_PORT"] != "80") {
  			$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
 		} 
 		else {
  			$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
 		}
 		return $pageURL;
	}
	
	
/**
* @method       : check_flag
* @description	: Returns the value of flag set
* @param        : Email
* @return		: Value of flag set i.e. 1 if lock or 0 if unlock
*/
	public function check_flag($access_token,$access_token_secret) {
		$Oauth 		= new Model_Oauth;
		$flag		= $Oauth->check_lockflag($access_token,$access_token_secret);
		$flag_changed['flag']	=	$flag[0]['flag'];
		$flag_changed['company_id']	=	$flag[0]['company_id'];
		$flag_changed['user_id']	=	$flag[0]['oct_usa_id_ref'];
		return $flag_changed;
	}
	
	
/**
* @method       : login_customized
* @description	: Login user, by returning Access tokens to access API's depending upon certain conditions.
* @param        : username and password of a user requesting for login
* @return		: Access Token and Access Token Secret.
*/
	public function login_customized($email,$password,$force=0) {
		$consumer_key 			= '512d9bdb0993915e143eb837eee28fe6051add945';
		$consumer_secret		= '52f852f8445c9949e94acadfe57f15ee';
		$email					= strtolower($email);
		$Oauth 					= new Model_Oauth;
		
		$result					= $Oauth->login_user($email,$password,$consumer_key,$consumer_secret);
		$check_tokens_present	= $Oauth->getaccessTokens($email,$password);
		if(!empty($result) && $force==0 && empty($check_tokens_present)){
			$user_id 		= $result[0]['osr_usa_id_ref'];
			$token 			= OAuthRequester::requestRequestToken($consumer_key, $user_id);
			$access_tokens = $this->authorize_request($user_id,$consumer_key);
			return $access_tokens;
			//exit();
		}
		else if(!empty($result) && $force==0 && !empty($check_tokens_present) ){
			
			$check_tokens_present['access_token']=$check_tokens_present['oct_token'];
			$check_tokens_present['access_token_secret']=$check_tokens_present['oct_token_secret'];
			return $check_tokens_present;
		}
		else if(!empty($result) && $force==1){
			$user_id 		= $result[0]['osr_usa_id_ref'];
			$token 			= OAuthRequester::requestRequestToken($consumer_key, $user_id);
			$access_tokens = $this->authorize_request($user_id,$consumer_key);			
			return $access_tokens;
		}
		else{
			return FALSE;
		}
	}

/**
* @method       : 	set_access_flag
* @description	: 	setting the flag for given tokens
* @param        : 	access_token
 * 					access_secret
* @return		:  	TRUE/FALSE
*/
	public function access_user_flag($access_token,$access_secret, $flag) {
        
		if(isset($access_token) && isset($access_secret)){
			
    		$Oauth 		= new Model_Oauth;
			$result		= $Oauth->access_user_flag($access_token, $access_secret, $flag);
	    	
			if($result==1){
    			return "updated";
				
			} else {
				return "already updated";
				
			}
	        	
		} else{
			return FALSE;
		}
	}
	
}