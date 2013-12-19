<?php
/**
* @File 		 : requesttoken.php - Controller
* @Created		 : June 17, 2013
* @Last Modified : 
* @Description   : Requesting for request tokens
* Copyright (c) 2011 Acclivity LLC 
*/
include_once DOCUMENT_ROOT.'/application/classes/vendors/oauth/oauth_lib/server/core/init.php';
class Controller_Requesttoken extends Controller {
/**
* @method       : action_request_token
* @description	: Requesting for request tokens
* @param        : none
* @return		: none
*/
	public function action_request_token($user_id) {
    	try {
        	$server = new OAuthServer();
        	$token = $server->requestToken($user_id);
        	exit();
        }
        catch (OAuthException2 $e) {
        	echo '<strong>Error: ' . $e->getMessage() . '</strong><br />';
        }
	}
}
?>