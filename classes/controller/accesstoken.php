<?php
/**
* @File 		 : accesstoken.php - Controller
* @Created		 : June 17, 2013
* @Last Modified : 
* @Description   : Requseting for access tokens.
* Copyright (c) 2011 Acclivity LLC 
*/
include_once DOCUMENT_ROOT.'/application/classes/vendors/oauth/oauth_lib/server/core/init.php';
class Controller_Accesstoken extends Controller {
/**
* @method       : action_access_token
* @description	: Requseting for access tokens.
* @param        : none
* @return		: none
*/
	public function action_access_token() {
        try{
	        $server = new OAuthServer();
    	    $server->accessToken();
        }
        catch (OAuthException2 $e){
       		echo '<strong>Error: ' . $e->getMessage() . '</strong><br />';
        }
	}
}