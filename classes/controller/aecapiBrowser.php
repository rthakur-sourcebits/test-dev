<?php   
defined('SYSPATH') or die('No direct script access.');

require Kohana::find_file('classes', 'library/OauthServerLibrary');
/**
 * @File : aecapi.php Controller
 * @Class : Controller_Aecapi_browser
 * @Description: This class file holds the operations of aec api for testing.
 * Copyright (c) 2011 Acclivity Group LLC
 * 
 * 	Update log:    this will be replica for AEC API API.
 * 			<2013-07-03> - 	set flag function, reset flag function.
 * 			<2013-07-08> - 	added function action_unLink
 * 			<2013-07-08> - 	added function action_checkRegistered
 * 			<2013-07-08> - 	added function action_setAccessFlag
 * 			<2013-07-09> - 	added function action_testLogin  
 * 				
 */
 
 define ('AEC_INIT_SYNC', 1);
 define ('AED_FORCE_SYNC', 2);
class Controller_Aecapibrowser extends Controller 
{		
 	/**
	 * @Method: __construct 
	 * @Description: This method calls the validation session and checks user is authourized to access the AEC API from OAuth
	 * 				 if the user is not authorized then, will redirect him to get authorize itself. 
	 */
	
	public function __construct(Request $request)
	{
		parent::__construct($request);
	}		

	
	/**
	 * @Access		:	Public
	 * @Function	:	action_index
	 * @Description	:	AEC API controller index page
	 */
	public function action_index(){
		echo "AEC API browser";
		die;
	}
	
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_testLogin
	 * @Description	:	function for user to get login to AEC API via. this form 
	 * @Return 		: 	access token 
	 */
	public function action_testLogin(){
		?>
		<form enctype='multipart/form-data' method='POST' action='<?php echo SITEURL; ?>/aecapibrowser/loginUser' >
			<table>
			<tr>
			<td>User Name :</td><td><input type="text" name='username' id='username' />
			</tr>
			<tr>
			<td> Password :</td><td><input type="password" name='password' id='password' />
			</tr>
			<tr>
			<td>Force :</td>
			<td>
			<input type="radio" name="force" id="force" value="1" >Set(1)
			<input type="radio" name="force" id="force" value="0" checked="TRUE">UnSet(0)
			</td>
			</tr>
			<tr>
			<td></td>
        	<td><button>Submit</button></td>
        	</tr>
        	</table>
    	</form>
	    <?php 
	    die;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_getLogin
	 * @Description	:	function for user to get login in himself to thE AEC API and set session company_id
	 * @Return 		: 	access token 
	 */
	public function action_loginUser(){
		
		// this will just check the user and redirect him.
		$oauth_server_lib 	= new OauthServerLibrary;
		$company_id 		= NULL;
		$user_id 			= NULL;
		$res["success"]		= FALSE;
		
		if(!isset($_POST['force'])){
			$_POST['force']=0;
		} 
		if(isset($_POST['username'])&&isset($_POST['password'])){
			$result 	= $oauth_server_lib->login_customized($_POST['username'],$_POST['password'], $_POST['force']); // force login
			
			if(isset($result)&&!empty($result)){
				if(!empty($result['access_token'])&&!empty($result['access_token_secret'])){ // for now we are storing in session but we will just return SESSION data to the user.				
					$_SESSION['aec_token']	= $result['access_token'];
					$_SESSION['aec_secret']	= $result['access_token_secret'];

					$res['aec_token']	= $result['access_token'];
					$res['aec_secret']	= $result['access_token_secret'];
					$res['success']		= TRUE;
				}
			}
			else{
				$res["success"]	= FALSE;
				$res["error_code"]	= 6;
				$res["error"] 		= Kohana::message('error', 'aecapi6'); // not authorized user
			}
		}else{
			$res['success']= FALSE;
		}
		$res = json_encode($res);
		echo $res; die;
	}

	/**
	 * @Access		:	private
	 * @Function	:	action_verifyAccessToken
	 * @Description	:	function for user to check if the user access token exist.
	 * @Return 		: 	boolean
	 */
	private function action_verifyAccessToken($access_token=NULL, $access_secret=NULL){		
		$oauth_server_lib 	= 	new OauthServerLibrary;
		$result 	= $oauth_server_lib->access($access_token, $access_secret);
		return $result;
		
	}
	
	
	/**
	 * @Access		:	private
	 * @Function	:	check_init_force
	 * @Description	:	function for check:	If there is any master force sync request from AED side and
	 * 										If any INIT-SYNC from AEC side.
	 * @Return 		: 	data
	 */
	private function check_init_force($company_id, $module, $master_content){
		$aecapi_master_m 	= new Model_Aecapi_Master; 
		
		// checking for AEC INIT/FORCE sync. checking 3 things:
		// 1. If the sync exists for the company id: If not exists then, create sync variable.
		// 2. if exits then if the SYNC field is 1 then, we will got for force sync response, 
		// 3. If exists & the sync field is 0 then,  we will response with normal sync process.
		
		// checking for AED INIT/FORCE sync.
		if(isset($master_content['init_force_sync']) && $master_content['init_force_sync']==TRUE){
			return AED_FORCE_SYNC; 	// we got the instruction from AEC that, we need to do force sync activity i.e. delete and insert and coming data.  
			 
		}else if($aecapi_master_m->check_init_force($company_id, $module, $master_content)){
	 		return AEC_INIT_SYNC; // here we will do just a response saying we want data as INIT sync.
	 		
	 	}else {
	 		return FALSE; // normal sync
			
	 	}
	}
	
	/**
	 * @Access		:	public
	 * @Function	:	action_sync
	 * @Description	:	function for syncing module
	 * @Return 		: 	boolean
	 */
	public function action_sync(){  
		$_SESSION['aec_token'] 	= $_GET['aec_token'];
		$_SESSION['aec_secret'] = $_GET['aec_secret'];
		$module 				= $_GET['module'];
		$oauth_server_lib 		= new OauthServerLibrary;
		$company_id				= NULL;
		$user_id 				= NULL;
		$response 				= NULL;
		
		// checking the json file.
		if(isset($_FILES['file']['tmp_name']))
		{
			$contents = file_get_contents($_FILES['file']['tmp_name']);
		}else {
			if(isset($_POST['json'])){
				$contents = $_POST['json'];
			}else{
				echo "Data Error.";
				die;
			}
		}
		
		// checking the tokens whether the token is signed.
		if(isset($_GET['aec_token'])&&isset($_GET['aec_secret'])&& $oauth_server_lib->sign_request($_GET['aec_token'], $_GET['aec_secret'])){ // for now we are using session otherwise will go for GET request variable.
			$flag_data = $oauth_server_lib->check_flag($_GET['aec_token'], $_GET['aec_secret']);
			
			if(isset($flag_data['flag']) && !$flag_data['flag']){ // checking flag here			
				// set the user access flag : locking 
				$oauth_server_lib->access_user_flag($_GET['aec_token'], $_GET['aec_secret'], 1);
				
				$company_id = $flag_data['company_id'];
				$user_id 	= $flag_data['user_id'];
				if(isset($module)&&isset($company_id)){					
					switch($module)
					{
						case "customer":
							// call customer module code
							if(isset($contents)){ 
								$response = $this->customer_sync($company_id, $contents);
							}
							break;
						default:
							// some default module or error message no such module.
								echo "default";
								die;
							break;
					}
				}else{
					echo "bad module or no company id";
				}
			} else {
				echo "Try again later: Not authorized this time......";
			}			
		} else {			
			echo  "user is not authorized to login & it will be ask to login";
		}
		$response = json_encode($response);
		echo $response;
		// un-set the user access flag : unlocking 
		$oauth_server_lib->access_user_flag($_GET['aec_token'], $_GET['aec_secret'], 0);
		die;
	}
	
	/**
	 * @Access		:	private
	 * @Function	:	customer_sync
	 * @Description	:	function for syncing customer module.
	 * @Return 		: 	data
	 */
	private function customer_sync($company_id, $content){ //input and output will be json file.		
		$content= json_decode($content, TRUE);
		try{
			$aecapi_master_m 	= new Model_Aecapi_Master;
			$aecapi_customer_m 	= new Model_Aecapi_Customer; 
			
			$res = array();
			// checking operation: // we have to loop this for various operation.
			// 1. insert 
			// 2. update
			// 3. delete
			
			if(isset($_POST)){
				$init_force = FALSE; 
								
				// Check initial sync and force sync here.
				// if the content is master in update then, the content will be skipped.
				// even the response of the same will be different for the master data.
				if(isset($content['master']) && !empty($content['master'])){
					$init_force	= $this->check_init_force($company_id, "customer", $content['master']);
				}
				
				if(!$init_force){
					if($_COOKIE){
						echo "NORMAL SYNC <br />";
					}
					$aec_updates	= $aecapi_customer_m->update_customers($company_id, $content); // here I have to check the conflicted records as well and marked it as un-synced.
																	// if the data is marked as UN-SYNC then, will not update the data and will leave it as it is.
																	// and will give AEC a choice to choose from.
				} else {
					$res['master']=array();
					// the response will be based from reply from check_init_force.
					if ($init_force == AED_FORCE_SYNC){
						if($_COOKIE){
							echo "AED_FORCE_SYNC <br />  ";
						}
						$res['master']['init_force_sync'] = false; // here we are sending this because we have force sync the data.
						
						// we need to delete the complete data for customer module.
						$aecapi_customer_m->delete_data($company_id, "customer");
						
						// I need insert all the data.
						$aec_updates = $aecapi_customer_m->update_customers($company_id, $content); // here on data model I need to SKIP master data.
						
					} else if($init_force == AEC_INIT_SYNC){
						if($_COOKIE){
							echo "AEC_INIT_SYNC <br />";
						}
						// this will make AED to send us all the data + command to force sync.
						$res['master']['init_force_sync'] = true;
					}  
				}	
						
				if(isset($aec_updates[0])){ // AEC update reply for presentation 
					$presentation = NULL;
					foreach ($aec_updates[0] as $key => $value) {
						$presentation[$key] = $value;	
					}
				}else{
					$presentation[0]['operation']=0;
					$presentation[0]['operation_performed']=0; 
					$presentation[0]['record_id']=0;
					$presentation[0]['company_id']=0;
					$presentation[0]['sync_status']=0;
				}
																	
			} else {
				// no POST DATA.
			}
			
			// get the customer dirty data from AEC.
			$dirty_data = $aecapi_customer_m->get_customer_to_sync_all_api($company_id);	// function to get the dirty data from customer.(reading UN-SYNC data)
			
			// Update time-stamp and return new time-stamp to AED.
			if( !$init_force || ( isset($init_force) && $init_force == AED_FORCE_SYNC ) ){
				 // changes will performed only when AED want to force sync.
				if (isset($aec_updates[1])){ // AEC update JSON response.
					$res['aec_ack']		= $aec_updates[1];
				}
				if(isset($dirty_data)){
					$res['aec_changes'] = $dirty_data;
				}
			}
			// else this field is already set.
			
			$curr_timestamp 					= $aecapi_master_m->update_aec_timestamp($company_id, "customer");
			$res['master']['last_timestamp'] 	= $curr_timestamp;
			$res['master']['m_id'] 				= $content['master']['m_id'];
			
			if($_COOKIE){
				echo "<br />";
				echo "Operation Performed: ";echo "<br />"; 
				echo "0->INSERT"; echo "<br />";
				echo "1->UPDATE"; echo "<br />";
				echo "2->DELETE"; echo "<br />";
				echo "3->HANDSHAKE"; echo "<br />";
				echo "4->SKIP"; echo "<br />";
				echo "<br />";
			}
			
			if($_COOKIE){// if its a browser request
				echo "<table border=1>";
				echo "<th>Operation</th><th>operation performed</th><th>record id</th><th>company id</th><th>sync status</th>";
				
				foreach ($presentation as $key => $value) {
					echo "<tr>";
					echo "<td>".$presentation[$key]['operation']."</td>";
					echo "<td>".$presentation[$key]['operation_performed']."</td><td>".$presentation[$key]['record_id']."</td>";
					echo "<td>".$presentation[$key]['company_id']."</td><td>".$presentation[$key]['sync_status']."</td>";
					echo "</tr>";
				}
				echo "</table>";
				echo "<br />";
			}
			
			return $res;
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}

	
	/**
	 * @Access		:	public
	 * @Function	:	action_register_aed_user
	 * @Description	:	function for AED user to perform the registration to AEC API Oauth(Authentication) Server. 
	 * @param 		:
	 * 					GET: email_id : email id
	 * @Return 		: 	echo TRUE/NOT Authorized : Acknowledgement
	 */
	public function action_register_aed_user(){
		
		// this will just check the user and redirect him.
		$oauth_server_lib 	= 	new OauthServerLibrary;
		if($_GET['email_id']){
			$result['response'] 	= $oauth_server_lib->register($_GET['email_id']);
			$result["success"]	= TRUE;
		}
		echo json_encode($result); die;
	}
	
	
	/**
	 * @Access		:	public
	 * @Function	:	action_testUpload
	 * @Description	:	function for AED user/AEC user to test the API synchronization (Manual Testing)
	 * @param 		:
	 * 					module : e.g. customer
	 * 					aec_token : token key
	 * 					aec_secret : secret key
	 * @Return 		: 	echo form_html
	 */
	public function action_testUpload()
	{
		echo " 	<form enctype='multipart/form-data' method='POST' action='".SITEURL."/aecapibrowser/sync?module=".$_GET['module']."&aec_token=".$_GET['aec_token']."&aec_secret=".$_GET['aec_secret']."' >
				<input type='file' name='file'/>
	        	<button>Submit</button>
	    		</form>"; 
	    die;	
	}
	
	
	/**
	 * @Access		:	public
	 * @Function	:	action_accessFlag
	 * @Description	:	This function will allow to reset flag information.
	 * @Return 		: 	TRUE - flag set and unset successfully.
	 * 					FALSE - flag set and unset failed.
	 */
	public function action_setAccessFlag(){		
		$flag=isset($_GET['flag'])?$_GET['flag']:FALSE;
		
		$aec_token=isset($_GET['aec_token'])?$_GET['aec_token']:FALSE;
		$aec_secret=isset($_GET['aec_secret'])?$_GET['aec_secret']:FALSE;
		
		if($aec_token&&$aec_token){
			$oauth_server_lib 	= 	new OauthServerLibrary;
			
			$result 	= $oauth_server_lib->access_user_flag($_GET['aec_token'], $_GET['aec_secret'], $_GET['flag']);
			
			echo json_encode($result);
		} else{
			echo json_encode("Failed");
			
		}
		die;
	}

	/**
	 * @Access		:	public
	 * @Function	:	action_unLink
	 * @Description	:	This function delete the link i.e access tokens authority.
	 * @Return 		: 	TRUE - flag set and unset successfully.
	 * 					FALSE - flag set and unset failed.
	 */
	public function action_unLink(){
		$aec_token=isset($_GET['aec_token'])?$_GET['aec_token']:FALSE;
		$aec_secret=isset($_GET['aec_secret'])?$_GET['aec_secret']:FALSE;
		
		if($aec_token&&$aec_token){
			$oauth_server_lib 	= new OauthServerLibrary;
			$result 			= $oauth_server_lib->unlink($aec_token, $aec_secret);
			
			echo json_encode($result);
		} else{
			echo json_encode("Failed");
			
		}
		die;
	}

	/**
	 * @Access		:	public
	 * @Function	:	action_unLink
	 * @Description	:	This function delete the link i.e access tokens authority.
	 * @Return 		: 	TRUE - flag set and unset successfully.
	 * 					FALSE - flag set and unset failed.
	 */
	public function action_checkRegistered(){
		$email_id=isset($_GET['email_id'])?$_GET['email_id']:FALSE;
				
		if($email_id){
			$oauth_server_lib 	= new OauthServerLibrary;
			$result 			= $oauth_server_lib->check($email_id);
			
			echo json_encode($result);
		} else{
			echo json_encode("Failed");
			
		}
		die;
	}
			
}