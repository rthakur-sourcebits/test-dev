<?php
defined('SYSPATH') or die('No direct script access.');

/*
 * @File : 			vendor.php
 * @Class : 		Model_Vendor
 * @Description: 	Holds Vendor related database operations.
 * @Created Date: 	20-11-2013
 * @Created By: 	Rahul Thakur
 * @Modified:		20-11-2013 - create
 * 					20-11-2013 - Added function update_vendors
 * 					21-11-2013 - Added function get_vendor
 * 					21-11-2013 - Added function get_all_vendor  
 * 					21-11-2013 - Added function get_vendor_by_key
 * 					25-11-2013 - Added function update_vendor_by_key 
 */
class Model_Vendor extends Model
{
	
	/**
	 * Function to check tax already present or not
	 *
	 * @return unknown
	 */
	private function vendor_exists($record_id, $company_id) {
		$sql	=	"SELECT id
					 FROM vendors
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
	 * Function to update Vendors data from JSON file. 
	*     
	 *
	 * @param array $content
	 */
	public function update_vendors($contents) {
		if(count($contents)>0){
			foreach($contents as $c) {
				$record_id 	= 	empty($c['RecordID'])?"":$c['RecordID'];
				$first_name = 	empty($c['FirstName'])?"":$c['FirstName'];
				$company_or_last_name  = 	empty($c['CompanyOrLastName'])?"":$c['CompanyOrLastName'];
				$email  		= 	empty($c['Addr1Email'])?"":$c['Addr1Email'];
				$hourly_wages = '';
				if(isset($c['HourlyWageCategories']) && !empty($c['HourlyWageCategories'])){
					//$c['HourlyWageCategories']=json_decode($c['HourlyWageCategories']);
					foreach ($c['HourlyWageCategories'] as $value) { // check this
						$hourly_wages .= $value . ',';
					}
				}
				
				if($this->vendor_exists($record_id, $_SESSION['company_id'])) {
					$sql	=	"UPDATE vendors
								 SET 
									 first_name 	= '".addslashes($first_name)."',
									 company_or_last_name		= '".addslashes($company_or_last_name)."',
									 email			= '".addslashes($email)."',
									 hourly_wages	= '".addslashes($hourly_wages)."',
									 updated_date 	= now()
								 WHERE company_id 	= '".$_SESSION['company_id']."'
								 	AND record_id 	= '".$record_id."'
								 ";
					$this->_db->query(Database::UPDATE, $sql, False);
				} else {
					$sql	=	"INSERT INTO vendors
								 ( 	record_id, company_id, first_name, company_or_last_name, email, hourly_wages, updated_date, created_date)
								 VALUES (
								 	'".addslashes($record_id)."',
								 	'".addslashes($_SESSION['company_id'])."',
								 	'".addslashes($first_name)."',
								 	'".addslashes($company_or_last_name)."',
								 	'".addslashes($email)."',
								 	'".addslashes($hourly_wages)."',
								 	now(),
								 	now()
								 )";
					$this->_db->query(Database::INSERT, $sql, False);
				}
			}
		}
	}
	
	/*
	 * @Function get_vendor
	 * @Description : We return vendor info by record-id + company id.
	 * 
	 * @Return: array
	 */
	public function get_vendor($record_id, $company_id){
		$sql = 'SELECT *
				FROM vendors
				WHERE 	record_id = "'.$record_id.'"
					AND company_id = "'.$company_id.'"';
		$vendor_data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($vendor_data)) {
			return false;
		} else {
			return $vendor_data;
		}
	}
	
	/*
	 * @Function get_all_vendor
	 * @Description : We return all vendor's information of a particular company  
	 * 
	 * @Return: array
	 */
	public function get_all_vendor($company_id){
		$sql = 'SELECT *
				FROM vendors
				WHERE 	company_id = "'.$company_id.'"';
		$vendor_data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		
		if(!empty($vendor_data)){
			$return = array();
			foreach($vendor_data as $emp_data){
				$return[] 	= 	array( 	"RecordID" 		=> $emp_data["record_id"],
	    								"CompanyOrLastName" => $emp_data["company_or_last_name"],
	    								"email" 		=> $emp_data["email"], 
	    								"active_status"	=> $emp_data["active_status"]);
			}
		}
		
		if(empty($return)) {
			return $return= array();
		} else {
			return $return;
		}
	}
	
	/*
	 * @Function get_vendor_by_key
	 * @Description : We return all vendor's information of a particular company  
	 * 
	 * @Return: array
	 */
	public function get_vendor_by_key($search_key_value){		
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
					FROM vendors
					WHERE '.$search_query;

			$vendor_data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		}
		
		if(!empty($vendor_data)){
			$return = array();
			foreach($vendor_data as $emp_data){
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
	 * @Function 	update_vendor_by_key
	 * @Description : Based the database key-value we can update the record in vendor   
	 * 
	 * @Return: array
	 */
	public function update_vendor_by_key($company_id, $record_id, $db_key_value){
		$result = DB::update('vendors')->set($db_key_value)->where('company_id', '=', $company_id)->and_where('record_id', '=', $record_id)->execute();
		return TRUE;
	}	
	
}