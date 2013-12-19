<?php
include_once DOCUMENT_ROOT.'/application/classes/vendors/Dropbox/session.php';
//include DOCUMENT_ROOT.'/application/classes/vendors/Dropbox/rest.php'; 
include_once DOCUMENT_ROOT.'/application/classes/vendors/Dropbox/client.php'; 

ini_set("memory_limit", "100M");
/**
 * @class : Model_Dropbox
 * @desc  : Class for dropbox connectivity and APIs
 * @author: Shijith M
 * @date  : 12-Oct-2010
 *
 */
class Model_Dropbox extends Model
{
	var $dropbox;
	var $dropbox_email;
	var $dropbox_password;
	var $oauth;
	/*
	 * @Method: connection 
	 * @Description: creates a connection object to the drop by using consumer key, consumer secret, username and password.
	 */
	public function connection($callback = '', $action="")
	{	
		/*if(empty($this->dropbox_email) || empty($this->dropbox_password)) {
			$this->dropbox_email		=	$_SESSION["dropbox_username"];
			$this->dropbox_password		=	$_SESSION["dropbox_password"];
		}*/
		if	($action == 1)
		{
			$session_state_name	=	"admin_state";
		}
		elseif ($action == 'forgot')
		{
			$session_state_name	=	"forgot_state";
		}
		else
		{
			$session_state_name	=	"state";
		}
		/*if(empty($_SESSION[$session_state_name]) || $_SESSION[$session_state_name] ==2)
		{*/
			//$oauth		=	new Dropbox_OAuth_PEAR($_SESSION['consumer_key'], $_SESSION['consumer_secret']);
			/*$oauth			=	new Dropbox_OAuth_PEAR(CONSUMER_KEY, CONSUMER_SECRET); // create dropbox object by using PEAR package
			$this->dropbox	=	new Dropbox_API($oauth);*/
			
			$this->dropbox	=	$this->create_dropbox_object(CONSUMER_KEY, CONSUMER_SECRET);
			
			header('Content-Type: text/plain');
			
			// There are multiple steps in this workflow, we keep a 'state number' here
			
			if (isset($_SESSION[$session_state_name])) 
			{
			    $state = $_SESSION[$session_state_name];
			} 
			else 
			{
			    $state = 1;
			}
			if(empty($_SESSION['oauth_tokens'])) {
				//$tokens = $this->dropbox->getToken($this->dropbox_email, $this->dropbox_password); 
				//if tokens r empty in the session we need to fetch them from the db.
				$_SESSION['oauth_tokens'] 	=	$tokens; // Note that it's wise to save these tokens for re-use.
			}
			//$this->oauth->setToken($_SESSION['oauth_tokens']);
			$_SESSION['dropbox']	=	$this->dropbox;
			
			return true;
		/*}
		else
		{ 	die("ss 2");	
				$oauth 		   = new Dropbox_OAuth_PEAR("mr2d1ak16ac9ykh", "faawn09ehmfe25i");
				$this->dropbox = new Dropbox_API($oauth);
				$_SESSION['dropbox']	=	$this->dropbox;
				$oauth->setToken($_SESSION['oauth_tokens']);	
				
		}*/
	
	}
	
	/**
	 * Function to create dropbox object using pear
	 *
	 * @param unknown_type $consumer
	 * @param unknown_type $secret
	 */
	private function create_dropbox_object($consumer="", $secret="")
	{
		if(isset($_SESSION['oauth_tokens']))
		{
			$consumer	=	$_SESSION['oauth_tokens']['token'];
			$secret		=	$_SESSION['oauth_tokens']['token_secret'];
		}else {
			$query	=	"SELECT AES_DECRYPT(da.token, '".ENCRYPT_KEY."') as token, AES_DECRYPT(da.secret, '".ENCRYPT_KEY."') as secret,
				 		FROM dropbox_account AS da
				 		WHERE da.company_id = '".addslashes($_SESSION['company_id'])."'";
	
			$query	=	DB::query(Database::SELECT, $query);
			$data	=	$query->execute()->as_array();
			$consumer	=	$data['token'];
			$secret		=	$date['secret'];		
		}
				
		$token		=	array("oauth_token_secret"=>$secret,"oauth_token"=>$consumer);
		$session 	= 	new DropboxSession(CONSUMER_KEY,CONSUMER_SECRET,'dropbox',$token);
		return new DropboxClient($session);
	}
	
	/*
	 * @Method: get_user 
	 * @Description: this function get the information dropbox user.
	 */
	public function get_user($admin="")
	{
		try 
		{
			/*if(isset($_SESSION['dropbox']))
			{
				$this->dropbox	=	$_SESSION['dropbox'];
			}
			else
			{	
				$this->connection();
			}*/
			$this->dropbox	=	$this->create_dropbox_object(CONSUMER_KEY, CONSUMER_SECRET);
		} catch(Exception $e) {
			return false;
		}
		return $this->dropbox->getAccountInfo();
	}
	
	/*
	 * @Method: get_file 
	 * @Description: gets the file conntent from the dropbox.
	 */
	public function get_file($filename,$admin="")
	{
		/*if(isset($_SESSION['dropbox']) && is_object($_SESSION['dropbox']))
		{
			$this->dropbox	=	$_SESSION['dropbox'];
		}
		else
		{
			$this->connection();
		}*/
		$this->dropbox	=	$this->create_dropbox_object(CONSUMER_KEY, CONSUMER_SECRET);
		//$this->oauth->setToken($_SESSION['oauth_tokens']);
		$fcontent		=	$this->dropbox->getFile($filename);
		if(empty($fcontent) OR $fcontent == "" OR $fcontent == "null")
		{
			return null;
		}
		$file_content	=	json_decode(utf8_encode($fcontent));
		$file_array		=	Model_Dropbox::object_to_array($file_content);
		return $file_array;
	}
	
	public function get_file_assoc($filename)
	{
		/**if(isset($_SESSION['dropbox']) && is_object($_SESSION['dropbox']))
		{
			$this->dropbox	=	$_SESSION['dropbox'];
		}
		else
		{
			$this->connection();
		}*/
		$this->dropbox	=	$this->create_dropbox_object(CONSUMER_KEY, CONSUMER_SECRET);
		//$this->oauth->setToken($_SESSION['oauth_tokens']);
		$fcontent		=	$this->dropbox->getFile($filename);
		/*}
		catch(Exception $e)
		{
			$this->connection();
			$fcontent		=	$this->dropbox->getFile($filename);
		}*/
		if(empty($fcontent) OR $fcontent == "" OR $fcontent == "null")
		{
			return null;
		}
		$file_content	=	json_decode($fcontent,true);
		//$file_array		=	Model_Dropbox::object_to_array($file_content);
		return $file_content;
	}
	/*
	 * @Method: get_file 
	 * @Description: gets the file conntent from the dropbox.
	 */
	public function upload_file($path, $base_path, $admin="")
	{
		try
		{	
			//$this->connection(null, $admin);
			$this->dropbox	=	$this->create_dropbox_object(CONSUMER_KEY, CONSUMER_SECRET);
			//$this->oauth->setToken($_SESSION['oauth_tokens']);
			//$this->dropbox	=	$this->create_dropbox_object(CONSUMER_KEY, CONSUMER_SECRET);
			$file_content	=	$this->dropbox->putFile($path, $base_path);
		}	
		catch (Exception $e){
			//throw new Exception("Failed to upload file.");
			throw new Exception($e->getMessage());
			return;
		}
		return $file_content;
	}
    
	
    
 	/*
	 * @Method: object_to_array 
	 * @param: object that is converted to array format.
	 * @Description: Converts the given object to array format.
	 */
    static function object_to_array( $object )
    {
        if (!is_object($object) && !is_array($object))
        {
            return $object;
        }
        if (is_object($object))
        {
            $object = get_object_vars($object);
        }
        
        return array_map( array('Model_Dropbox','object_to_array'), $object );
    }
	
	public function arrayToObject($array) 
	{
		if (!is_array($array))
		{
			return $array;
		}
		$object = new stdClass();
		if (is_array($array) && count($array) > 0) 
		{
			foreach ($array as $name=>$value)
			{
				$name = strtolower(trim($name));
				if (!empty($name)) 
				{
					$object->$name = $this->arrayToObject($value);
				}
			}
			return $object;
		}
		else
		{
			return FALSE;
		}
	}
	/*
	 * 
	 */
	public function dropbox_admin()
	{
		$comp_content		=	file_get_contents("media/json/setting.json");		
		$arr_cmp_info		=	json_decode($comp_content,true);
	}
	/**
	 * Method to fetch detail information about the folder.
	 * @param  $path : path to the folder
	 */
	public function getmetadata($path)
	{
		//$this->connection();
		/*if(isset($_SESSION['dropbox']) && is_object($_SESSION['dropbox']))
		{
			$this->dropbox	=	$_SESSION['dropbox'];
		}
		else
		{
			$this->connection();
		}*/
		$this->dropbox	=	$this->create_dropbox_object(CONSUMER_KEY, CONSUMER_SECRET);
		//$this->oauth->setToken($_SESSION['oauth_tokens']);
		return	$this->dropbox->metadata($path);
	}
	/**
	 * Method to delete file from dropbox folder
	 * @param $path : file path
	 */
	public function delete_file($path)
	{
		/*if(isset($_SESSION['dropbox']) && is_object($_SESSION['dropbox']))
		{
			$this->dropbox	=	$_SESSION['dropbox'];
		}
		else
		{
			$this->connection();
		}*/
		$this->dropbox	=	$this->create_dropbox_object(CONSUMER_KEY, CONSUMER_SECRET);
		//$this->oauth->setToken($_SESSION['oauth_tokens']);
		return $this->dropbox->delete($path); // delete the file from dropbox
	}
	
/**
	 * Method to create folder in dropbox directory
	 * @param $path : file path
	 */
	public function create_folder($path)
	{
		$this->dropbox	=	$this->create_dropbox_object(CONSUMER_KEY, CONSUMER_SECRET);
		//$this->oauth->setToken($_SESSION['oauth_tokens']);
		return $this->dropbox->create_folder($path); // delete the file from dropbox
	}
	
	// Check if Apps folder exists in dropbox account
	public function apps_folder_exists($query,$path) {
		$this->dropbox	=	$this->create_dropbox_object(CONSUMER_KEY, CONSUMER_SECRET);
		
			return $this->dropbox->search($query,$path);
	}
	
} // End of dropbox class