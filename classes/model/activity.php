<?php defined('SYSPATH') or die('No direct script access.');

/*
 * @File : activity.php
 * @Class : Model_Activity
 * @Author: 
 * @Created: 				27-08-2010
 * @Modified:  
 * 					20-11-2013 - modified get_activities function for consolidated TAX.
 * @Description: Holdes the database operations.
 */
class Model_Activity extends Model
{
	/**
	 * @Access		:	Public
	 * @Method		:	get_activities_by_tt
	 * @Description	:	This method fetches the activities data for time-tracker.
	 * @Params		:	
	 */
	public function get_activities_by_tt($timesheet="")
	{
		if($timesheet == 1) {
			$sql	=	"SELECT activity_id as ActivityID, activity_name as ActivityName, activity_rate as ActivityRate, is_non_hourly as IsActivityNonHourly, unit_of_measure as UnitOfMeasure, which_rate_to_use as WhichRateToUse
						 FROM activities
						 WHERE is_non_hourly = '0'
						 AND company_id = '".$_SESSION['company_id']."' 
						 ORDER BY activity_name ASC
						";
		} else {
			$sql	=	"SELECT activity_id as ActivityID, activity_name as ActivityName, activity_rate as ActivityRate, is_non_hourly as IsActivityNonHourly, unit_of_measure as UnitOfMeasure, which_rate_to_use as WhichRateToUse
						 FROM activities
						 WHERE company_id = '".$_SESSION['company_id']."' 
						 ORDER BY activity_name ASC
						";
		}
		$array['Activities']	=	$this->_db->query(Database::SELECT, $sql, False)->as_array();
		return $array['Activities'];
	}
	
	
	// Function to get activities
	// Its a duplicate function of mentioned above.
	// The above function is not proper we have to use the same field name AS mention as table field name.
	// The above function need to be change in future.  
	public function get_activities($country) {
		
		if(isset($country) && $country==USA){ // for US
			$sql	=	"SELECT id, activity_id, use_description_on_sales, description, activity_name, activity_rate, TaxCodeRecordID, tax_when_sold_us
					 	 FROM activities
						 WHERE company_id = '".$_SESSION['company_id']."' 
						 ORDER BY activity_id ASC";
			$return	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		} else {
			$sql	=	"SELECT a.*, t.percentage, t.tax_code, t.sub_tax_code
						FROM activities AS a
						LEFT JOIN taxes as t ON   a.company_id = t.company_id
						AND a.TaxCodeRecordID = t.tax_record_id
						WHERE a.company_id = '".$_SESSION['company_id']."'
						ORDER BY activity_id ASC ";
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();

			// Processing for consolidated TAX percentages.
			$return 	= 	array();
			foreach($result as $activities){
				if($activities['sub_tax_code']!=''){ // Getting consolidated TAX percentage.
					
					// get the sum of the consolidated TAX percentage.
					$sql = 'SELECT SUM(t.percentage) as t_percentage
							FROM taxes as t
							LEFT JOIN sub_taxes as st ON st.tax_code_internal_id = t.id
							WHERE st.consolidated_taxes_internal_id = (	SELECT id 
																		FROM taxes 
																		WHERE tax_record_id = '.$activities["TaxCodeRecordID"].'
																		AND company_id = '.$_SESSION["company_id"].')';
					
					$percentage		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
					if(isset($percentage)){
						$activities['percentage']=$percentage[0]['t_percentage'];
					}
				} 
				$return[] = $activities;
			}
		}
		return $return;
	}
	
	/*
	 * @Method: get_customers 
	 * @Description: This method fetches the customers from Customer.json.
	 */
	public function get_customers()
	{
		$sql	=	"SELECT company_or_lastname as CompanyOrLastName, billing_rate as BillingRate, firstname as FirstName, record_id as RecordID, is_card_inactive as IsCardInactive
					 FROM customers
					 WHERE company_id = '".$_SESSION['company_id']."' ORDER BY company_or_lastname ASC
					";
		
		$array['Customers']	=	$this->_db->query(Database::SELECT, $sql, False)->as_array();
		return $array['Customers'];
	}
	
	/*
	 * @Method: get_jobs 
	 * @Description: This method fetches the jobs from Jobs.json.
	 */
	public function get_jobs()
	{
		$sql	=	"SELECT job_name as JobName, job_number as JobNumber, linked_customer_record_id as LinkedCustomerRecordID, 
							is_header_job as IsHeaderJob, is_job_inactive as IsJobInactive
					 FROM jobs
					 WHERE company_id = '".$_SESSION['company_id']."' ORDER BY job_number ASC
					";
		$array['Jobs']	=	$this->_db->query(Database::SELECT, $sql, False)->as_array();
		return $array['Jobs'];
	}
	
	/*
	 * @Method: get_payroll 
	 * @Description: This method fetches payroll from Employees.json.
	 */
	public function get_payroll()
	{
		$json_model		=	new Model_json;
		$employee_m 	=	new Model_Employee;
		$vendor_m		= 	new Model_Vendor;
					
		// $employees 					=	isset($_SESSION['Employees'])?$_SESSION['Employees']:null;
		// $json_model->file_content 	=	$employees;
		
		// var_dump($employees);  
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
			$emp_id		=	isset($_SESSION['selected_emp_id'])? $_SESSION['selected_emp_id']:'000';
		}else{
			$emp_id		=	$_SESSION['employee_id'];
		}
		//$search_id	=	$json_model->JSON_Query('*', array('RecordID' => $emp_id));
		$search_id	=	$employee_m->get_employee($emp_id, $_SESSION['company_id']); 
		
		if(empty($search_id))
		{
			// $json_model->file_content 	=	isset($_SESSION['Contracts'])?$_SESSION['Contracts']:null;
			// $search_id					=	$json_model->JSON_Query('*', array('RecordID' => $emp_id));
			$search_id	=	$vendor_m->get_vendor($emp_id, $_SESSION['company_id']);	
		}
		
		if($search_id)
		{
			if($_SESSION['User_type'] == "Contractor")
			{
				return array("Base Hourly");
			}
			else
			{
				if(empty($search_id[0]['hourly_wages'])) // return payroll wage if exists else return null
				{
					return null;
				} 
				else 
				{
					$search_id[0]['hourly_wages'] = explode(',', $search_id[0]['hourly_wages']);
					return $search_id[0]['hourly_wages'];
				}
			}
		}
		else
		{
			return;
		}
	}
	
	/*
	 * @Method: get_id
	 * @Description: this method fetches the id value based on the table name and name string.
	 */
	public function get_id($table, $search_field, $name)
	{
		
		
		if ($table == 'payroll_category')
		{
			return true;
		}
				
		try {
			$json_model	=	new Model_json;
			switch($table) {
				case 'Activities':$json_model->file_content	=	$this->get_activities_by_tt();
						break;
				case 'Customers':$json_model->file_content	=	$this->get_customers();
						break;
				case 'Jobs':$json_model->file_content	=	$this->get_jobs();
						break;
			}
		
			$search_id	=	$json_model->JSON_Query('*', array($search_field => $name));
		} catch(Exception $e) {
				die($e->getMessage());
		}
		return $search_id;		
	}
	
	/*
	 * @Method: get_latest_payroll
	 * @Description: this method fetches latest payroll entered by the user.
	 */
	public function get_latest_payroll($employee_id)
	{
		
		$json_model					=	new Model_json;
		$employees 					=	isset($_SESSION['ActivitySlips'])?$_SESSION['ActivitySlips']:null;
		$json_model->file_content 	=	$employees;
		
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
			$emp_id	=	isset($_SESSION['selected_emp_id'])? $_SESSION['selected_emp_id']:'000';
		}else{
			$emp_id	=	$_SESSION['employee_id'];
		}
		
		$search_id					=	$json_model->JSON_Query('*', array('EmployeeRecordID' => $emp_id));
		$search_id 					=	$json_model->order_by_date($search_id, 'SlipDate',"desc");
		
		if($search_id)
		{
			$search_id 				=	array_merge(array(), $search_id); 
			return isset($search_id[0]['PayrollCategory'])?$search_id[0]['PayrollCategory']:'';
		}
		else
		{
			return; 
		}
	}
		
	
	/*
	 * @Method: insert 
	 * @params: params contains the set of array that is inserted to the activity slip.
	 * @Description: This method insert activity data into database.
	 */
	public function insert($params)
	{
	  	$json_model	=   new Model_json;
		$content 	=	array();
		foreach($params as $data)
		{	
			if	($data['units'] != null AND $data['units'] != '')
			{	
				$row_info	=	$this->same_row_exists($data);
				$sql		=	"INSERT INTO activity_slip_lists
								(ActivityID, CustomerCompanyOrLastName, CustomerRecordID, EmployeeCardID,
								EmployeeCompanyOrLastName, EmployeeFirstName, EmployeeRecordID,
								JobNumber, Notes, Rate, SlipDate, SlipIDNumber, Units, PayrollCategory, 
								Is_non_hourly, company_id, is_admin, created_date, sync_status)
								VALUES (
									'".addslashes(htmlentities($data['activity_id']))."',
									'".addslashes(htmlentities($data['customer_name']))."',
									'".addslashes(htmlentities($data['customer_id']))."',
									'*None',
									'".addslashes(htmlentities($data['employee_lastname']))."',
									'".addslashes(htmlentities($data['employee_name']))."',
									'".addslashes(htmlentities($data['employee_id']))."',
									'".addslashes(htmlentities($data['job_id']))."',
									'".addslashes($data['notes'])."',
									'".addslashes($data['rate'])."',
									'".$data['date']."',
									'".addslashes($data['slip_number'])."',
									'".addslashes($data['units'])."',
									'".addslashes($data['payroll_name'])."',
									'".addslashes($data['is_non_hourly'])."',
									'".$_SESSION['company_id']."',
									'".$_SESSION['admin_user']."',
									now(),
									0
								)";
					$this->_db->query(Database::INSERT, $sql, False);
				
				//}
			}
		}
		
		
		return true;
	
	}
	
	
	private function same_row_exists($data)
	{
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
			$emp_id	=	isset($_SESSION['selected_emp_id'])? $_SESSION['selected_emp_id']:'000';
			$sql	=	"SELECT RecordID
						 FROM activity_slip_lists
						 WHERE ActivityID = '".addslashes(htmlentities($data['activity_id']))."'
						 AND CustomerRecordID = '".addslashes(htmlentities($data['customer_id']))."'
						 AND EmployeeRecordID = '".$emp_id."'
						 AND JobNumber = '".addslashes(htmlentities($data['job_id']))."'
						 AND Notes = '".addslashes($data['notes'])."'
						 AND PayrollCategory = '".addslashes($data['payroll_name'])."'
						 AND company_id = '".$_SESSION['company_id']."'
						 AND SlipDate = '".$data['date']."'
						 AND sync_status = '0'";
		}else{
			$sql	=	"SELECT RecordID
						 FROM activity_slip_lists
						 WHERE ActivityID = '".addslashes(htmlentities($data['activity_id']))."'
						 AND CustomerRecordID = '".addslashes(htmlentities($data['customer_id']))."'
						 AND EmployeeRecordID = '".$_SESSION['employee_id']."'
						 AND JobNumber = '".addslashes(htmlentities($data['job_id']))."'
						 AND Notes = '".addslashes($data['notes'])."'
						 AND PayrollCategory = '".addslashes($data['payroll_name'])."'
						 AND company_id = '".$_SESSION['company_id']."'
						 AND is_admin = '".$_SESSION['admin_user']."'
						 AND SlipDate = '".$data['date']."'
						 AND sync_status = '0'";
		}
		
		$result	= $this->_db->query(Database::SELECT, $sql, False)->as_array();
		if(!empty($result)) {
			$arr_result['status']	=	true;
			$arr_result['id']		=	$result[0]['RecordID'];
		} else {
			$arr_result['status']	=	false;
		}
		return $arr_result;
	}
	
	/*
	 * @Method: update 
	 * @params: params contains the set of array that is updated to the activity slip.
	 * @Description: This method update the selected activity data into database.
	 */
	public function update($params, $timesheet="")
	{
	  	$json_model	=   new Model_json;		
		$content 	=	array();
		foreach($params as $data)
		{	
			if ($data['units'] != null AND $data['units'] != '')
			{
				if($timesheet == 1) {
					$sql	=	"UPDATE activity_slip_lists
								 SET ActivityID = '".addslashes(htmlentities($data['activity_id']))."',
								     CustomerCompanyOrLastName = '".addslashes(htmlentities($data['customer_name']))."',
									 CustomerRecordID = '".addslashes(htmlentities($data['customer_id']))."',
									 EmployeeRecordID= '".addslashes(htmlentities($data['employee_id']))."',
									 JobNumber = '".addslashes(htmlentities($data['job_id']))."',
									 Notes = '".addslashes($data['notes'])."',
									 Rate = '".addslashes($data['rate'])."',
									 SlipDate = '".addslashes($data['date'])."',
									 SlipIDNumber = '".addslashes($data['slip_number'])."',
									 Units = '".addslashes($data['units'])."',
									 PayrollCategory = '".addslashes($data['payroll_name'])."',
									 Is_non_hourly   = '".$data['is_non_hourly']."'
								 WHERE RecordID = '".$data['RecordID']."'";
				} else {
					$sql	=	"UPDATE activity_slip_lists
								 SET ActivityID = '".addslashes(htmlentities($data['activity_id']))."',
								     CustomerCompanyOrLastName = '".addslashes(htmlentities($data['customer_name']))."',
									 CustomerRecordID = '".addslashes(htmlentities($data['customer_id']))."',
									 EmployeeRecordID = '".addslashes(htmlentities($data['employee_id']))."',
									 EmployeeFirstName= '".addslashes(htmlentities($data['employee_name']))."',
									 EmployeeCompanyOrLastName = '".addslashes(htmlentities($data['employee_lastname']))."',
									 JobNumber = '".addslashes(htmlentities($data['job_id']))."',
									 Notes = '".addslashes($data['notes'])."',
									 Rate = '".addslashes($data['rate'])."',
									 SlipDate = '".addslashes($data['date'])."',
									 SlipIDNumber = '".addslashes($data['slip_number'])."',
									 Units = '".addslashes($data['units'])."',
									 PayrollCategory = '".addslashes($data['payroll_name'])."',
									 Is_non_hourly   = '".$data['is_non_hourly']."'
								 WHERE RecordID = '".$data['RecordID']."'";		
					
				}					
				$this->_db->query(Database::INSERT, $sql, False);
				
			}		
		}
		return true;
	}
	
	/**
	 * @desc: Check whether selected slips (in view mode) has been already synced or not. If it synced then it cannot be editable anymore.
	 * @param1: $slip_id
	 * @return : true if synced already else false
	 */
	public function check_slip_synced($slip_id)// rolls: not used
	{
		try{
			$query	=	"SELECT *
						 FROM activities_sync
						 WHERE RecordID = '".addslashes($slip_id)."'
						 AND company_id = '".$_SESSION['company_id']."'
						";
			$result	= $this->_db->query(Database::SELECT, $query, False)->as_array();
			if(empty($result))	return false;
			else return true;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	/**
	 * @desc : Fetch last record id from database for the creation of slip number
	 * @return : last auto id + 1
	 */
	public function get_last_auto_id()
	{
		try{
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
				$emp_id	=	isset($_SESSION['selected_emp_id'])? $_SESSION['selected_emp_id']:'000';
				$query	=	"SELECT MAX(RecordID) as last_id
						 	FROM activity_slip_lists
						 	WHERE EmployeeRecordID = '".$emp_id."'
						 	AND company_id = '".$_SESSION['company_id']."'";
			}else{
				$query	=	"SELECT MAX(RecordID) as last_id
							 FROM activity_slip_lists
							 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."'
							 AND company_id = '".$_SESSION['company_id']."'
							 AND is_admin = '".$_SESSION['admin_user']."'";
			}
			$result	= $this->_db->query(Database::SELECT, $query, False)->as_array();
			if(empty($result))	return 1;
			else return $result[0]['last_id']+1;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	/**
	 * @desc : Fetch last record id from database for the creation of slip number
	 * @return : last auto id + 1
	 */
	public function get_slip_auto_id($emp_id='')
	{
		try{
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
				$sql	=	"SELECT count(RecordID) as last_id
						 	FROM activity_slip_lists
						 	WHERE company_id = '".$_SESSION['company_id']."'
						 	AND is_admin = '".$_SESSION['admin_user']."'";
			}else{
				$sql	=	"SELECT count(RecordID) as last_id
						 	FROM activity_slip_lists
						 	WHERE EmployeeRecordID = '".$_SESSION['employee_id']."'
						 	AND company_id = '".$_SESSION['company_id']."'
						 	";
			}
		
			$result	= $this->_db->query(Database::SELECT, $sql, False)->as_array();
			if(empty($result))	return 0;
			else return $result[0]['last_id'];
		}catch(Exception $e){
				die($e->getMessage());
		}
		
	} 
	
	public function delete_activity($slip_id)
	{	
		try {
			$sql	=	"DELETE FROM activity_slip_lists WHERE RecordID = '".addslashes($slip_id)."'";
			$this->_db->query(Database::DELETE, $sql, False);
			return true;
		} catch(Exception $e) {
			return false;
		}
	}
	
	public function get_activity_last_date($date_start, $date_end)
	{ 
		try{
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
				$sql	=	"SELECT SlipDate
							 FROM activity_slip_lists
							 WHERE created_date BETWEEN '".$date_start."' AND '".$date_end."'
							 AND company_id = '".$_SESSION['company_id']."'
							 AND is_admin = '".$_SESSION['admin_user']."'
							 ORDER BY created_date DESC
							 LIMIT 0,1";
			}else{
				$sql	=	"SELECT SlipDate
							 FROM activity_slip_lists
							 WHERE created_date BETWEEN '".$date_start."' AND '".$date_end."'
							 AND EmployeeRecordID = '".$_SESSION['employee_id']."'
							 AND company_id = '".$_SESSION['company_id']."'
					 		 ORDER BY created_date DESC
							 LIMIT 0,1";
			}
			$result	= $this->_db->query(Database::SELECT, $sql, False)->as_array();
			if(empty($result))	return null;
			else return $result[0]['SlipDate'];
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	public function redundant_record($slip_data)
	{
		try{
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']!=''){
				$emp_id	=	isset($_SESSION['selected_emp_id'])? $_SESSION['selected_emp_id']:'000';
				$sql	=	"SELECT ActivityID
							 FROM activity_slip_lists
							 WHERE SlipIDNumber = '".addslashes($slip_data['slip_number'])."'
							 AND EmployeeRecordID = '".$emp_id."'
							 AND company_id = '".$_SESSION['company_id']."'"
							 ;
			}else{
				$sql	=	"SELECT ActivityID
							 FROM activity_slip_lists
							 WHERE SlipIDNumber = '".addslashes($slip_data['slip_number'])."'
							 AND EmployeeRecordID = '".$_SESSION['employee_id']."'
							 AND company_id = '".$_SESSION['company_id']."'
							 AND is_admin = '".$_SESSION['admin_user']."'"
							 ;
			}
			
			$result	= $this->_db->query(Database::SELECT, $sql, False)->as_array();
			if(empty($result))	return false;
			else return true;
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	/*End of Class*/
}