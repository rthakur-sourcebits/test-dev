<?php defined('SYSPATH') or die('No direct script access.');
ini_set('max_execution_time', 3000);
ini_set("memory_limit", "100M");
/**
 * 	@File		:	user.php Model
 * 	@Class		:	Model_User
 * 	@Date		:	31 Aug 2010
 * 	@Description:	User login authentication model.
*/
class Model_User extends Model {
 
    public function __construct($id = NULL)
	{
		// load database library into $this->db (can be omitted if not required)
		parent::__construct($id);
	}
	
	/**
	 * @function		:	verify_users
	 * @param1			:	user-name (entered by user)
	 * @param2			:	password (entered by user)
	 * @return			: 	user info as array if user is valid else false.
	 */
	public function verify_users($email, $password, $admin="")
	{
		$query	=	"SELECT du.record_id AS record_id, da.device_name, AES_DECRYPT(da.token, '".ENCRYPT_KEY."') as token, AES_DECRYPT(da.secret, '".ENCRYPT_KEY."') as secret,
					 du.id, du.first_name, du.last_name, du.company_id, du.type, du.status, du.display_rate, 
					 c.suspend_status, c.activation_id
					 FROM dharma_users AS du, company AS c, dropbbox_account AS da
					 WHERE c.id = du.company_id
					 AND da.company_id = c.id
					 AND du.email = '".addslashes($email)."'
					 AND du.password = AES_ENCRYPT('".addslashes($password)."','".ENCRYPT_KEY."')
					 AND c.status_flag = 1";
		$query	=	DB::query(Database::SELECT, $query);
		$data	=	$query->execute()->as_array();
		if(empty($data)) return false;
		else return $data;
	}
	
	/*
	 * get the company info based on the email id.
	 */
	public function get_company($company_id){
		$query	=	"SELECT * FROM company WHERE id = '".$company_id."'";
		$query	=	DB::query(Database::SELECT, $query);
		$data	=	$query->execute()->as_array();
		return $data;
	}
	
	
	
	
	/**
	 * @Function 	:	create_json_sessions
	 * @Description	:	call each files from dropbox and filter array by adding index id then create session and store array into it.
	*/
	// modified : <10-10-2013> - reading preference data for more field.
	public function create_json_sessions($admin = "")
	{
		$json			=	new Model_Json;
		$dropbox_model	=   new Model_dropbox;
		try {
			$handshake	=	$this->check_handshake_updates();
		} catch(Exception $e){
			die($e->getMessage());
		}
		
		try{
			$all_jobs							=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Jobs.json');
			$json->file_content					=	$all_jobs; // why we are doing this
			$json->files_list['Jobs']			=	$all_jobs;
		}
		catch(Exception $e)
		{
			$json->files_list['Jobs']		=	"";
		}
		try {
			$preferences		=	$dropbox_model->get_file_assoc(DROPBOXFOLDERPATH.'Preferences.json');
			$json->files_list['Preference'] = $preferences;
			if(isset($preferences['CurrencySymbol'])){
				$_SESSION['CurrencySymbol']	= 	$preferences['CurrencySymbol'];
			}	else {
				$_SESSION['CurrencySymbol']	= "$";
			}
		}
		catch(Exception $e){
			$json->files_list['Preference']	=	array();
			$_SESSION['CurrencySymbol']	=	"$";
		}
		try{
			$json->files_list['Employees']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Employees.json');
			$_SESSION['Employees'] = 1;		
		}
		catch(Exception $e)
		{
			$json->files_list['Employees']	=	array();
		}
		try{
			$json->files_list['Activities']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Activities.json');
		}
		catch(Exception $e)
		{
			$json->files_list['Activities']	=	array();
		}
		try{
			$json->files_list['Customers']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Customers.json');
		}
		catch(Exception $e)
		{
			$json->files_list['Customers']	=	array();
		}
		try{
			$json->files_list['Vendors']	=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Vendors.json'); // contract users
			$_SESSION['Contracts'] = 1;
		}
		catch(Exception $e)
		{
			$json->files_list['Contracts']	=	array();
		}
		try{
			$json->files_list['Items']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Items.json');
		}
		catch(Exception $e)
		{
			$json->files_list['Items']	=	array();
		}
		try{
			$json->files_list['Accounts']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Accounts.json');
			
		}
		catch(Exception $e)
		{
			$json->files_list['Accounts']	=	array();
		}
		try{
			$json->files_list['Taxes']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'TaxCodes.json');
		}
		catch(Exception $e)
		{
			$json->files_list['Taxes']	=	array();
		}
		try{
			$json->files_list['ConsolidatedTaxes']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'ConsolidatedTaxCodes.json');
		}
		catch(Exception $e)
		{
			$json->files_list['ConsolidatedTaxes']	=	array();
		}
		try{
			$json->files_list['SalesAndPurchase']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'SaleAndPurchaseInformation.json');
		}
		catch(Exception $e)
		{
			$json->files_list['SalesAndPurchase']	=	array();
		}
		try{
			$json->files_list['CustomListAndFieldNames']	=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'CustomListAndFieldNames.json');
		}
		catch(Exception $e)
		{
			$json->files_list['CustomListAndFieldNames']	=	array();
		}
		//for custom list
		try{
		    $json->files_list['CustomLists']	=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'CustomLists.json');
		}
	    catch(Exception $e)
		{
		    $json->files_list['CustomLists']	=	array();
		}
		
		$json->filter_json_array();
		return true;
	}
	
	/**
	 * Refresh customer/jobs/activities json files and check for customer hand shake files
	 */
	public function create_customer_json_sessions()
	{
		$json			=	new Model_Json;
		$dropbox_model	=   new Model_dropbox;
		
		try{
			$handshake	=	$this->check_handshake_updates();	
		} catch(Exception $e){

		}	
		try{
			$all_jobs							=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Jobs.json');
			$json->file_content					=	$all_jobs;
			$json->files_list['Jobs']			=	$json->JSON_Query(array('*'), array('IsHeaderJob' => ""));
		} catch(Exception $e) {
			$json->files_list['Jobs']			=	"";
		}
		try {
			$preferences		=	$dropbox_model->get_file_assoc(DROPBOXFOLDERPATH.'Preferences.json');
			$json->files_list['Preference'] = $preferences;
			if(isset($preferences['CurrencySymbol'])){
				$_SESSION['CurrencySymbol']	= 	$preferences['CurrencySymbol'];
			}	else {
				$_SESSION['CurrencySymbol']	= "$";
			}
		}
		catch(Exception $e){
			$json->files_list['Preference']	=	array();
			$_SESSION['CurrencySymbol']	=	"$";
		}
		try{
			$json->files_list['Activities']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Activities.json');
		} catch(Exception $e) {
			$json->files_list['Activities']		=	"";
		}
		try{
			$json->files_list['Employees']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Employees.json');
		} catch(Exception $e) {
			$json->files_list['Employees']		=	"";
		}
		try{
			$json->files_list['Contracts']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Vendors.json');
		} catch(Exception $e) {
			$json->files_list['Contracts']		=	"";
		}
		try{
			$json->files_list['Customers']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Customers.json');
		} catch(Exception $e) {
			$json->files_list['Customers']		=	"";
		}
		try{
			$json->files_list['Items']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Items.json');
		} catch(Exception $e) {
			$json->files_list['Items']		=	"";
		}
		try{
			$json->files_list['Accounts']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'Accounts.json');
		} catch(Exception $e) {
			$json->files_list['Accounts']		=	"";
		}
		try{
			$json->files_list['Taxes']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'TaxCodes.json');
		}
		catch(Exception $e)
		{
			$json->files_list['Taxes']	=	array();
		}
		try{
			$json->files_list['ConsolidatedTaxes']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'ConsolidatedTaxCodes.json');
		}
		catch(Exception $e)
		{
			$json->files_list['ConsolidatedTaxes']	=	array();
		}
		try{
			$json->files_list['SalesAndPurchase']		=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'SaleAndPurchaseInformation.json');
		}
		catch(Exception $e)
		{
			$json->files_list['SalesAndPurchase']	=	array();
		}
		try{
			$json->files_list['CustomListAndFieldNames']	=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'CustomListAndFieldNames.json');
		}
		catch(Exception $e)
		{
			$json->files_list['CustomListAndFieldNames']	=	array();
		}
		//for custom list
		try{
		    $json->files_list['CustomLists']	=	$dropbox_model->get_file(DROPBOXFOLDERPATH.'CustomLists.json');
		}
	    catch(Exception $e)
		{
		    $json->files_list['CustomLists']	=	array();
		}	
		
		$json->filter_json_array();
		return true;
	}
	
	
	/*
	 * 
	 */
	public function get_employee($employee_id)
	{
		$dropbox			=   new Model_dropbox;
		$json				=	new Model_Json;
		$json->file_content	=	$dropbox->get_file(DROPBOXFOLDERPATH.'Employees.json');
		$result				=	$json->JSON_Query(array('*'), array('RecordID' => $employee_id));
		if(empty($result))
		{
			$json->file_content	=	$dropbox->get_file(DROPBOXFOLDERPATH.'Contracts.json');
			$result				=	$json->JSON_Query(array('*'), array('RecordID' => $employee_id));
		}
		$result				=	array_merge(array(), $result);
		return $result;
		
	}
	
	/**
		@function 		:	get_user_types()
		@description	:	get user types (Employee or Contract) 
	**/
	public function get_user_type($employee_id)
	{
		$json		=	new Model_Json;
		$json->file_content	=	$_SESSION['DharmaUsers'];
		$result		=	$json->JSON_Query(array('Type'), array('RecordID' => $employee_id));
		$user_result=	array_merge(array(), $result);
		return $user_result[0]['Type'];
	}
	/*
	 * 
	 */
	public function validate_user_email($email)
	{
		$query	=	"SELECT AES_DECRYPT(password,'".ENCRYPT_KEY."') as pw,first_name,email, id   
					 FROM dharma_users WHERE email = '".$email."'";
		$query	=	DB::query(Database::SELECT, $query);
		$data	=	$query->execute()->as_array();
		return $data;
	}
	
	/**
	@Function 	:	get_user
	@Description:	Get user details from database
	@Param1		:	user id
	@param2		:	company name
	*/
	public function get_user($user_id, $company)
	{
		$query	=	"SELECT du.record_id, du.first_name, du.last_name, du.email, du.type, du.company_id, 
							AES_DECRYPT(au.password, '".ENCRYPT_KEY."') as admin_password, au.email as admin_email,
							da.device_name as device_name, da.email as dropbox_email, AES_DECRYPT(da.password, '".ENCRYPT_KEY."') as dropbox_password
					 FROM dharma_users as du
					 LEFT JOIN admin_users as au 
					 ON au.company_id = du.company_id 
					 LEFT JOIN dropbbox_account da
					 ON da.company_id = au.company_id
					 WHERE du.record_id = '".$user_id."' 
					 AND du.company_id = '".$company."'";
		$query	=	DB::query(Database::SELECT, $query);
		$data	=	$query->execute()->as_array();
		//echo "<pre>";print_r($data);die;
		return $data;
	}
	
	/**
	 * Check whether user is currently logged in or not.
	 * @return : true if logged in else false
	 */
	public function check_user_status()
	{
		if(!empty($_SESSION['employee_id']))
		{
			$query	=	"SELECT * 
						 FROM user_log
						 WHERE session_id = '".session_id()."'";
			$query	=	DB::query(Database::SELECT, $query);
			$data	=	$query->execute()->as_array();	//	die($query);	
			if(empty($data))
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
	
	/**
	 * Check for any customer update in customer cards files.
	 */
	private function check_customer_cards()
	{
		$dropbox		=   new Model_dropbox;
		$json			=	new Model_Json;
		try {
		$metadata_info	  =	 $dropbox->getmetadata(DROPBOXFOLDERPATH."DesktopChanges/Cards"); // read all the files from customer cards folder
		} catch(Exception $e) {
			//return true;
		}
		$files_count	  =	 count($metadata_info['contents']);
		if($files_count == 0)
		{
			$json->files_list['Customers']	=	$dropbox->get_file(DROPBOXFOLDERPATH.'Customers.json');
			$json->filter_json_array();
			return true;
		}
		$arr_customer_id  =  array();
		$unique_customers =	 array();
		foreach($_SESSION['Customers'] as $key=>$customer) {
			$arr_customer_id[$key]	=	$customer['RecordID'];
		}
		$unique_customers	=	array_unique($arr_customer_id);
		for($i=0;$i<$files_count;$i++)
		{
			$file_name		=	$metadata_info['contents'][$i]['path'];
			$filepath 		=	str_replace("%2F", "/", rawurlencode($file_name));
			$fcontent		=	$dropbox->get_file($filepath);
			$field_count	=	count($fcontent);
			
			// read each cards available in the cards folder and check for any update
			for($j=0;$j<$field_count;$j++)
			{
				if($fcontent[$j]['RecordID']<0)
				{
					$record_id		=	($fcontent[$j]['RecordID']*-1);
					$pop_key	=	array_search($record_id, $unique_customers);
					if(!empty($pop_key))
					{
						unset($_SESSION['Customers'][$pop_key]);
					}
				}
				elseif(!in_array($fcontent[$j]['RecordID'], $unique_customers))
				{
					array_push($_SESSION['Customers'], $fcontent[$j]);
				}
			}
			$dropbox->delete_file($file_name);
		}
		
		$_SESSION['Customers']	=	array_merge(array(), $_SESSION['Customers']);
		echo "<pre>";print_r($_SESSION['Customers']);echo "</pre>";die;
	}
	
	public function user_expired($company_id)
	{
		$sql		=	"SELECT DATE_ADD(created_date, INTERVAL 30 DAY) as default_end_date, end_date, expire_date_modify
						 FROM company
						 WHERE id = '".$company_id."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if($result[0]['expire_date_modify'] == 1) {
			$current_end_date	=	$result[0]['end_date'];
		} else {
			$current_end_date	=	$result[0]['default_end_date'];
		}
		$sql	=	"SELECT c.created_date, s.price, '".$current_end_date."' as expire_date, CURDATE() as cur_date
					 FROM company as c
					 LEFT JOIN company_plan as cp
					 ON c.id = cp.company_id
					 LEFT JOIN signup_plan as s
					 ON cp.signup_plan_id = s.plan_id
					 WHERE c.id = '".$company_id."'";
		$query	=	DB::query(Database::SELECT, $sql);
		$data	=	$query->execute()->as_array();		
		//$curdate=	date("Y-m-d H:i:s");
		if($data[0]['price'] == 0 && $data[0]['cur_date'] > $data[0]['expire_date']) {
			return true;
		} else {
			return false;
		}
	}
	
	public function free_plan_user($company_id)
	{
		$sql	=	"SELECT s.price as price
					 FROM company_plan as c
					 LEFT JOIN signup_plan as s
					 ON c.signup_plan_id = s.plan_id
					 WHERE c.company_id = '".$company_id."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if($data[0]['price'] == 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public function get_serail_number($serial_number)
	{
		$sql	=	"SELECT serialnumber
					 FROM company
					 WHERE id='".$serial_number."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) {
			return null;
		} else {
			return $data[0]['serialnumber'];
		}
	}
	
	public function save_email_support_data()
	{
		if(isset($_POST['email'])){
			$_POST['email']=strtolower($_POST['email']);
		}
		
		$sql	=	"INSERT INTO email_support
					 (firstname, lastname, email, description, serialnumber, employee_id, company_id, date)
					 VALUES (
					 	'".addslashes(trim($_POST['firstname']))."', 
					 	'".addslashes(trim($_POST['lastname']))."',
					 	'".addslashes(trim($_POST['email']))."',
					 	'".addslashes(trim($_POST['description']))."',
					 	'".addslashes(trim($_POST['serialnumber']))."',
					 	'".addslashes(trim($_SESSION['employee_id']))."',
					 	'".addslashes(trim($_SESSION['company_id']))."',
					 	now()
					 )";
		$this->_db->query(Database::INSERT, $sql, False);
		return true;
	}
	
	public function save_email_supportadmin_data()
	{
		if(isset($_POST['email'])){
			$_POST['email']=strtolower($_POST['email']);
		}
		$sql	=	"INSERT INTO email_support
					 (firstname, lastname, email, description, serialnumber, employee_id, company_id, date)
					 VALUES (
					 	'".addslashes(trim($_POST['firstname']))."', 
					 	'".addslashes(trim($_POST['lastname']))."',
					 	'".addslashes(trim($_POST['email']))."',
					 	'".addslashes(trim($_POST['description']))."',
					 	'".addslashes(trim($_POST['serialnumber']))."',
					 	'0',
					 	'".addslashes(trim($_SESSION['company_id']))."',
					 	now()
					 )";
		$this->_db->query(Database::INSERT, $sql, False);
		return true;
	}
	
	public function validate_admin_email($email)
	{
		$query	=	"SELECT AES_DECRYPT(password,'".ENCRYPT_KEY."') as pw, name as first_name, lastname, email, id  
					 FROM admin_users
					 WHERE email = '".$email."'";
		$query	=	DB::query(Database::SELECT, $query);
		$data	=	$query->execute()->as_array();
		return $data;	
	}
	
	/**
	 * Function to retrieve user logged in user name
	 * return : username
	 */
	
	public function get_logged_user_name()
	{
		$sql	=	"SELECT first_name, last_name, email
					 FROM dharma_users
					 WHERE record_id = '".$_SESSION['employee_id']."'
					 AND company_id = '".$_SESSION['company_id']."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($data)) {
			return ucfirst($data[0]['first_name'])." ".ucfirst($data[0]['last_name']);
		} else {
			return null;
		}
		
	}
	
	/**
	 * Function to get all the information about the user based on the auto id
	 */
	public function get_user_by_logged_id($id)
	{
		$query	=	"SELECT du.id, du.record_id AS record_id, du.first_name, du.last_name, du.company_id, du.type, du.status, 
							du.display_rate, c.suspend_status, c.activation_id
					 FROM dharma_users AS du, company AS c
					 WHERE c.id = du.company_id
					 AND du.id = '".$id."'";

		$query	=	DB::query(Database::SELECT, $query);
		$data	=	$query->execute()->as_array();
		return $data;
	}
	
/**
	 * store the logged in user details, user can login only once at single time
	 */
	public function create_user_log()
	{
		/**delete the user who already logged in**/
		$sql	=	"DELETE FROM user_log
					 WHERE user_id  = '".$_SESSION['employee_id']."'
					 AND company_id = '".$_SESSION['company_id']."'";
		$query  =  $this->_db->query(Database::DELETE, $sql, true);
		/**store the new user**/
		$sql	=	"INSERT INTO user_log (user_id, company_id, session_id, login_time)
					 VALUES(
						'".$_SESSION['employee_id']."',
						'".$_SESSION['company_id']."',
						'".session_id()."',
						now()
					 )";
		$query  =  $this->_db->query(Database::INSERT, $sql, true);
	}
	
	// Function to check the handshake updates (jobs/customers) from dropbox
	public function check_handshake_updates() {
		$dropbox			=	new Model_dropbox(); 
		$customer_jobs		=	new Model_Customerandjobs;
		$customer_handshake	=	array();
		$total_files		=	0;
		//$path	 = str_replace("%2F", "/", rawurlencode("DesktopChanges/Handshake"));
			
	try {
			if($dropbox->apps_folder_exists("DesktopChanges",DROPBOXFOLDERPATH)){
				if($dropbox->apps_folder_exists("Handshake",DROPBOXFOLDERPATH."DesktopChanges/")){	// check whether the desktopchanges/handshake path exists or not
					$handshake_files	=	$dropbox->getmetadata(DROPBOXFOLDERPATH."DesktopChanges/Handshake/");
					$total_files		=	count($handshake_files['contents']);
				}
				if($total_files == 0){  
						return true;
				}
				for($i=0;$i<$total_files;$i++) {
					try {
						$file_name	=	$handshake_files['contents'][$i]['path'];
						$arr_file	=	$dropbox->get_file($file_name);
						foreach($arr_file as $arr_handshake) {
							if(stristr($file_name, "CardHandshake")) {
								$record_id		=	isset($arr_handshake['RecordID'])?$arr_handshake['RecordID']:"";
								$customer_id	=	isset($arr_handshake['ThirdPartyUniqueID'])?$arr_handshake['ThirdPartyUniqueID']:"";
								$customer_handshake_data	=	array("record_id" => $record_id);
								$status	=	$customer_jobs->update("customers", $customer_handshake_data, $customer_id);
							} elseif(stristr($file_name, "JobHandshake")) {
								$record_id	=	isset($arr_handshake['RecordID'])?$arr_handshake['RecordID']:"";
								$job_id		=	isset($arr_handshake['ThirdPartyUniqueID'])?$arr_handshake['ThirdPartyUniqueID']:"";
								$job_handshake_data	=	array("record_id" => $record_id);
								$status	=	$customer_jobs->update("jobs", $job_handshake_data, $job_id);
							}
						}
						$delete_status	=	$dropbox->delete_file($file_name); // Delete handshake file from dropbox
							
					} catch(Exception $e) {
						continue;
					}
				}
			}
		} catch (Exception $e) {
			throw new Exception("Desktopchanges and handshake folders does not found.");
		}
	
		return true;
	}
}