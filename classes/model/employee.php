<?php
defined('SYSPATH') or die('No direct script access.');

/*
 * @File : 			employee.php
 * @Class : 		Model_Eployee
 * @Description: 	Holds EMployee related database operations.
 * @Created Date: 	20-11-2013
 * @Created By: 	Rahul Thakur
 * @Modified:		20-11-2013 - create
 * 					20-11-2013 - Added function update_employees
 * 					20-11-2013 - Added function employee_exists
 * 					21-11-2013 - Added function get_employee  
 * 					21-11-2013 - Added function get_all_employee
 * 					21-11-2013 - Added function get_employee_by_key
 * 					25-11-2013 - Added function update_employee_by_key
 * 
 */
class Model_Employee extends Model
{
	
	/**
	 * Function to check tax already present or not
	 *
	 * @return unknown
	 */
	private function employee_exists($record_id, $company_id) {
		$sql	=	"SELECT id
					 FROM employees
					 WHERE  record_id = '".addslashes($record_id)."'
					 AND company_id = '".addslashes($company_id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) {
			return false;
		}
		else {
			return true;
		}
	}
	 
	/**
	 * Function to update employees data from JSON file. 
	*     
	 *
	 * @param array $content
	 */
	public function update_employees($contents) {
		if(count($contents)>0){
			foreach($contents as $c) {
				
				if(isset($c['RecordID']) && !empty($c['RecordID'])){
					$data['record_id'] 				= $c['RecordID'];
					$data['company_id']			=	$_SESSION['company_id'];
						}
				if(isset($c['FirstName']) && !empty($c['FirstName'])){
					$data['first_name'] 			= $c['FirstName'];
				}
				if(isset($c['CompanyOrLastName']) && !empty($c['CompanyOrLastName'])){
					$data['company_or_last_name']	= $c['CompanyOrLastName'];
				}
				if(isset($c['Addr1Email']) && !empty($c['Addr1Email'])){
					$data['email']					= $c['Addr1Email'];
				}
				if(isset($c['HourlyWageCategories']) && !empty($c['HourlyWageCategories'])){
					$data['hourly_wages']	= 	'';
					$flag = FALSE;
					foreach ($c['HourlyWageCategories'] as $value) { // check this
						if ($flag){
							$data['hourly_wages'] .=  ', ';
						}
						$data['hourly_wages'] .= $value;
						$flag = TRUE;
					}
				}
				if(isset($c['BillingRate']) && !empty($c['BillingRate'])){
					$data['billing_rate']			= $c['BillingRate'];
				}
				if(isset($c['RecordID']) && !empty($c['RecordID'])){
					$data['updated_date'] 			= date('Y-m-d H:i:s');
				}
				if($this->employee_exists($data['record_id'] , $_SESSION['company_id'])) {
					$updated = DB::update('employees')->set($data)	->where('company_id', '=', $_SESSION['company_id'])
																	->where('record_id', '=', $data['record_id'] )->execute();
				} else {
					$columns = 	array_keys($data);
					$values	 =	array_values($data);
					$query = DB::insert('employees', $columns)->values($values)->execute();
				}
			}
		}
	}
	
	
	/*
	 * @Function get_employee
	 * @Description : We return employee info by record-id + company id.
	 * 
	 * @Return: array
	 */
	public function get_employee($record_id, $company_id){
		$sql = 'SELECT *
				FROM employees
				WHERE 	record_id = "'.$record_id.'"
					AND company_id = "'.$company_id.'"';
		$employee_data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($employee_data)) {
			return false;
		} else {
			return $employee_data;
		}
	}
	
	/*
	 * @Function get_all_employee
	 * @Description : We return all employee's information of a particular company  
	 * 
	 * @Return: array
	 */
	public function get_all_employee($company_id){
		$sql = 'SELECT *
				FROM employees
				WHERE 	company_id = "'.$company_id.'"
				ORDER BY CONCAT(first_name,company_or_last_name) ASC';
		$employee_data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		
		if(!empty($employee_data)){
			$return = array();
			foreach($employee_data as $emp_data){
				$return[] 	= 	array( 	"RecordID" 		=> $emp_data["record_id"],
	    								"CompanyOrLastName" => $emp_data["company_or_last_name"],
	    								"email" 		=> $emp_data["email"], 
										"firstname"		=> $emp_data["first_name"],
	    								"active_status"	=> $emp_data["active_status"]);
			}
		}
		if(empty($return)) {
			return false;
		} else {
			return $return;
		}
	}
	
	/*
	 * @Function get_employee_by_key
	 * @Description : We return all employee's information of a particular company  
	 * 
	 * @Return: array
	 */
	public function get_employee_by_key($search_key_value){		
		if(isset($search_key_value) && !empty($search_key_value)){
			$search_query = '';
			$flag = FALSE;
			foreach ($search_key_value as $key => $value) {
				if($flag){
					$search_query .= ' OR ';
				}
				$search_query .= $key .' = "'.$value.'"';
				$flag = TRUE;
			}
			$sql = 'SELECT * 
					FROM employees
					WHERE '.$search_query;

			$employee_data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		}
		
		if(!empty($employee_data)){
			$return = array();
			foreach($employee_data as $emp_data){
				$return[] 	= 	array( 	"RecordID" 		=> $emp_data["record_id"],
	    								"CompanyOrLastName" => $emp_data["company_or_last_name"],
	    								"email" 		=> $emp_data["email"], 
	    								"active_status"	=> $emp_data["active_status"]);
			}
		}
		
		if(empty($return)) {
			return false;
		} else {
			return $return;
		}
	}
	
	/*
	 * @Function 	update_employee_by_key
	 * @Description : Based the database key-value we can update the record in employee   
	 * 
	 * @Return: array
	 */
	public function update_employee_by_key($company_id, $record_id, $db_key_value){
		$result = DB::update('employees')->set($db_key_value)->where('company_id', '=', $company_id)->and_where('record_id', '=', $record_id)->execute();
	}	
	
	public function get_billing_rate($company_id,$employee_record_id){
		$sql	=	"Select billing_rate from employees where company_id = '".$company_id."' AND record_id = '".$employee_record_id."'";
		$employee_data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($employee_data)){
			return $employee_data[0]['billing_rate'];
		} else {
			return '';
		}
	}
	
/**
	 * @Method		:	get_all_employees
	 * @Description	:	Function to get all employees wheather active or in-active.
	 * @Return		:	if success array else NULL
	 */
	public function get_all_employees(){
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT id, record_id, concat(first_name,' ',company_or_last_name)as name
						FROM employees
						WHERE company_id = '".addslashes($_SESSION['company_id'])."' ORDER BY first_name ASC";
			
		} else {
			$sql	=	"SELECT id, record_id, concat(first_name,' ',company_or_last_name)as name
						FROM employees
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