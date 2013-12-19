<?php defined('SYSPATH') or die('No direct script access.');
/*
 * @Class: Model data.php
 * @Created: 2-09-2010
 * @Modified:1-08-2010 
 * @Description: This model file uses for json or ajax action.
 * Update : <01-07-2013> 	: 	added a functionality for new AEC API.
 * 			<10-07-2013> 	:	check_init_force function for checking the master table for init/force sync.
 * 			<29-10-2013>	:	adding update_company function to update company fields from preference fields.
 * 			11-11-2013		: 	removes function update_consolidated_taxes, update_taxes AND tax_exists
 */
 
class Model_Data extends Model
{
	
	/**
	* @Function		:	auto_list;
	* @Description	:	get the list of names that are used for auto list.
	*/
	public function auto_list($list)
	{
		$customers_name = '';
		$activites_name = '';
		$jobs_name = '';
		$payroll_name = '';
	   
		$customers_name = $this->get_names($list['customers'], 'CompanyOrLastName','RecordID','customers');
		$activites_name = $this->get_names($list['activities'], 'ActivityName','ActivityID','activities');
		$jobs_name 		= $this->get_names($list['jobs'], 'JobName','JobNumber','jobs');

		$list_names 	= array(
								'customers_name' => $customers_name,
								'activites_name' => $activites_name,
								'jobs_name' => $jobs_name
								//'payroll_name' => $payroll_name
					      );
		return $list_names;
	}
	
	
	public function customer_job_list($list)
	{
		$customers_name = '';
		$activites_name = '';
		$jobs_name = '';
		$payroll_name = '';
	   
		$jobs_name 		= $this->get_names($list['jobs'], 'JobName','JobNumber','jobs');

		$list_names 	= array(
								'jobs_name' => $jobs_name
								//'payroll_name' => $payroll_name
					      );
		return $list_names;
	}
	/**
	* @Function		:	get_names;
	* @Description	:	picks the names from the associative array and converts to a string seperated with commos.
	*/
	public function get_names($list, $flag, $flag2, $element)
	{
		//echo "<pre>";
		$name_array 		=   array();
		$sort_name_array	=	array();
		$list_count			=	count($list);
		
		if($list)
		{			
			for($i=0; $i<$list_count; $i++)
			{
				if(!empty($list[$i]['FirstName']))
				{
					$field_name =   $list[$i]['FirstName']." ".$list[$i][$flag];
					$field_name1	=	str_replace("'"," ",$field_name);
					
					$field_id	=	$list[$i][$flag2];
					$field_id1	=	str_replace("'"," ",$field_id);
					$temp = array(	'name' => addslashes($field_name1),
									'id' => addslashes($field_id1)
									);			
				}
				else
				{
					if(!empty($list[$i][$flag])) {
						$field_name =   $list[$i][$flag];
					} else {
						//$field_name =   "";
						continue;
					}
					$field_name1	=	str_replace("'"," ",$field_name);
					if(!empty($list[$i][$flag2])) {
						$field_id =   $list[$i][$flag2];
					} else {
						//$field_id =   "";
						continue;
					}

					//$field_name =   $list[$i][$flag];
					$field_name1	=	str_replace("'"," ",$field_name);
					
					//$field_id	=	$list[$i][$flag2];
					$field_id1	=	str_replace("'"," ",$field_id);
					
				if(isset($list[$i]['LinkedCustomerRecordID'])){
						$customer_id 	=	$list[$i]['LinkedCustomerRecordID'];
						$customer_id1	=	str_replace("'"," ",$customer_id);
						$temp 			= 	array(	'name' => addslashes($field_name1),
											'id' => addslashes($field_id1),
											'cust_id' => addslashes($customer_id1)
											);
					} else{
						$temp 	=	array(	'name' => addslashes($field_name1),
											'id' => addslashes($field_id1)
										);
					}
					
					
				}
				array_push( $name_array, $temp);
			}
			$sort_name_array	=	$this->sort_name_array($name_array, $element);
		}	
	//	echo "<pre>";print_r($sort_name_array);echo "</pre>";die;
		return $sort_name_array;
	}
	
	/*
	 *@Function		:	check_cookie
	 *@Description	:	Check cookie is available in browser or not. If yes login automatically
	 */
	public function check_cookie()
	{ 
		$dropbox_model	=	new Model_dropbox;
		$user			=	new Model_User;
		
		if (isset($_COOKIE['keep_loged']))
		{
			$data		=	$dropbox_model->object_to_array(json_decode($_COOKIE['keep_loged']));
			$employe_id	=	base64_decode($data['employee_id']);
			$company	=	base64_decode($data['company']);
		}
		else
		{
			return false;
		}
		// based on the company name get cunsumer key and customer key.
		
		$user_info	=	$user->get_user($employe_id, $company);
		if(!empty($user_info))
		{
			// conect to the dropbox.
			$_SESSION['dropbox_username']	=	$user_info[0]['dropbox_email'];
			$_SESSION['dropbox_password']	=	$user_info[0]['dropbox_password'];
			
			$_SESSION['employee_id']   		=	$user_info[0]['record_id'];
			$_SESSION['employee_name']  	=	$user_info[0]['first_name'];
			$_SESSION['employee_lastname']  =	$user_info[0]['last_name'];
			$_SESSION['company']			=	$company;
			$_SESSION['company_id']			=	$user_info[0]['company_id'];
			$_SESSION['User_type']			=	($user_info[0]['type'] == "Employee") ? "Employees":"Contractor"; // store filename based on user type.
			$_SESSION['company']   			=	$company;
			$_SESSION['synced_slips_view']	=	0; //display un-synced slips
			$_SESSION['sync_alert_message']	=	1; //display sync alert message
			$login		=   new Model_login;
			$this->define_dropbox_constants($user_info[0]);
			try
			{
				$dropbox_model->connection();
				$login->login_validate();
				return true;
			}
			catch(Exception $e)
			{
				throw new Exception($e->getMessage());
			}
		}
		else
		{
			return false;
		}
		
		// send the call back url and redirect back to the url.
	}
	
	/** 
		@function		:	update_user
		@description	:	update the user auth code.
	*/
	public function update_user($type, $id, $data) {
		// read data 
		// data key and data value
		$update_values="";
		if(isset($data))
		{
			$flag = 0;
			foreach ($data as $key => $value) {
				
				if($flag==0){
					$flag=1;
				}else{
					$update_values.=", ";
				}
				
				if($key =="password"){
					$update_values.=$key ."=AES_ENCRYPT('".$value."', '".ENCRYPT_KEY."')";
				} else{
					$update_values.=$key ."='".$value."' ";	
				}		
			}		
		} 
		if(isset($type)){// type will be admin_user and dharma_user
			$sql = " UPDATE ".$type."
					 SET ".$update_values.", 							 	 
					 	 last_updated_timestamp = now()
					 WHERE 	id = '".$id."'";
			
			return $this->_db->query(DATABASE::UPDATE, $sql, False);
		
		} else{
			return FALSE;
		}
	}
	
	/** 
		@function		:	get_user
		@description	:	give user details in return based on id.
	*/
	public function get_user_by_id($type, $id) {
		if(isset($type)){// type will be admin_user and dharma_user
			$sql = "	SELECT *
						FROM ".$type."
					 	WHERE 	id = '".$id."'";
			return $this->_db->query(Database::SELECT, $sql, False)->as_array();
		} else{
			return FALSE;
		}	
	}
	
	/** 
		@function		:	send_email();
		@description	:	send new password to the user via email using Gmail SMTP server.
	*/
public function send_email($email_data, $bcc="",$pdf_created="") {
	try {
		include_once(DOCUMENT_ROOT."/application/classes/vendors/mailer/class.phpmailer.php");
		$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
		$mail->IsSMTP(); // telling the class to use SMTP
		try {
			//$mail->SMTPDebug  = 2;                     // enables SMTP debug information (for testing)
			$mail->SMTPAuth   = true;                  // enable SMTP authentication
			$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
			$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
			$mail->Port       = 465;                   // set the SMTP port for the GMAIL server
			$mail->Username = "aec.acclivity@gmail.com"; // SMTP account username
			$mail->Password = "stclares";         // GMAIL password
			if(isset($email_data['fromemail']) && isset($email_data['from'])) {
				$mail->From 	  = $email_data['fromemail'];
				$mail->FromName   = $email_data['from']; // Name to appear once the email is sent
			}else {
				$mail->From 		= "timetracker@acclivitysoftware.com";
				$mail->FromName		= 'AccountEdge Cloud Admin'; // Name to appear once the email is sent
			}
			if(isset($bcc) && $bcc == 1) {	
				//$mail->AddBCC("pravhr@gmail.com");  // email address of recipient
			}
			if(isset($email_data['cc']) && !empty($email_data['cc'])) {
				$mail->AddCC($email_data['cc']);
			}
			if(isset($email_data['replyto']) && !empty($email_data['replyto'])){
				$mail->AddReplyTo($email_data['replyto'], $email_data['reply_to_name']);
			}
			$mail->Subject    = $email_data['subject']; // Email's subject
			$mail->Body    	  = $email_data['content']; // optional, comment out and test
			$mail->WordWrap   = 50; // set word wrap
			$mail->AddAddress($email_data['to']);  // email address of recipient
		  	$mail->IsHTML(true); // [optional] send as HTML
		  	if($pdf_created != ''){
				$mail->AddAttachment($pdf_created);      // attachment
			} 
		  	$mail->Send();
		} catch (phpmailerException $e) {
  			echo $e->errorMessage(); //Pretty error messages from PHPMailer
		} 
	} catch (Exception $e) {
  		echo $e->getMessage(); //Boring error messages from anything else!
	}
}
	/**
	 * @Method		:	get_slip_number
	 * @Description	:	To get the slip number based on the last inserted id.
	 * @Params		:	int
	 * @return		:	string
	 */
	public function get_slip_number($last_id)
	{
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$slip_id		=	str_pad((int) $last_id,5,"0",STR_PAD_LEFT);
			$slip_number 	=	'ADM'.$slip_id;
			$pad_digit		=	$this->verify_slip_number($slip_number);
			if($pad_digit>0) {
				$slip_number 	=	strtoupper(substr($emp_fname,0,1).substr($emp_lname,0,1)).$pad_digit.$slip_id;
			}
		}else{
			$slip_id		=	str_pad((int) $last_id,6,"0",STR_PAD_LEFT);
			$slip_number 	=	strtoupper(substr($_SESSION['employee_name'],0,1).substr($_SESSION['employee_lastname'],0,1)).$slip_id;
			$pad_digit		=	$this->verify_slip_number($slip_number);
			if($pad_digit>0) {
				$slip_number 	=	strtoupper(substr($_SESSION['employee_name'],0,1).substr($_SESSION['employee_lastname'],0,1)).$pad_digit.$slip_id;
			}	
		}
		return $slip_number;
	}

	/**
	 * 
	 * @desc : Function to define dropbox constant values, dropbox root folder and activity slip folder
	 * @param $account  : array containing dropbox details 
	 */
	public function define_dropbox_constants($account)
	{
	//	$path	= 	str_replace("%2F", "/", rawurlencode($account['device_name'])); // encode the url
		$path	=	$account['device_name'];
		$obj_dropbox	=	new Model_Dropbox;
			
		try {
			if(!defined('DROPBOXFOLDERPATH')) {	
				if($obj_dropbox->apps_folder_exists($path,'/Apps/AccountEdge/')) { // check if user follow new dropbox path. Apps/AccounEdge/device
					define('DROPBOXFOLDERPATH', "/Apps/AccountEdge/".$path."/");
					if(isset($_SESSION['new_activation_status'])){
						unset($_SESSION['new_activation_status']);
					}
				} else if($obj_dropbox->apps_folder_exists($path,'/AccountEdge/')){
					define('DROPBOXFOLDERPATH', "/AccountEdge/".$path."/");
					if(isset($_SESSION['new_activation_status'])){
						unset($_SESSION['new_activation_status']);
					}
				} else {
					return false;
				}			
			} //else{
				//echo 'pollo';die;
			//}
		} catch(Exception $e) {
			//die('hello');//$e->getMessage());
			return false;		
		}
		try {
			if(!defined('DROPBOX_ACTIVITY_FOLDERPATH') && DROPBOX_ACTIVITY_FOLDERPATH == "") { 
				define('DROPBOX_ACTIVITY_FOLDERPATH', DROPBOXFOLDERPATH.DROPBOX_ACTIVITY_FOLDER);
			}
		} catch(Exception $e) { 
				define('DROPBOX_ACTIVITY_FOLDERPATH', DROPBOXFOLDERPATH.DROPBOX_ACTIVITY_FOLDER);
		}
		
		$_SESSION['dropbox_device_name']	=	DROPBOX_ACTIVITY_FOLDERPATH;
		$_SESSION['dropbox_folder_path']	=	DROPBOXFOLDERPATH;
		return true;
	}
	
	
	/**
	 * 
	 * @desc : Function to define dropbox constant values, dropbox root folder and activity slip folder
	 * @param $account  : array containing dropbox details 
	 */
	public function define_dropbox_constants_old($account)
	{
		$path	 = str_replace("%2F", "/", rawurlencode($account['device_name'])); // encode the url
		$obj_dropbox	=	new Model_Dropbox;
		
		try {
			if(!defined(DROPBOXFOLDERPATH) && DROPBOXFOLDERPATH == "") {
				if($obj_dropbox->apps_folder_exists($path,'/Apps/AccountEdge/')) { // check if user follow new dropbox path. Apps/AccounEdge/device
					define('DROPBOXFOLDERPATH', "/Apps/AccountEdge/".$path."/");
				} 
				if($obj_dropbox->apps_folder_exists($path,'/AccountEdge/')){
					define('DROPBOXFOLDERPATH', "/AccountEdge/".$path."/");
				}
			}
		} catch(Exception $e) {
			if($obj_dropbox->apps_folder_exists($path,'/Apps/AccountEdge/')) { // check if user follow new dropbox path. Apps/AccounEdge/device
				define('DROPBOXFOLDERPATH', "/Apps/AccountEdge/".$path."/");
			} else{
				define('DROPBOXFOLDERPATH', "AccountEdge/".$path."/");
			}
		}
		try {
			if(!defined(DROPBOX_ACTIVITY_FOLDERPATH) && DROPBOX_ACTIVITY_FOLDERPATH == "") { 
				define('DROPBOX_ACTIVITY_FOLDERPATH', DROPBOXFOLDERPATH.DROPBOX_ACTIVITY_FOLDER);
			}
		} catch(Exception $e) { 
			define('DROPBOX_ACTIVITY_FOLDERPATH', DROPBOXFOLDERPATH.DROPBOX_ACTIVITY_FOLDER);
		}
		
		$_SESSION['dropbox_device_name']	=	DROPBOX_ACTIVITY_FOLDERPATH;
		$_SESSION['dropbox_folder_path']	=	DROPBOXFOLDERPATH;
		return true;
	}
	
	/**
	 * Sort customer/activity popup box names (in activity/timesheet)
	 * @param1  $name_array	:	Array containg list of values
	 * @param2  $element	:	element name (customer, activity)
	 */
	private function sort_name_array($name_array, $element)
	{
		$count_name_array	=	count($name_array);
		$sort_array			=	array();
		$name_sort_array	=	array();
		if($element == "customers")
		{
			$sort_field		=	"name";
			$value_field	=	"id";	
		}
		else
		{
			$sort_field		=	"id";
			$value_field	=	"name";
		}
		
		if($element == 'jobs'){
			usort($name_array,array($this,"sorting"));
			$i=0;
			foreach($name_array as $each){
				$name_sort_array[$i][$sort_field]	=	$each['id'];
				$name_sort_array[$i][$value_field]	=	$each['name'];
				$name_sort_array[$i]['customer_id']	=	$each['cust_id'];
				$i++;
			}	
		}else {
			for($i=0;$i<$count_name_array;$i++)
			{
				$sort_array[ucfirst($name_array[$i][$sort_field])]	=	$name_array[$i][$value_field];
			}
			ksort($sort_array);
			$i=0;
			foreach($sort_array as $key=>$val)
			{
				$name_sort_array[$i][$sort_field]	=	$key;
				$name_sort_array[$i][$value_field]	=	$val;
				$i++;
			}
		}
		return $name_sort_array;
	}
	
	private static function sorting($a,$b){
		return $a["id"] - $b["id"];
	}

	/**
	 * @Method		:	Verify_slip_number
	 * @Description	:	Function to verify slip number
	 * @Params		:	int
	 * @return		:	int		
	 */
	public function verify_slip_number($slipnumber,$emp_id='')
	{
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
			return 0;
			$sql	=	"SELECT count(RecordID) as total
						 FROM activity_slip_lists
						 WHERE". 
						 //EmployeeRecordID = '".$emp_id."'
						 "AND company_id = '".$_SESSION['company_id']."'
						 AND is_admin = '".$_SESSION['admin_user']."'
						 AND SlipIDNumber = '".addslashes($slipnumber)."'";
		} else{
			$sql	=	"SELECT count(RecordID) as total
					 	FROM activity_slip_lists
					 	WHERE EmployeeRecordID = '".$_SESSION['employee_id']."'
					 	AND company_id = '".$_SESSION['company_id']."'
					 	AND SlipIDNumber = '".addslashes($slipnumber)."'";	
		}
		
		$result	= $this->_db->query(Database::SELECT, $sql, False)->as_array();
		return $result[0]['total'];
	}
	
	/**
	 * 
	 */
	public function update_aed_files($key, $content) {
		$taxes_m 	= new Model_Taxes;
		$emp_m 		= new Model_Employee;
		$vendor_m 	= new Model_Vendor;
		$customer_m	= new Model_Customerandjobs;
		
		if(empty($content)) return true;
		switch($key) {
			case 'Preference': $this->update_company($content);
					break;
			case 'Employees': $emp_m->update_employees($content);
					break;
			case 'Vendors' : $vendor_m->update_vendors($content);
					break;		 
			case 'Activities': $this->update_activities($content);
					break;
			case 'Customers' : $customer_m->update_customers($content);
					break;
			case 'Jobs'		 : $this->update_jobs($content);
					break;
			case 'Items'	 : $this->update_items($content);
					break;
			case 'Accounts'	 : $this->update_accounts($content);
					break;
			case 'Taxes'	 : $taxes_m->update_taxes($content);
					break;
			case 'ConsolidatedTaxes'	: 	$taxes_m->update_consolidated_taxes($content);
					break;
			case 'SalesAndPurchase' 	:	$this->update_sales_and_purchase_info($content);
					break;
			case 'CustomListAndFieldNames' :$this->update_custom_list_names($content);
					break;
			case 'CustomLists' : 			$this->update_custom_lists($content);
					break;
		}
		require_once Kohana::find_file('classes', 'library/Versioning');
		$version_lib 	= 	new Versioning;
		if(isset($_SESSION['company_id']) && !empty($_SESSION['company_id'])) { 
			$group = $version_lib->set_group_to_user($_SESSION['company_id']);
			if(isset($_SESSION['group']) && !empty($_SESSION['group'])) {
				unset($_SESSION['group']);
				$_SESSION['group'] = $group;
			}else{
				$_SESSION['group'] = $group;
			}
		}
		return true;
	}
	
	/**
	 * 
	 * Function to update company from preference JSON file
	 *
	 * @param array $content
	 */
	private function update_company($content) {
		if(isset($content['Build']) && !empty($content['Build'])) {  
			$arr_data['aed_build']	=	$content['Build'];
		}
		if(isset($content['CompanyName']) && !empty($content['CompanyName'])) {  
			$arr_data['name']	=	$content['CompanyName'];
		}
		if(isset($content['Country']) && !empty($content['Country'])) {
			switch($content['Country']){
				case 'US':
					$arr_data['country']			=	1;
					$_SESSION['CONSTANT_MSG_FILE'] 	=	'english-usa';
					// default message file will be USA
					break;
				case 'GB': // GB is for UK
					$arr_data['country']			=	2;
					$_SESSION['CONSTANT_MSG_FILE']	=	'english-uk';
					 
					break;
				case 'AU':
					$arr_data['country']			=	3;
					$_SESSION['CONSTANT_MSG_FILE']	=	'english-usa';
					
					break;
				case 'CA':
					$arr_data['country']			=	4;
					$_SESSION['CONSTANT_MSG_FILE']	=	'english-usa';
					
					break;
				case 'NZ':
					$arr_data['country']			=	5;
					$_SESSION['CONSTANT_MSG_FILE']	=	'english-usa';
					
					break;
				default:
					$arr_data['country']			=	1;	// default is US.
					$_SESSION['CONSTANT_MSG_FILE']	=	'english-usa';
			}
			// set session variable
			$_SESSION['country']			=	$arr_data['country'];
		}
		
		if(isset($content['DefaultCustomerFreightTaxCodeRecordID'])){

			$arr_data['customer_freight_tax_code_record_id']		=	$content['DefaultCustomerFreightTaxCodeRecordID'];
		} else {
			$arr_data['customer_freight_tax_code_record_id']        =	0;
		}
        if(isset($content['DefaultCustomerTaxCodeRecordID'])){
			$arr_data['customer_tax_code_record_id']	=	$content['DefaultCustomerTaxCodeRecordID'];
		} else {
			$arr_data['customer_tax_code_record_id']	=	0;
		}
		if(isset($content['DefaultUseCustomerTaxCode'])&& $content['DefaultUseCustomerTaxCode'] == true){
			$arr_data['use_customer_tax_code']			=	1;
		} else{
			$arr_data['use_customer_tax_code']			=	0;
		}
		if(isset($content['CurrencySymbol'])){
			$arr_data['currency_symbol']				=	$content['CurrencySymbol'];
		} else {
			$arr_data['currency_symbol']				=	'$';
		}
		if(isset($content['UndepositedFundsAccount'])){
			$arr_data['deposit_account']				=	$content['UndepositedFundsAccount'];
		} else {
			$arr_data['deposit_account']				=	'';
		}
		if(isset($content['Build'])){
			$arr_data['build']				=	$content['Build'];
		} else {
			$arr_data['build']				=	'';
		}
		
		$result = DB::update('company')->set($arr_data)->where('id', '=', $_SESSION['company_id'])->execute();
	}
	/**
	 * 
	 * Function to insert/update dropbox activity json files
	 *
	 * @param unknown_type $contens
	 */
	private function update_activities($contens) {
		if(count($contens)>0){
		foreach($contens as $c) {
			$is_non_chargable	=	(!empty($c['IsActivityNonChargeable']) && ($c['IsActivityNonChargeable'] == "1" || $c['IsActivityNonChargeable'] == "true" || $c['IsActivityNonChargeable'] == true))?"1":"0";
			$is_non_hourly		=	(!empty($c['IsActivityNonHourly']) && ($c['IsActivityNonHourly'] == "1" || $c['IsActivityNonHourly'] == "true" || $c['IsActivityNonHourly'] == true))?"1":"0";
			$is_item_inactive	=	(!empty($c['IsItemInactive']) && ($c['IsItemInactive'] == "1" || $c['IsItemInactive'] == "true" || $c['IsItemInactive'] == true))?"1":"0";
			$activity_name		=	empty($c['ActivityName'])?"":$c['ActivityName'];
			$activity_id		=	empty($c['ActivityID'])?"":$c['ActivityID'];
			$description		=	empty($c['Description'])?"":$c['Description'];
			$income_account		=	empty($c['IncomeAccount'])?"":$c['IncomeAccount'];
			$unit_of_measure	=	empty($c['UnitOfMeasure'])?"":$c['UnitOfMeasure'];
			$which_rate_to_use	=	empty($c['WhichRateToUse'])?"":$c['WhichRateToUse'];
			$activity_rate		=	empty($c['ActivityRate'])?"":$c['ActivityRate'];
			$use_description_on_sales  =  empty($c['UseDescriptionOnSales'])?"":$c['UseDescriptionOnSales'];
			$tax_code_record_id		=	isset($c['TaxCodeRecordID'])?$c['TaxCodeRecordID']:"0";
			$tax_when_sold_us		=	isset($c[' 	tax_when_sold_us'])?$c['tax_when_sold_us']:"0";
			
			if($this->activity_exists($activity_id, $_SESSION['company_id'])) {
				$sql	=	"UPDATE activities
							 SET activity_name 			= '".$activity_name."',
							 	 activity_rate			= '".$activity_rate."',	
							 	 description 			= '".$description."',
							 	 income_account 		= '".$income_account."',
							 	 is_non_chargable 		= '".$is_non_chargable."',
							 	 is_non_hourly 			= '".$is_non_hourly."',
							 	 is_item_inactive 		= '".$is_item_inactive."',
							 	 unit_of_measure 		= '".$unit_of_measure."',
							 	 which_rate_to_use 		= '".$which_rate_to_use."',
							 	 use_description_on_sales = '".$use_description_on_sales."',
							 	 TaxCodeRecordID		= '".$tax_code_record_id."',
							 	 tax_when_sold_us		= '".$tax_when_sold_us."',
							 	 last_updated_date = now()
							 WHERE activity_id = '".$activity_id."'
							 AND company_id = '".$_SESSION['company_id']."' 
							 ";
			} else {
				$sql	=	"INSERT INTO activities 
							 (company_id, activity_id, activity_name, activity_rate, description, income_account, is_non_chargable, is_non_hourly, 
							  is_item_inactive, unit_of_measure, which_rate_to_use, use_description_on_sales, TaxCodeRecordID, tax_when_sold_us, created_date, last_updated_date)
							 VALUES (
								'".$_SESSION['company_id']."',
								'".$activity_id."',
								'".$activity_name."',
								'".$activity_rate."',
								'".$description."',
								'".$income_account."',
								'".$is_non_chargable."',
								'".$is_non_hourly."',
								'".$is_item_inactive."',
								'".$unit_of_measure."',
								'".$which_rate_to_use."',
								'".$use_description_on_sales."',
								'".$tax_code_record_id."',
								'".$tax_when_sold_us."',
								now(),
								now()
							 )";
			}
			$this->_db->query(Database::INSERT, $sql, False);
		}}
		return true;
	}
	
	/**
	 * Function to check if the record exitst of the given record id.
	 * Description: check if record exists from given record_id.
	 * created: 02-07-2013
	 * @param 	string $module.
	 * 			int $record_id
		 * 		int $company_id    
	 * 			return synced_status value
	 */
	 private function check_record( $module, $record_id, $company_id){
		switch($module)
		{
			case "customer":
							$module="customers";
							$where_clause= "record_id=".$record_id." AND company_id = '".$company_id."'";  
							break;
			default:
					break;
		}
		$sql_query = "SELECT  synced_status
				FROM ".$module."
				WHERE ".$where_clause;
				
	 	$res = $this->_db->query(Database::SELECT, $sql_query, False)->as_array();
		
		if(isset($res)&&!empty($res))
			return $res[0];
		else 
			return FALSE;
	 }
	 
	 /**
	 * Function to get company-id from user id 
	 * 
	 * @param 	$user id .
	 * 			return company_id
	 */
	 private function get_company_id($user_id){
	 	if(isset($user_id)){
	 		$sql 	=	DB::SELECT('company_id')->FROM('admin_users')->WHERE('id', '=', $user_id)->execute()->as_array();
			
			return $sql[0]['company_id'];
		}else {
			return FALSE;
				
		}		
	 }
	
	/**
	 * 
	 * Function to insert/update dropbox jobs json files
	 *
	 * @param unknown_type $contens
	 */
	private function update_jobs($contens) {
		if(count($contens)>0){
		foreach($contens as $c) {
			$is_header_job		=	(isset($c['IsHeaderJob']) && ($c['IsHeaderJob'] == "1" || $c['IsHeaderJob'] == "true" || $c['IsHeaderJob'] == true))?"1":"0";
			$is_job_inactive	=	(isset($c['is_job_inactive']) && ($c['is_job_inactive'] == "1" || $c['is_job_inactive'] == "true" || $c['is_job_inactive'] == true))?"1":"0";
			$contact			=	empty($c['Contact'])?"":addslashes($c['Contact']);
			$description		=	empty($c['Description'])?"":addslashes($c['Description']);
			$job_name			=	empty($c['JobName'])?"":addslashes($c['JobName']);
			$linked_customer_id	=	empty($c['LinkedCustomerRecordID'])?"":addslashes($c['LinkedCustomerRecordID']);
			$percent_complete	=	empty($c['PercentComplete'])?"":addslashes($c['PercentComplete']);
			$job_number			=	empty($c['JobNumber'])?"":addslashes($c['JobNumber']);
			
			$job_record_id		=	empty($c['JobRecordID'])?"":addslashes($c['JobRecordID']);
			$sub_job_of			=	empty($c['SubJobOf'])?"":addslashes($c['SubJobOf']);
			$track_reimbursable	=	empty($c['TrackReimbursables'])?"":addslashes($c['TrackReimbursables']);
			$start_date			=	empty($c['StartDate'])?"":($c['StartDate']['Year'].'-'.$c['StartDate']['Month'].'-'.$c['StartDate']['Day']);
			$finish_date		=	empty($c['FinishDate'])?"":($c['FinishDate']['Year'].'-'.$c['FinishDate']['Month'].'-'.$c['FinishDate']['Day']);
			$manager			=	empty($c['Manager'])?"":addslashes($c['Manager']);
			$linked_customer	=	empty($c['LinkedCustomer'])?"":addslashes($c['LinkedCustomer']);
			
			if($this->job_exists($job_record_id, $_SESSION['company_id'])) {
				$sql	=	"UPDATE jobs
							 SET contact = '".addslashes($contact)."',
							 	 description = '".addslashes($description)."',
							 	 is_header_job = '".$is_header_job."',
							 	 is_job_inactive = '".$is_job_inactive."',
							 	 job_name = '".addslashes($job_name)."',
							 	 record_id = '".$job_record_id."',
							 	 job_number = '".$job_number."',
							 	 
							 	 sub_job_of = '".$sub_job_of."',
							 	 start_date = '".$start_date."',
							 	 finish_date = '".$finish_date."',
							 	 manager = '".$manager."',
							 	 track_reimbursables = '".$track_reimbursable."',
							 	 linked_customer = '".$linked_customer."',
							 	 
							 	 linked_customer_record_id = '".$linked_customer_id."',
							 	 percent_complete = '".$percent_complete."',
							 	 updated_date = now()
							 WHERE record_id = '".$job_record_id."'
							 AND company_id = '".$_SESSION['company_id']."' 
							 ";
			} else {
				$sql	=	"INSERT INTO jobs 
							 (company_id, contact, description, is_header_job, is_job_inactive, record_id, job_name, job_number,
							  sub_job_of, start_date, finish_date, manager, track_reimbursables, linked_customer,
							  linked_customer_record_id, percent_complete, created_date, updated_date, status)
							 VALUES (
								'".$_SESSION['company_id']."',
								'".$contact."',
								'".$description."',
								'".$is_header_job."',
								'".$is_job_inactive."',
								'".$job_record_id."',
								'".$job_name."',
								'".$job_number."',
								'".$sub_job_of."',
								'".$start_date."',
								'".$finish_date."',
								'".$manager."',
								'".$track_reimbursable."',
								'".$linked_customer."',
								'".$linked_customer_id."',
								'".$percent_complete."',
								now(),
								now(),
								'1'
							 )";
			}
			
			$this->_db->query(Database::INSERT, $sql, False);
		}}
		return true;
	}
	
	/**
	 * Function to check activity already present or not
	 *
	 * @param unknown_type $activity_id
	 * @param unknown_type $company_id
	 * @return unknown
	 */
	private function activity_exists($activity_id, $company_id) {
		$sql	=	"SELECT id
					 FROM activities
					 WHERE activity_id = '".addslashes($activity_id)."'
					 AND company_id = '".addslashes($company_id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return true;
	}
	
	/**
	 * Function to check job already present or not
	 *
	 * @param unknown_type $job_id
	 * @param unknown_type $company_id
	 * @return unknown
	 */
	private function job_exists($job_id, $company_id) {
		$sql	=	"SELECT id
					 FROM jobs
					 WHERE record_id = '".addslashes($job_id)."'
					 AND company_id = '".addslashes($company_id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return true;
	}
	
	/**
	 * Function to update all the items
	 *
	 * @param unknown_type $content
	 */
	private function update_items($contents) {
		if(count($contents)>0){
		    foreach($contents as $c) {
			    $item_name				=	empty($c['ItemName'])?"":$c['ItemName'];
				$item_number			=	empty($c['ItemNumber'])?"":$c['ItemNumber'];
				$price					=	empty($c['SellingPrice'])?"":$c['SellingPrice'];
				$is_description_used	= 	empty($c['IsDescriptionUsedOnSalesAndPurchases'])?$c['IsDescriptionUsedOnSalesAndPurchases'] ? "1" : "0":"0";
				$item_description		=	empty($c['ItemDescription'])?"":$c['ItemDescription'];
				//new field
				$is_selling_incl		=	isset($c['IsSellingPriceInclusive'])?$c['IsSellingPriceInclusive']?"1":"0":"0";
				$tax_when_sold_us		=	empty($c['TaxWhenSold'])?'0':($c['TaxWhenSold']=='true')?'1':'0';
				$tax_when_sold_non_us	=	empty($c['TaxCodeWhenSoldRecordID'])?"":$c['TaxCodeWhenSoldRecordID'];
				$income_account			=	empty($c['IncomeAccount'])?"":$c['IncomeAccount'];
				$is_item_sold			=	empty($c['IsItemSold'])?'0':($c['IsItemSold']=='true')?'1':'0';
				
				if($this->item_exists($item_number, $_SESSION['company_id'])) {
					$sql	=	"UPDATE items
								 SET item_name = '".addslashes($item_name)."',
									 selling_price = '".addslashes($price)."',
									 is_description_used = '".addslashes($is_description_used)."',
									 item_description = '".addslashes($item_description)."',
									 is_item_sold	  =	'".addslashes($is_item_sold)."',
									 is_selling_incl=	'".addslashes($is_selling_incl)."',
									 tax_when_sold_us	=	'".addslashes($tax_when_sold_us)."',
									 tax_when_sold_non_us=	'".addslashes($tax_when_sold_non_us)."',
									 income_account	=	'".addslashes($income_account)."',
									 last_updated_date = now()
								 WHERE company_id = '".$_SESSION['company_id']."'
								 AND item_number = '".$item_number."'
								 ";
				} else {					 
					$sql	=	"INSERT INTO items
								 (company_id, 
								 	item_number, 
								 	item_name, 
								 	selling_price,
								 	is_description_used,
								 	item_description, 
								 	is_item_sold,
								 	is_selling_incl, 
								 	tax_when_sold_us, 
								 	tax_when_sold_non_us, 
								 	income_account, 
								 	created_date, 
								 	last_updated_date, 
								 	status)
								 VALUES (
								 	'".addslashes($_SESSION['company_id'])."',
								 	'".addslashes($item_number)."',
								 	'".addslashes($item_name)."',
								 	'".addslashes($price)."',
								 	'".addslashes($is_description_used)."',
								 	'".addslashes($item_description)."',
								 	'".addslashes($is_item_sold)."',
								 	'".addslashes($is_selling_incl)."',
								 	'".addslashes($tax_when_sold_us)."',
								 	'".addslashes($tax_when_sold_non_us)."',
								 	'".addslashes($income_account)."',
								 	now(),
								 	now(),
								 	'1'
								 )
								 ";
				}
				$this->_db->query(Database::INSERT, $sql, False);
			}
		}
	}
	
	/**
	 * Function to update all the items
	 *
	 * @param unknown_type $content
	 */
	private function update_accounts($contents) {
		if(count($contents)>0){
			foreach($contents as $c) {
				$account_name			=	empty($c['AccountName'])?"":$c['AccountName'];
				$account_number			=	empty($c['AccountNumber'])?"":$c['AccountNumber'];
				$account_description	=	empty($c['Description'])?"":$c['Description'];
				$type					=	empty($c['AccountType'])?"":$c['AccountType'];
				$balance				=	empty($c['Balance'])?"":$c['Balance'];
				$currency_code			=	empty($c['CurrencyCode'])?"":$c['CurrencyCode'];
				$tax_code_record_id		=	isset($c['TaxCodeRecordID'])?$c['TaxCodeRecordID']:"0";
				
				if($this->account_exists($account_number, $_SESSION['company_id'])) {
					$sql	=	"UPDATE accounts
								 SET account_name 			= '".addslashes($account_name)."',
								 	 account_description 	= '".addslashes($account_description)."',
									 account_type 			= '".addslashes($type)."',
									 balance				= '".addslashes($balance)."',
									 currency_code 			= '".addslashes($currency_code)."',
									 TaxCodeRecordID		= '".addslashes($tax_code_record_id)."',
									 last_updated_date 		= now()
								 WHERE company_id 			= '".$_SESSION['company_id']."'
								 AND account_number 		= '".$account_number."'
								 ";
				} else {
					$sql	=	"INSERT INTO accounts
								 (company_id, account_name, account_number, account_description, account_type, balance, currency_code,TaxCodeRecordID, created_date, last_updated_date, status)
								 VALUES (
								 	'".addslashes($_SESSION['company_id'])."',
								 	'".addslashes($account_name)."',
								 	'".addslashes($account_number)."',
								 	'".addslashes($account_description)."',
								 	'".addslashes($type)."',
								 	'".addslashes($balance)."',
								 	'".addslashes($currency_code)."',
								 	'".addslashes($tax_code_record_id)."',
								 	now(),
								 	now(),
								 	'1'
								 )
								 ";
				}
				$this->_db->query(Database::INSERT, $sql, False);
			}
		}
	}
	
	/**
	 * Function to update all sales and purchase information
	 *
	 * @param unknown_type $content
	*/
	private function update_sales_and_purchase_info($contents) {
		if(count($contents)>0){
		foreach($contents as $c) {
			$info_name		=	empty($c['InformationName'])?"":$c['InformationName'];
			$info_type		=	empty($c['InformationType'])?"":$c['InformationType'];
			$payment_type	=	empty($c['PaymentMethodType'])?"":$c['PaymentMethodType'];
			if($this->sale_info_exists($info_name, $_SESSION['company_id'])) {
				$sql	=	"UPDATE sales_and_purchase
							 SET information_type 	= '".addslashes($info_type)."',
								 payment_type 	= '".addslashes($payment_type)."',
								 last_updated_date = now()
							 WHERE company_id 		= '".addslashes($_SESSION['company_id'])."'
							 AND information_name 	= '".addslashes($info_name)."'
							 ";
			} else {
				$sql	=	"INSERT INTO sales_and_purchase
							 (company_id, information_name, information_type, payment_type, created_date, last_updated_date, status)
							 VALUES (
							 	'".addslashes($_SESSION['company_id'])."',
							 	'".addslashes($info_name)."',
							 	'".addslashes($info_type)."',
							 	'".addslashes($payment_type)."',
							 	now(),
							 	now(),
							 	'1'
							 )
							 ";
			}
			$this->_db->query(Database::INSERT, $sql, False);
		}}
	}
	
	// Function to update custom list
	private function update_custom_list_names($contents) {
		if(count($contents)>0){
		foreach($contents as $c) {
			$field1		=	empty($c['NameOfCustomerCustomField1'])?"":$c['NameOfCustomerCustomField1'];
			$field2		=	empty($c['NameOfCustomerCustomField2'])?"":$c['NameOfCustomerCustomField2'];
			$field3		=	empty($c['NameOfCustomerCustomField3'])?"":$c['NameOfCustomerCustomField3'];
			$list1		=	empty($c['NameOfCustomerCustomList1'])?"":$c['NameOfCustomerCustomList1'];
			$list2		=	empty($c['NameOfCustomerCustomList2'])?"":$c['NameOfCustomerCustomList2'];
			$list3		=	empty($c['NameOfCustomerCustomList3'])?"":$c['NameOfCustomerCustomList3'];
			
			if($this->custom_name_info_exists($_SESSION['company_id'])) {
				$sql	=	"UPDATE custom_list_fields
							 SET custom_field1 	 	= '".addslashes($field1)."',
								 custom_field2 	 	= '".addslashes($field2)."',
								 custom_field3 	 	= '".addslashes($field3)."',
								 custom_list1 	 	= '".addslashes($list1)."',
								 custom_list2 	 	= '".addslashes($list2)."',
								 custom_list3 	 	= '".addslashes($list3)."'
							 WHERE company_id 		= '".$_SESSION['company_id']."'
							 ";
			} else {
				$sql	=	"INSERT INTO custom_list_fields
							 (company_id, custom_field1, custom_field2, custom_field3, custom_list1, custom_list2, custom_list3)
							 VALUES (
							 	'".addslashes($_SESSION['company_id'])."',
							 	'".addslashes($field1)."',
							 	'".addslashes($field2)."',
							 	'".addslashes($field3)."',
							 	'".addslashes($list1)."',
							 	'".addslashes($list2)."',
							 	'".addslashes($list3)."'
							 )
							 ";
			}
			$this->_db->query(Database::INSERT, $sql, False);
		}}
	}
	
    // Function for custom lists items
	private function update_custom_lists($contents) {
		if(count($contents)>0){
			foreach($contents as $c) {
				$CustomListName		=	empty($c['CustomListName'])?"":$c['CustomListName'];
				$ListNumber		=	empty($c['ListNumber'])?"":$c['ListNumber'];
				$ListType		=	empty($c['ListType'])?"":$c['ListType'];
				
				if($this->custom_list_exists($_SESSION['company_id'],$CustomListName)) {
					$sql	=	"UPDATE custom_list_items
								 SET custom_list_name   = '".addslashes($CustomListName)."',
									 list_number 	  = '".addslashes($ListNumber)."',
									 list_type 	 	  = '".addslashes($ListType)."'
								 WHERE company_id 	  = '".$_SESSION['company_id']."'
								 AND custom_list_name 	= '".addslashes($CustomListName)."'
								 ";
					$this->_db->query(Database::INSERT, $sql, False);
				} else {
				    if($ListType == 'C')
				    {
						$sql	=	"INSERT INTO custom_list_items
									 (company_id, custom_list_name, list_number, list_type)
									 VALUES (
									 	'".addslashes($_SESSION['company_id'])."',
									 	'".addslashes($CustomListName)."',
									 	'".addslashes($ListNumber)."',
									 	'".addslashes($ListType)."'
									 )
									 ";
						$this->_db->query(Database::INSERT, $sql, False);
					}
				} 
			}
		}
	} 

	  		
	
	/**
	 * Function to check items already present or not
	 *
	 * @return unknown
	 */
	private function item_exists($item_id, $company_id) {
		$sql	=	"SELECT id
					 FROM items
					 WHERE item_number = '".addslashes($item_id)."'
					 AND company_id = '".addslashes($company_id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return true;
	}
	
	/**
	 * Function to check accounts already present or not
	 *
	 * @return unknown
	 */
	private function account_exists($account_number, $company_id) {
		$sql	=	"SELECT id
					 FROM accounts
					 WHERE account_number = '".addslashes($account_number)."'
					 AND company_id = '".addslashes($company_id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return true;
	}
	
	/**
	 * Function to check sales information already present or not
	 *
	 * @return unknown
	 */
	private function sale_info_exists($sale_info, $company_id) {
		$sql	=	"SELECT id
					 FROM sales_and_purchase
					 WHERE information_name = '".addslashes($sale_info)."'
					 AND company_id = '".addslashes($company_id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return true;
	}
	
	
	
	/**
	 * @Method		:	custom_name_infor_exists
	 * @Description	:	Function to check custom name already exists or not
	 * @return 		:	unknown
	 */
	private function custom_name_info_exists($company_id) {
		$sql	=	"SELECT id
					 FROM custom_list_fields
					 WHERE company_id = '".addslashes($company_id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return true;
	}
	
	 /**
	 * @Method		:	custom_list_exists
	 * @Description	:	Function to check custom list already exists or not	
	 * @return 		:	Unknown
	 */
	private function custom_list_exists($company_id,$CustomListName) {
		$sql	=	"SELECT id
					 FROM custom_list_items
					 WHERE company_id = '".addslashes($company_id)."'
					 AND custom_list_name  = '".addslashes($CustomListName)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return true;
	}
			
}