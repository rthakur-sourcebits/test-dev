<?php defined('SYSPATH') or die('No direct script access.');
/*
 * @Class: Model dharmausers.php
 * @Description: This model file uses for queries on Dharma users table.
 * @Created: 22-11-2013
 * @Modified: 
 * Update : 
 * 			22.11.2013	: Added function customer_job_related
 * 			25.11.2013 	: Added function get_user_info from admin.php
 * 			25.11.2013 	: Added function from admin.php 
 * 			25.11.2013  : Added function delete_user from admin.php 
 * 			25.11.2013 	: Added function get_timetracker_users from admin.php
 * 			25.11.2013 	: Added function get_total_company_users from admin.php
 * 			25.11.2013 	: Added function activate_all_users from admin.php
 * 			25.11.2013 	: Added function deactivate_all_users from admin.php
 * 			25.11.2013 	: Added function change_user_rate_status from admin.php
 * 			25.11.2013 	: Added function change_user_payroll_status from admin.php
 * 			25.11.2013 	: Added function current_password_success from admin.php
 * 			25.11.2013 	: Added function change_user_password from admin.php
 * 			25.11.2013 	: Added function get_user_active_id from admin.php
 * 			25.11.2013 	: Added function update_user_active_id from admin.php
 * 			25.11.2013 	: Added function reset_user_password from admin.php
 * 			25.11.2013 	: Added function get_user_info_by_active_id from admin.php
 * 			25.11.2013 	: Added function get_payroll_flag from admin.php
 * 			25.11.2013 	: Added function get_employees from admin.php
 * 
  */
 
class Model_Dharmausers extends Model
{
	/**
	 * Check customer job relation activated or not
	 * @return unknown
	 */
	public function customer_job_related() {
		try{
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']!=''){
				$emp_id	=	isset($_SESSION['selected_emp_id'])? $_SESSION['selected_emp_id']:'000';
				$sql	=	"SELECT id
							 FROM dharma_users
							 WHERE record_id = '".$emp_id."'
							 AND company_id = '".$_SESSION['company_id']."'
							 AND show_jobs = '1'";		
			}else{
				$sql	=	"SELECT id
							 FROM dharma_users
							 WHERE record_id = '".$_SESSION['employee_id']."'
							 AND company_id = '".$_SESSION['company_id']."'
							 AND show_jobs = '1'";
			}
			
			$result	= $this->_db->query(Database::SELECT, $sql, False)->as_array();
			if(empty($result)) return false;
			else return true;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	/**
	 * Get customer Jobs
	 * @return unknown
	 */
	public function get_show_jobs($emp_id = '0'){
		try{ 
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
				if($emp_id == '0')return 0;
				
				$sql	=	"SELECT show_jobs
							FROM dharma_users
							WHERE record_id= '".$emp_id."'
							AND company_id = '".$_SESSION['company_id']."'	";
						
			} else {
				$sql	=	"SELECT show_jobs
							FROM dharma_users
							WHERE record_id= '".$_SESSION['employee_id']."'
							AND company_id = '".$_SESSION['company_id']."'	";
			}
			
			$result	=	 $this->_db->query(Database::SELECT, $sql, FALSE)->as_array();//echo "<pre>";print_r($result);die;
			//var_dump($result); die;
			if(empty($result)) return 0;
			else return $result[0]['show_jobs'];
		}catch(Exception $e){
			die($e->getMessage());
		}	
	}
	
	/**
	 * @Method      : get_user_info 
	 * @Description : get the user details.
	 */
	public function get_user_info($id, $cid)
	{
		$query		=	"SELECT id, record_id, first_name, last_name, email, AES_DECRYPT(password, '".ENCRYPT_KEY."') as password, type, company_id, display_rate, show_jobs
						 FROM dharma_users
						 WHERE record_id = '".$id."'
						 AND company_id = '".$cid."'";
		
		$query		=	DB::query(Database::SELECT, $query);
		$data		=	$query->execute()->as_array();
		if(empty($data)){
			return FALSE;
		} else {
			return $data;
		}
	}
	
	public function change_in_email($params)
	{
		$sql	=	"SELECT record_id
					 FROM dharma_users
					 WHERE email = '".addslashes($params['Email'])."'
					 AND company_id = '".$_SESSION['company_id']."'
					 AND record_id 	= '".$params['RecordID']."'";
		$query		=	DB::query(Database::SELECT, $sql);
		$data		=	$query->execute()->as_array();
		if(empty($data)) {
			return true;
		} else {
			return false;
		}
	}
	
	/**
	 * @function	:	delete_user
	 * @description	:	Delete user from the tables.
	 */
	public function delete_user($user_id)
	{
		
		/** Delete record from dharma users table first **/
		$query	=	"DELETE 
					 FROM dharma_users 
					 WHERE record_id = '".$user_id."' 
					 AND company_id = '".$_SESSION['company_id']."'";
		$this->_db->query(Database::DELETE, $query, False);
		return true;
	}
	
	public function get_timetracker_users($search_user=false)
	{
		$json		= 	new Model_json;	
		$employee_m	=	new Model_Employee;
		$venfor_m 	=	new Model_Vendor;
			
		if(isset($_POST['search'])) {
			$search_item	=	addslashes($_POST['search']);
			switch($_POST['search_field']) {
				case 'all' :
							$sql	=	"SELECT *
										 FROM dharma_users
										 WHERE company_id = '".$_SESSION['company_id']."'
										 AND (first_name LIKE '%".$search_item."%' OR 
										 	  last_name LIKE '%".$search_item."%' OR 
										 	  email LIKE '%".$search_item."%'
										 	 )";
							break;
				case 'fname' :
							$sql	=	"SELECT *
										 FROM dharma_users
										 WHERE company_id = '".$_SESSION['company_id']."'
										 AND first_name LIKE '%".$search_item."%' 
										 ";
							break;
				case 'lname' :
							$sql	=	"SELECT *
										 FROM dharma_users
										 WHERE company_id = '".$_SESSION['company_id']."'
										 AND last_name LIKE '%".$search_item."%' 
										 ";
							break;
				case 'email':	
							$sql	=	"SELECT *
										 FROM dharma_users
										 WHERE company_id = '".$_SESSION['company_id']."'
										 AND email LIKE '%".$search_item."%' 
										 ";
							break;
			}
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			if($search_user) {
				$json_emp_list			=	$employee_m->get_employee_by_key(array('first_name' =>$search_item, 'company_or_last_name' => $search_item));
				$json_vend_list			=	$venfor_m->get_vendor_by_key(array('first_name' =>$search_item, 'company_or_last_name' => $search_item));
				
			}
		} else {
			$sql	=	"SELECT *
						 FROM dharma_users
						 WHERE company_id = '".$_SESSION['company_id']."'";
		
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			if($search_user) {
				$json_emp_list			=	$employee_m->get_all_employee($_SESSION['company_id']);
				$json_vend_list			=	$venfor_m->get_all_vendor($_SESSION['company_id']);
			}
		}
		
		if(!$search_user) {
			return $result;
		}
		$emp_active_list	=	array();
		foreach($result as $user) {
			$emp_active_list[]	=	$user['record_id'];
		}
		if(!empty($json_emp_list))
		{
			foreach($json_emp_list as $json_list) {
				$arr_json_emp	=	array();
				if(!in_array($json_list["RecordID"], $emp_active_list)) {
					$arr_json_emp["record_id"]	=	$json_list["RecordID"];
					$arr_json_emp["first_name"]	=	empty($json_list["FirstName"])?"":$json_list["FirstName"];
					$arr_json_emp["last_name"]	=	empty($json_list["CompanyOrLastName"])?"":$json_list["CompanyOrLastName"];
					$arr_json_emp["email"]		=	"";
					$arr_json_emp["type"]		=	"Employee";
					array_push($result, $arr_json_emp);
				}
			}
		}
		if(!empty($json_vend_list)) 
		{
			foreach($json_vend_list as $json_vlist) {
				$arr_json_vend	=	array();
				if(!in_array($json_vlist["RecordID"], $emp_active_list)) {
					$arr_json_vend["record_id"]		=	$json_vlist["RecordID"];
					$arr_json_vend["first_name"]	=	empty($json_vlist["FirstName"])?"":$json_vlist["FirstName"];
					$arr_json_vend["last_name"]		=	empty($json_vlist["CompanyOrLastName"])?"":$json_vlist["CompanyOrLastName"];
					$arr_json_vend["email"]			=	"";
					$arr_json_vend["type"]			=	"Vendor";
					array_push($result, $arr_json_vend);
				}
			}
		}
		return $result;
	}

	public function get_total_company_users()
	{
		$sql			=	"SELECT count(id) as total_users 
						 	FROM dharma_users
							WHERE company_id = '".$_SESSION['company_id']."'
							AND status = 1";
		$result_users	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result_users;
	}
	
	public function activate_all_users()
	{
		$employee_m 	=	new Model_Employee;
		$vendor_m	 	=	new Model_Vendor;
		
		$sql			=	"SELECT count(id) as total_users, *
							 FROM dharma_users
							 WHERE company_id = '".$_SESSION['company_id']."'";
		$result_users	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		$user_limit		=	$this->get_plan_user_limit();
		if($result_users[0]['total_users'] > $user_limit[0]['user_limit']) {
			return false;
		}
		$sql			=	"UPDATE dharma_users
							SET status = 1
						 	WHERE company_id = '".$_SESSION['company_id']."'";
		$result_users	=	$this->_db->query(Database::UPDATE, $sql, true);
		
		// Activating respective employee/vendor in employees/vendors table.
		$dharma_users = DB::select()->from('dharma_users')->where('company_id', '=', $_SESSION['company_id'])->execute()->as_array();
		
		$db_key_value['active_status']=1;
		if(isset($dharma_users)){
			foreach($dharma_users as $duser){
				if($duser['type']=="Employee"){
					$employee_m->update_employee_by_key($_SESSION['company_id'], $duser['record_id'], $db_key_value);
				} else if($duser['type']=="Vendor"){
					$vendor_m->update_vendor_by_key($_SESSION['company_id'], $duser['record_id'], $db_key_value);
				} 
			}
		}
		return true;
	}
	
	public function deactivate_all_users()
	{
		$sql	=	"UPDATE dharma_users
					 SET status = 0
					 WHERE company_id = '".$_SESSION['company_id']."'";
		$result	=	$this->_db->query(Database::UPDATE, $sql, true);
		
		// De-Activating respective employee/vendor in employees/vendors table.
		$dharma_users = DB::select()->from('dharma_users')->where('company_id', '=', $_SESSION['company_id'])->execute()->as_array();
		$db_key_value['active_status']=0;
		if(isset($dharma_users)){
			foreach($dharma_users as $duser){
				if($duser['type']=="Employee"){
					$employee_m->update_employee_by_key($_SESSION['company_id'], $duser['record_id'], $db_key_value);
				} else if($duser['type']=="Vendor"){
					$vendor_m->update_vendor_by_key($_SESSION['company_id'], $duser['record_id'], $db_key_value);
				} 
			}
		}
		
		return true;
	}
	
	public function change_user_rate_status($user_id, $current_status)
	{
		if($current_status == 1) {
			$sql	=	"UPDATE dharma_users
						 SET display_rate = 0
						 WHERE id = '".$user_id."'";
		} else {
			$sql	=	"UPDATE dharma_users
						 SET display_rate = 1
						 WHERE id = '".$user_id."'";
		}
		$result	=	$this->_db->query(Database::INSERT, $sql, true);
		return true;
	}
	
	/**
	 * Function to change payroll category
	 *
	 * @param $user_id : user id
	 * @param $current_status : current payroll status for this user
	 * @return true
	 */
	public function change_user_payroll_status($user_id, $current_status)
	{
		if($current_status == 1) {
			$sql	=	"UPDATE dharma_users
						 SET payroll_category = 0
						 WHERE id = '".$user_id."'";
		} else {
			$sql	=	"UPDATE dharma_users
						 SET payroll_category = 1
						 WHERE id = '".$user_id."'";
		}
		$result	=	$this->_db->query(Database::INSERT, $sql, true);
		return true;
	}
	
	/**
	 * Check current password in valid or not
	 *
	 */
	public function current_password_success($password)
	{
		if(isset($_POST["ajax"])) { //editing through ajax by the admin
			$sql	=	"SELECT *
						 FROM dharma_users
						 WHERE password	=	AES_ENCRYPT('".addslashes($_POST['cur_password'])."', '".ENCRYPT_KEY."')
						 AND record_id = '".addslashes($_POST['user_id'])."'
						 AND company_id = '".addslashes($_SESSION['company_id'])."'";
		} else {
			$sql	=	"SELECT *
						 FROM dharma_users
						 WHERE password	=	AES_ENCRYPT('".addslashes($_POST['cur_password'])."', '".ENCRYPT_KEY."')
						 AND active_id = '".addslashes($_POST['activate_id'])."'";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) {
			return false;	
		} else {
			return true;
		}
	}
	
	/**
	 * Function (query) to reset the password
	 *
	 */
	public function change_user_password()
	{
		try {
			if(isset($_POST["ajax"])) { //editing through ajax by the admin
				$sql	=	"UPDATE dharma_users
							 SET password	=	AES_ENCRYPT('".addslashes($_POST['new_password'])."','".ENCRYPT_KEY."')
							 WHERE record_id  = '".addslashes($_POST['user_id'])."'
							 AND company_id = '".addslashes($_SESSION['company_id'])."'";
			} else {
				$sql	=	"UPDATE dharma_users
							 SET password	=	AES_ENCRYPT('".addslashes($_POST['new_password'])."','".ENCRYPT_KEY."')
							 WHERE active_id = '".addslashes($_POST['activate_id'])."'";
			}
			$result	=	$this->_db->query(Database::INSERT, $sql, true);
			return true;
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	/**
	 * Get user active id to store new temp password
	 *
	 */
	public function get_user_active_id($user_id)
	{
		$sql	=	"SELECT active_id
					 FROM dharma_users
					 WHERE record_id = '".addslashes($user_id)."'
					 AND company_id = '".$_SESSION['company_id']."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result) || empty($result[0]['active_id']) || $result[0]['active_id'] == "") {
			$active_id	=	md5(session_id().time().rand());
			$this->update_user_active_id($user_id, $active_id);
			return $active_id;	
		} else {
			return $result[0]['active_id'];
		}
	}
	
	/**
	 * Function update user active id
	 */
	
	public function update_user_active_id($user_id, $active_id)
	{
		try {
			$sql	=	"UPDATE dharma_users
						 SET active_id = '".addslashes($active_id)."'
						 WHERE record_id = '".addslashes($user_id)."'
						 AND company_id = '".$_SESSION['company_id']."'";
			$result	=	$this->_db->query(Database::INSERT, $sql, true);
			return true;
		}
		catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	/**
	 * Function to reset user password
	 *
	 * @param unknown_type $user_id
	 */
	public function reset_user_password($user_id, $temp_password, $active_id)
	{
		try {
			$sql	=	"UPDATE dharma_users
						 SET password = AES_ENCRYPT('".addslashes($temp_password)."','".ENCRYPT_KEY."')
						 WHERE active_id = '".addslashes($active_id)."'";
			$result	=	$this->_db->query(Database::INSERT, $sql, true);
			return true;
		} catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	/**
	 * Get user full information by active id
	 *
	 * @param unknown_type $active_id
	 */
	public function get_user_info_by_active_id($active_id)
	{
		try {
			$sql	=	"SELECT first_name, email
						 FROM dharma_users
						 WHERE active_id = '".addslashes($active_id)."'";
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			if(empty($result)) {
				return false;	
			} else {
				return $result[0];
			}
		}  catch(Exception $e) {
			throw new Exception($e->getMessage());
		}
	}
	
	/**
	* @Function		:	get_payroll_flag;
	* @Description	:	reads the employee.json file and gets the flag value.
	*/
	public function get_payroll_flag()
	{
		try {	
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
				$emp_id	=	isset($_SESSION['selected_emp_id'])?$_SESSION['selected_emp_id']:'000';
				$sql	=	"SELECT payroll_category
							FROM dharma_users
							WHERE 	record_id = '".$emp_id."'
							AND company_id = '".$_SESSION['company_id']."'";
			}else{
				$sql	=	"SELECT payroll_category
							FROM dharma_users
							WHERE 	record_id = '".$_SESSION['employee_id']."'
							AND company_id = '".$_SESSION['company_id']."'";
			}
			$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			if($data[0]['payroll_category'] == 1) {
				return true;
			} else {
				return false;
			}
		} catch(Exception $e) {
			return false;
		}
	}
	
	/**
	 * @Method		:	get_employees
	 * @Description	:	Function to display the list of employees existing in a company in admin perspective.
	 * 					For displaying the user name in creation of ActivitySlip, TimeSheet,..
	 * @Return		:	if success array else NULL
	 */
	public function get_employees(){
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT id, record_id, concat(first_name,' ',last_name)as name
						FROM dharma_users
						WHERE company_id = '".addslashes($_SESSION['company_id'])."' ORDER BY first_name ASC";
			
		} else {
			$sql	=	"SELECT id, record_id, concat(first_name,' ',last_name)as name
						FROM dharma_users
						WHERE company_id = '".addslashes($_SESSION['company_id'])."' 
						AND record_id = '".addslashes($_SESSION['employee_id'])."' ORDER BY first_name ASC";
		}
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)){
			return NULL;
		}else{
			return $data;
		}
	}
	

}