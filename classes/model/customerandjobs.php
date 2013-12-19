<?php
defined('SYSPATH') or die('No direct script access.');

/*
 * @File : customer.php
 * @Class : Model_Customer
 * @Description: Holds customer related database operations.
 * @ Modified Details: 
 * 						13.09.2013	- 	modified get_all_customer function for getting all the customer with there taxes info if any + following up on coding standard
 * 						20-11-2013 	- 	modifying get_all_customer function for consolidated TAX
 * 						10.12.2013 	-	Transfered Update_customer function from data.php
 * 						10.12.2013 	-	Transfered customer_exists function from data.php 	 
 */
class Model_Customerandjobs extends Model
{
	// Function to get all the countries
	public function get_countries() {
		$sql		=	"SELECT * FROM country_list WHERE 1 ";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// @Description:	Function to get all the customers
	// @parameter:		$flag = 0, 	- 	to limit the data
	//					$company_id=null
	//					$emp_id=null
	//					$admin_user=null
	// return_type: 	multi_dimension array
	//* 						20-11-2013 		- 	modifying get_all_customer function for consolidated TAX
	public function get_all_customer($flag = 0, $company_id=null, $emp_id=null, $admin_user=null) {
		$emp_str="";	
		if(isset($admin_user) && $admin_user == "1") {
			$emp_str = "employee_id = '0'";
		} else {
			$emp_str = "(employee_id = '".$emp_id."' || employee_id = '0')";
		}

		if(isset($company_id) && $company_id!= null){
			$sql	=	"SELECT c.*, cl.id as country_id, IF(cl.country IS NULL, c.country, cl.country) as country_name, t.percentage,t.tax_code, t.sub_tax_code,
						f.percentage as freight_percentage, f.tax_code as freight_tax_code, f.sub_tax_code as freight_sub_tax_code
						FROM customers c 
						LEFT JOIN country_list cl ON(c.country = cl.id)
						LEFT JOIN taxes AS t ON t.company_id = c.company_id
							AND t.tax_record_id = c.tax_record_id 
						LEFT JOIN taxes AS f ON f.company_id = c.company_id
							AND f.tax_record_id = c.freight_tax_record_id 
						WHERE c.company_id = '".$company_id."' 
							AND ".$emp_str." 
							AND c.status = '1' 
						ORDER BY CONCAT(c.firstname,c.company_or_lastname) ASC";
			if($flag == 0) {
				$sql	.=	" LIMIT 0,10";
			}
			
			$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			// Processing for consolidated TAX percentages.
			$return 	= 	array();
			foreach($result as $customer){
				if($customer['sub_tax_code']!=''){ // Getting consolidated TAX percentage.  -- for TAX code.
					
					// get the sum of the consolidated TAX percentage. -- for TAX code.
					$sql = 'SELECT SUM(t.percentage) as t_percentage
							FROM taxes as t
							LEFT JOIN sub_taxes as st ON st.tax_code_internal_id = t.id
							WHERE st.consolidated_taxes_internal_id = (	SELECT id 
																		FROM taxes 
																		WHERE tax_record_id = '.$customer["tax_record_id"].'
																		AND company_id = '.$_SESSION["company_id"].')';
					
					$percentage		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
					if(isset($percentage)){
						$customer['percentage']=$percentage[0]['t_percentage'];
					}
				}
				
				if($customer['freight_sub_tax_code']!=''){ // Getting consolidated TAX percentage.  -- for FREIGHT TAX code.
					
					// get the sum of the consolidated TAX percentage. -- for FREIGHT TAX code.
					$sql = 'SELECT SUM(t.percentage) as t_percentage
							FROM taxes as t
							LEFT JOIN sub_taxes as st ON st.tax_code_internal_id = t.id
							WHERE st.consolidated_taxes_internal_id = (	SELECT id 
																		FROM taxes 
																		WHERE tax_record_id = '.$customer["freight_tax_record_id"].'
																		AND company_id = '.$_SESSION["company_id"].')';
					
					$percentage		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
					if(isset($percentage)){
						$customer['freight_percentage']=$percentage[0]['t_percentage'];
					}
				}

				$return[] = $customer;
			}
			
		} else {
			$return = false;
		}
		return $return;
	}
	
	// Function to check email address exists or not
	public function email_exists($email, $customer_id=0) {
		if($customer_id != 0) {
			$sql	=	"SELECT id FROM customers WHERE email = '".addslashes($email)."' AND id != '".$customer_id."'";
		} else {
			$sql	=	"SELECT id FROM customers WHERE email = '".addslashes($email)."'";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return false;
		else return true;
	}
	
	// Function to save customer/jobs
	public function save($table, $columns, $values) {
		$query = DB::insert($table, $columns)->values($values)->execute();
		
		return true;
	}
	
	// Function to update customer/jobs
	public function update($table, $arr_data, $where_id) {
		$query = DB::update($table)->set($arr_data)->where('id', '=', $where_id)->execute();
		return true;
	}
	
	// Function delete customer/jobs
	public function delete($table, $id) {
		$total_rows = DB::delete($table)->where('id','=',$id)->execute();
		return true;
	}
	
	// Function to read customer details
	public function get_customer($customer_id,$company_id) {
		$sql	=	"SELECT c.*, t.tax_code as tax_code,f.tax_code as freight_tax_code, AES_DECRYPT(ct.payment_token, '".ENCRYPT_KEY."') as payment_token
		FROM customers c
		LEFT JOIN customer_token AS ct ON (ct.customer_id=c.id)
		LEFT JOIN taxes AS t 
			ON (t.tax_record_id = c.tax_record_id AND t.company_id = '".$company_id."')
		LEFT JOIN taxes AS f 
			ON (f.tax_record_id = c.freight_tax_record_id AND f.company_id = '".$company_id."')
		WHERE c.id = '".$customer_id."'";

		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return null;
		else return $result;
	}
	
	// Function to get country name
	public function get_country($id) {
		$sql	=	"SELECT country FROM country_list WHERE id = '".$id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return null;
		else return $result[0]['country']; 
	}
	
	
// 		Function to read customer details
	public function get_jobs($job_id) {
		$sql	=	"SELECT * FROM jobs WHERE id = '".$job_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return null;
		else return $result;
	}
	
	// Function to get all customer taxes
	public function get_all_taxes() {
		$sql	=	"SELECT * FROM taxes WHERE company_id = '".$_SESSION['company_id']."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return null;
		else return $result;
	}
	
	// Function to get all the customers which can be related to jobs (AED customers)
	public function get_job_related_customers() {
		$sql	=	"SELECT * FROM customers WHERE company_id = '".$_SESSION['company_id']."' AND status = '1' AND record_id != '0' ORDER BY CONCAT(firstname,company_or_lastname) ASC";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
// Function to get all the customers which can be related to jobs (AED customers)
	public function get_job_related_customer_info($customer_record_id) {
		$sql	=	"SELECT record_id, firstname, company_or_lastname FROM customers WHERE company_id = '".$_SESSION['company_id']."' AND record_id = '".$customer_record_id."' ";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	//	Function to tax id by tax code
	public function get_tax_details($tax_record_id) {
		$sql	=	"SELECT tax_record_id, tax_code FROM taxes WHERE company_id = '".$_SESSION['company_id']."' AND tax_record_id = '".$tax_record_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)) return null;
		else return $result;
	}
	
	// Function to get all customer list to be synced
	public function get_customer_to_sync() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT 'Customer' as type,id,created_date,updated_date,company_id as sale_number,'' as total_payment,company_or_lastname ,firstname
						FROM customers WHERE company_id = '".$_SESSION['company_id']."' AND employee_id = '0' AND synced_status = '0' ORDER BY updated_date DESC";
		} else {
			$sql	=	"SELECT 'Customer' as type,id,created_date,updated_date,company_id as sale_number,'' as total_payment,company_or_lastname ,firstname
						FROM customers WHERE company_id = '".$_SESSION['company_id']."' AND (employee_id = '".$_SESSION['employee_id']."' || employee_id = '0') AND synced_status = '0' ORDER BY updated_date DESC";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get all customer list to be synced
	public function get_customer_to_sync_all() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT c.* ,t.tax_code as tax_code,f.tax_code as freight_tax_code
						FROM customers c 
						LEFT JOIN taxes AS t 
							ON (t.tax_record_id = c.tax_record_id AND t.company_id = '".$_SESSION['company_id']."')
						LEFT JOIN taxes AS f 
							ON (f.tax_record_id = c.freight_tax_record_id AND f.company_id = '".$_SESSION['company_id']."')
						 WHERE c.company_id = '".$_SESSION['company_id']."' AND employee_id = '0' AND synced_status = '0'";

		} else {
			$sql	=	"SELECT c.*, t.tax_code as tax_code,f.tax_code as freight_tax_code
						FROM customers c 
						LEFT JOIN taxes AS t 
							ON (t.tax_record_id = c.tax_record_id AND t.company_id = '".$_SESSION['company_id']."')
						LEFT JOIN taxes AS f 
							ON (f.tax_record_id = c.freight_tax_record_id AND f.company_id = '".$_SESSION['company_id']."')
						WHERE c.company_id = '".$_SESSION['company_id']."' AND (employee_id = '".$_SESSION['employee_id']."' || employee_id = '0') AND synced_status = '0'";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	
	
	// Function to fetch all the jobs list to be synced
	public function get_jobs_to_sync() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT 'Job' as type,id,created_date,updated_date,company_id as sale_number,'' as total_payment,job_name as company_or_lastname
						FROM jobs WHERE company_id = '".$_SESSION['company_id']."' AND employee_id = '0' AND synced_status = '0' ORDER BY updated_date DESC";
		} else {
			$sql	=	"SELECT 'Job' as type,id,created_date,updated_date,company_id as sale_number,'' as total_payment,job_name as company_or_lastname
						FROM jobs WHERE company_id = '".$_SESSION['company_id']."' AND (employee_id = '".$_SESSION['employee_id']."' || employee_id = '0') AND synced_status = '0' ORDER BY updated_date DESC";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to fetch all the jobs list to be synced
	public function get_jobs_to_sync_all() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT * FROM jobs WHERE company_id = '".$_SESSION['company_id']."' AND employee_id = '0' AND synced_status = '0'";
		} else {
			$sql	=	"SELECT * FROM jobs WHERE company_id = '".$_SESSION['company_id']."' AND (employee_id = '".$_SESSION['employee_id']."' || employee_id = '0') AND synced_status = '0'";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Function to get customer name and id
	public function get_customer_name($customer_id) {
		$sql	=	"SELECT id, record_id, company_or_lastname, firstname
					 FROM customers
					 WHERE id = '".$customer_id."'";
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;	
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	paginated_customers
	 * @Description	:	ajax pagination of data
	 * @Params		:	start, end, $field for sorting, order
	 */ 
	public function paginated_customers($start, $end,$field="company_or_lastname", $order="DESC") {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT c.*
						 FROM customers c 
						 WHERE c.company_id = '".$_SESSION['company_id']."' AND c.employee_id = '0' AND c.status = '1'
						 ORDER BY $field $order
						 LIMIT $start, $end";
		} else {
			$sql	=	"SELECT c.*
			 			 FROM customers c 
			 			 WHERE c.company_id = '".$_SESSION['company_id']."' AND (c.employee_id = '".$_SESSION['employee_id']."' || c.employee_id = '0') AND c.status = '1'
						 ORDER BY $field $order
			 			 LIMIT $start, $end";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// Get total customers
	public function total_customers() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT count(*) as total 
						 FROM customers 
						 WHERE company_id = '".$_SESSION['company_id']."' AND employee_id = '0' AND status = '1'
						";
		} else {
			$sql	=	"SELECT count(*) as total
			 			 FROM customers 
			 			 WHERE company_id = '".$_SESSION['company_id']."' AND (employee_id = '".$_SESSION['employee_id']."' || employee_id = '0') AND status = '1'
			 			";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result[0]['total'];
	}
	

	// Get total customer pages
	public function total_customer_pages() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT count(*) as total 
						 FROM customers 
						 WHERE company_id = '".$_SESSION['company_id']."' AND employee_id = '0' AND status = '1'
						";
		} else {
			$sql	=	"SELECT count(*) as total 
			 			 FROM customers 
			 			 WHERE company_id = '".$_SESSION['company_id']."' AND (employee_id = '".$_SESSION['employee_id']."' || employee_id = '0') AND status = '1'
			 			";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		$page_count	=	intval($result[0]['total'])/10;
		if($result[0]['total']%10 != 0) $page_count	+=	1;
		return $page_count;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	paginated_jobs
	 * @Description	:	ajax pagination of data
	 * @Params		:	start, end, $field for sorting, order
	 */
	public function paginated_jobs($start, $end,$field="created_date", $order="DESC") {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT j.* 
						 FROM jobs j 
						 WHERE j.company_id = '".$_SESSION['company_id']."' AND j.employee_id = '0' AND j.status = '1'
						 ORDER BY $field $order
						 LIMIT $start, $end";
		} else {
			$sql	=	"SELECT j.* 
			 			 FROM jobs j 
			 			 WHERE j.company_id = '".$_SESSION['company_id']."' AND (j.employee_id = '".$_SESSION['employee_id']."' || j.employee_id = '0') AND j.status = '1'
			 			 ORDER BY $field $order 
						 LIMIT $start, $end";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		
		return $result;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	total_jobs
	 * @Description	:	Returns the total jobs as per company id and employee id
	 * @Params		:	none
	 */
	public function total_jobs() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT count(*) as total 
						 FROM jobs 
						 WHERE company_id = '".$_SESSION['company_id']."' AND employee_id = '0' AND status = '1'
						";
		} else {
			$sql	=	"SELECT count(*) as total
			 			 FROM jobs 
			 			 WHERE company_id = '".$_SESSION['company_id']."' AND (employee_id = '".$_SESSION['employee_id']."' || employee_id = '0') AND status = '1'
			 			";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result[0]['total'];
	}
	

	/**
	 * @Access		:	Public
	 * @Function	:	total_job_pages
	 * @Description	:	Returns the total jobs pages as per company id and employee id
	 * @Params		:	none
	 */
	public function total_job_pages() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT count(*) as total 
						 FROM jobs 
						 WHERE company_id = '".$_SESSION['company_id']."' AND employee_id = '0' AND status = '1'
						";
		} else {
			$sql	=	"SELECT count(*) as total 
			 			 FROM customers 
			 			 WHERE company_id = '".$_SESSION['company_id']."' AND (employee_id = '".$_SESSION['employee_id']."' || employee_id = '0') AND status = '1'
			 			";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		$page_count	=	intval($result[0]['total'])/10;
		if($result[0]['total']%10 != 0) $page_count	+=	1;
		return $page_count;
	}

	/**
	 * @Access		:	Public
	 * @Function	:	get_custom_names
	 * @Description	:	Returns the custom names fields for customer module
	 * @Params		:	none
	 */
	public function get_custom_names() {
		$sql		=	"SELECT *
						 FROM custom_list_fields
					 	 WHERE company_id  = '".$_SESSION['company_id']."'";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}

	/**
	 * @Access		:	Public
	 * @Function	:	get_custom_list_1
	 * @Description	:	Returns the custom_list_1 for customer module
	 * @Params		:	none
	 */
	public function get_custom_list_1() {
		$sql		=	"SELECT * FROM custom_list_items WHERE list_number='1' 
						AND company_id = '".$_SESSION['company_id']."' ORDER BY custom_list_name ASC
						";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}

	/**
	 * @Access		:	Public
	 * @Function	:	get_custom_list_2
	 * @Description	:	Returns the custom_list_2 for customer module
	 * @Params		:	none
	 */
	public function get_custom_list_2() {
		$sql		=	"SELECT * FROM custom_list_items WHERE list_number='2' 
						AND company_id = '".$_SESSION['company_id']."' ORDER BY custom_list_name ASC
						";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}

	/**
	 * @Access		:	Public
	 * @Function	:	get_custom_list_3
	 * @Description	:	Returns the custom_list_3 for customer module
	 * @Params		:	none
	 */
	public function get_custom_list_3() {
		$sql		=	"SELECT * FROM custom_list_items WHERE list_number='3' 
						AND company_id = '".$_SESSION['company_id']."' ORDER BY custom_list_name ASC
						";
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	get_customer_search_result
	 * @Description	:	Returns the search result
	 * @Params		:	search_keyword, limit, field, order
	 */
    public function get_customer_search_result($search_keyword, $limit=0, $field="company_or_lastname", $order = "1") {
    	$search_keyword		=		strtolower($search_keyword);
    	if(stripos("company card",$search_keyword) !== FALSE  && stripos("individual card",$search_keyword) !== FALSE) {
    		//$company_or_individual='';	  
    	} elseif(stripos("individual",$search_keyword) !== FALSE){
    	  	$company_or_individual=1;
    	} elseif(stripos("company",$search_keyword) !== FALSE){
    		$company_or_individual=0;
    	} else{
    		$company_or_individual=2;
    	}
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT c.*
						 FROM customers c
						 WHERE c.company_id = '".$_SESSION['company_id']."' 
						 AND c.employee_id='0'
						 AND (
						 	c.phone	LIKE '%".$search_keyword."%' OR 
						 	lower(c.contact) LIKE '%".$search_keyword."%' OR 
						 	lower(c.company_or_lastname) LIKE '%".$search_keyword."%'
						 ";
		} else {
			$sql	=	"SELECT c.*
						 FROM customers c
						 WHERE c.company_id = '".$_SESSION['company_id']."'
						 AND (c.employee_id = '".$_SESSION['employee_id']."' || c.employee_id = '0')
						 AND (
						 	c.phone	LIKE '%".$search_keyword."%' OR
						 	lower(c.contact) LIKE '%".$search_keyword."%' OR 
						 	lower(c.company_or_lastname) LIKE '%".$search_keyword."%'
						";
		}
		if(isset($company_or_individual)){
			if($company_or_individual==2){
				$sql	.=	" ) ORDER BY '".$field."' '".$order."'";
			}else{
				$sql	.=	" OR c.is_individual_card	LIKE '%".$company_or_individual."%' ) ORDER BY '".$field."' '".$order."'";	
			}
		} else{
			$sql	.=	" OR c.is_individual_card IN ('0','1'))  ORDER BY '".$field."' '".$order."'";
		}
		$start		=	isset($_POST['page'])?$_POST['page']:0;
		$per_page	=	!empty($_POST['view_per_page'])?$_POST['view_per_page']:10;
		if(!empty($_POST['view_per_page']) && $limit == 1) {
			$start	=	$start*$per_page;
			$sql   .=	" LIMIT $start, ".$per_page;
		} else if($limit == 1) {
			$sql   .=	" LIMIT $start, 10";
		} else if($limit == 2) {
           $start	=	($start*$per_page)-$per_page;
			$sql   .=	" LIMIT $start, ".$per_page;
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}

	/**
	 * @Access		:	Public
	 * @Function	:	get_jobs_search_result
	 * @Description	:	Returns the search result
	 * @Params		:	search_keyword, limit, field, order
	 */
	public function get_jobs_search_result($search_keyword, $limit=0, $field="job_number", $order = "1") {
		$search_keyword		=		strtolower($search_keyword);
		if(stripos("header job",$search_keyword) !== FALSE  &&  stripos("detail job",$search_keyword) !== FALSE) {
    		//$header_or_detail='';	  
    	} elseif(stripos("detail",$search_keyword) !== FALSE){
    	  	$header_or_detail=0;
    	} elseif(stripos("header",$search_keyword) !== FALSE){
    		$header_or_detail=1;
    	} else{
    		$header_or_detail=2;
    	}
    	
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT j.*
						 FROM jobs j
						 WHERE j.company_id = '".$_SESSION['company_id']."' 
						 AND j.employee_id='0'
						 AND (
						 	lower(j.job_name) LIKE '%".$search_keyword."%' OR 
						 	j.job_number LIKE '%".$search_keyword."%' 
						 ";
		} else {
			$sql	=	"SELECT j.*
						 FROM jobs j
						 WHERE j.company_id = '".$_SESSION['company_id']."' 
						 AND (j.employee_id = '".$_SESSION['employee_id']."' || j.employee_id = '0')
						 AND (
						 	lower(j.job_name) LIKE '%".$search_keyword."%' OR 
						 	j.job_number LIKE '%".$search_keyword."%' 
						 ";
		}
		
		if(isset($header_or_detail)){
			if($header_or_detail==2){
				$sql	.=	" ) ORDER BY '".$field."' '".$order."'";	
			}else{
				$sql	.=	" OR j.is_header_job LIKE '%".$header_or_detail."%') ORDER BY '".$field."' '".$order."'";
			}
		}
		else{
			$sql	.=	"	OR j.is_header_job IN ('0','1')) ORDER BY '".$field."' '".$order."'";
		}
	     $start		=	isset($_POST['page'])?$_POST['page']:0;
		$per_page	=	!empty($_POST['view_per_page'])?$_POST['view_per_page']:10;
		
		if(!empty($_POST['view_per_page']) && $limit == 1) {
			$start	=	$start*$per_page;
			$sql   .=	" LIMIT $start, ".$per_page;
		} else if($limit == 1) {
			$sql   .=	" LIMIT $start, 10";
		} else if($limit == 2) {
           $start	=	($start*$per_page)-$per_page;
			$sql   .=	" LIMIT $start, ".$per_page;
		}	
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		return $result;
	}
	
	// 
	public function get_customer_tax($customer_id){
		
	}
	
	
	/**
	 * Function to check customer already present or not
	 *
	 * @param unknown_type $customer_id
	 * @param unknown_type $company_id
	 * @return unknown
	 */
	private function customer_exists($customer_id, $company_id) {
		$sql	=	"SELECT id
					 FROM customers
					 WHERE record_id = '".addslashes($customer_id)."'
					 AND company_id = '".addslashes($company_id)."'";
		$data	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($data)) return false;
		else return true;
	}
	
	
	/**
	 * 
	 * Function to insert/update dropbox customer json files
	 *
	 * @param unknown_type $contens
	 */
	public function update_customers($contens) {
		if(count($contens)>0){
		$sales_m =	new Model_Sales;
		$key_value['information_type']	= '3';
		$key_value['company_id']		= $_SESSION['company_id'];
			
		foreach($contens as $c) {
			if ( isset($c['Addr1Email'])){
				$c['Addr1Email']=strtolower($c['Addr1Email']);	
			}			
			$is_card_inactive		=	(isset($c['IsCardInactive']) && ($c['IsCardInactive'] == true || $c['IsCardInactive'] == "1" || $c['IsCardInactive'] == "true" ))?"1":"0";
			$is_individual_card		=	(isset($c['IsIndividualCard']) && ($c['IsIndividualCard'] == true || $c['IsIndividualCard'] == "1" || $c['IsIndividualCard'] == "true" ))?"1":"0";
			$use_customer_tax_code	= 	(isset($c['UseCustomerTaxCode']) && ($c['UseCustomerTaxCode'] == true || $c['UseCustomerTaxCode'] == "1" || $c['UseCustomerTaxCode'] == "true" ))?"1":"0";
			$company_or_lastname	=	empty($c['CompanyOrLastName'])?	"":$c['CompanyOrLastName'];
			$expire_days			=	empty($c['ExpirationDate'])?	"":$c['ExpirationDate'];
			$last_digits_on_card	=	empty($c['Last4DigitsOnCard'])?	"":$c['Last4DigitsOnCard'];
			$name_on_card			=	empty($c['NameOnCard'])?		"":$c['NameOnCard'];
			$firstname				=	empty($c['FirstName'])?			"":$c['FirstName'];
			$payment_method			=	empty($c['PaymentMethod'])?		"":$c['PaymentMethod'];
			$record_id				=	empty($c['RecordID'])?			"":$c['RecordID'];
			$billing_rate			=	empty($c['BillingRate'])?		"":$c['BillingRate'];
			$contact				=	empty($c['Addr1ContactName'])?	"":$c['Addr1ContactName'];
			$tax_record_id			=	empty($c['TaxCodeRecordID'])?	"":$c['TaxCodeRecordID'];
			$freight_tax_record_id	=	empty($c['FreightTaxCodeRecordID'])?empty($c['TaxCodeRecordID'])?"":$c['TaxCodeRecordID']:$c['FreightTaxCodeRecordID'];
			$type_of_card			=	empty($c['TypeOfCard'])?	"":$c['TypeOfCard'];
			$custom_list1			=	empty($c['CustomList1'])?	"":$c['CustomList1'];
			$custom_list2			=	empty($c['CustomList2'])?	"":$c['CustomList2'];
			$custom_list3			=	empty($c['CustomList3'])?	"":$c['CustomList3'];
			$custom_field1			=	empty($c['CustomField1'])?	"":$c['CustomField1'];
			$custom_field2			=	empty($c['CustomField2'])?	"":$c['CustomField2'];
			$custom_field3			=	empty($c['CustomField3'])?	"":$c['CustomField3'];
			$city					=	empty($c['Addr1City'])?		"":$c['Addr1City'];
			$street1				=	empty($c['Addr1Line1'])?	"":$c['Addr1Line1'];
			$street2				=	empty($c['Addr1Line2'])?	"":$c['Addr1Line2'];
			$phone					=	empty($c['Addr1Phone1'])?	"":$c['Addr1Phone1'];
			$state					=	empty($c['Addr1State'])?	"":$c['Addr1State'];
			$email					=	empty($c['Addr1Email'])?	"":$c['Addr1Email'];
			$country				=	empty($c['Addr1Country'])?	"":$c['Addr1Country'];
			$zipcode				=	empty($c['Addr1ZipCode'])?	"":$c['Addr1ZipCode'];
			$account				=	empty($c['Account'])?		"":$c['Account'];
			$salesperson_id			=	empty($c['SalespersonRecordID'])?"":$c['SalespersonRecordID'];
			$salesperson			=	empty($c['Salesperson'])?	"":$c['Salesperson'];
			$payment_is_due			=	!isset($c['PaymentIsDue'])?	0:$c['PaymentIsDue'];
			$balance_due_days		=	!isset($c['BalanceDueDays'])?0:$c['BalanceDueDays'];
			$discount_days			=	!isset($c['DiscountDays'])?	0:$c['DiscountDays'];
			$early_payment_discount	=	!isset($c['EarlyPaymentDiscountPercent'])?0:$c['EarlyPaymentDiscountPercent'];
			$late_payment_charge	=	!isset($c['LatePaymentChargePercent'])?0:$c['LatePaymentChargePercent'];
			
			if($payment_method!=""){	// get payment-type and add here.
				$key_value['information_name']	= $payment_method;
				$sale_purchase_data = $sales_m->search_sales_and_purchase_data($key_value);
				if(isset($sale_purchase_data[0]['payment_type'])){
					$payment_type 	= $sale_purchase_data[0]['payment_type'];
				} else {
					$payment_type 	= 0; // default value 
				}
			} else {
				$payment_type 	= 0; // default value
			}
			
			if(isset($c['UseCustomerTaxCode']) && !empty($c['UseCustomerTaxCode'])){
			$use_customer_tax_code	=	$c['UseCustomerTaxCode'];
			} else {
			$use_customer_tax_code	=	0;
			}
			if($this->customer_exists($c['RecordID'], $_SESSION['company_id'])) {
				$sql	=	"UPDATE customers
							 SET company_or_lastname	= '".addslashes($company_or_lastname)."',
							 	 account				=	'".addslashes($account)."',
							 	 contact				=	'".addslashes($contact)."',
							 	 tax_record_id			=	'".addslashes($tax_record_id)."',
							 	 type					=	'".addslashes($type_of_card)."',
							 	 custom_list1			=	'".addslashes($custom_list1)."',
							 	 custom_list2			=	'".addslashes($custom_list2)."',
							 	 custom_list3			=	'".addslashes($custom_list3)."',
							 	 custom_field1			=	'".addslashes($custom_field1)."',
							 	 custom_field2			=	'".addslashes($custom_field2)."',
							 	 custom_field3			=	'".addslashes($custom_field3)."',
							 	 freight_tax_record_id	=	'".addslashes($freight_tax_record_id)."',
							 	 use_customer_tax_code	=	'".addslashes($use_customer_tax_code)."',
							 	 street1				=	'".addslashes($street1)."',
							 	 street2				=	'".addslashes($street2)."',
							 	 city					=	'".addslashes($city)."',
							 	 state					=	'".addslashes($state)."',
							 	 country				=	'".addslashes($country)."',
							 	 zip					=	'".addslashes($zipcode)."',
							 	 email					=	'".addslashes($email)."',
							 	 phone					=	'".addslashes($phone)."',
							 	 billing_rate			=	'".addslashes($billing_rate)."',
							 	 expiration_date 		= 	'".addslashes($expire_days)."',
							 	 is_card_inactive		= 	'".$is_card_inactive."',
							 	 is_individual_card		= 	'".$is_individual_card."',
							 	 last_digits_on_card 	= 	'".addslashes($last_digits_on_card)."',
							 	 name_on_card 			= 	'".addslashes($name_on_card)."',
							 	 firstname 				= 	'".addslashes($firstname)."',
							 	 payment_method 		= 	'".addslashes($payment_method)."',
							 	 payment_type	 		= 	'".addslashes($payment_type)."',
							 	 salesperson_id			=	'".addslashes($salesperson_id)."',
							 	 salesperson			=	'".addslashes($salesperson)."',
							 	 payment_is_due			=	'".addslashes($payment_is_due)."',
							 	 balance_due_days		=	'".addslashes($balance_due_days)."',
							 	 discount_days			=	'".addslashes($discount_days)."',
							 	 early_payment_discount	=	'".addslashes($early_payment_discount)."',
							 	 late_payment_charge	=	'".addslashes($late_payment_charge)."',
							 	 updated_date 			= 	now(),
							 	 synced_status 			= 	'1',
							 	 status					=	'1'
							 WHERE record_id 			= 	'".$record_id."'
							 AND company_id  			= 	'".$_SESSION['company_id']."' 
							 ";
			} else {
				$sql	=	"INSERT INTO customers 
							 (company_id, company_or_lastname, account, contact,  tax_record_id, freight_tax_record_id, 
							  use_customer_tax_code, type, 
							  custom_list1, custom_list2, custom_list3, custom_field1, custom_field2, custom_field3,
							  street1, street2, city, state, country, zip, email, phone, 
							  billing_rate, expiration_date, is_card_inactive, is_individual_card, last_digits_on_card, name_on_card, firstname, 
							  record_id, payment_method, payment_type, salesperson_id, salesperson, payment_is_due, 
							  balance_due_days,discount_days,early_payment_discount,late_payment_charge, created_date, updated_date, 
							  status, synced_status)
							 VALUES (
								'".$_SESSION['company_id']."',
								'".addslashes($company_or_lastname)."',
								'".addslashes($account)."',
								'".addslashes($contact)."',
							 	'".addslashes($tax_record_id)."',
							 	'".addslashes($freight_tax_record_id)."',
							 	'".addslashes($use_customer_tax_code)."',
							 	'".addslashes($type_of_card)."',
							 	'".addslashes($custom_list1)."',
							 	'".addslashes($custom_list2)."',
							 	'".addslashes($custom_list3)."',
							 	'".addslashes($custom_field1)."',
							 	'".addslashes($custom_field2)."',
							 	'".addslashes($custom_field3)."',
								'".addslashes($street1)."',
								'".addslashes($street2)."',
								'".addslashes($city)."',
								'".addslashes($state)."',
								'".addslashes($country)."',
								'".addslashes($zipcode)."',
								'".addslashes($email)."',
								'".addslashes($phone)."',
								'".addslashes($billing_rate)."',
								'".addslashes($expire_days)."',
								'".addslashes($is_card_inactive)."',
								'".addslashes($is_individual_card)."',
								'".addslashes($last_digits_on_card)."',
								'".addslashes($name_on_card)."',
								'".addslashes($firstname)."',
								'".$record_id."',
								'".addslashes($payment_method)."',
								'".addslashes($payment_type)."',
								'".addslashes($salesperson_id)."',
								'".addslashes($salesperson)."',
								'".addslashes($payment_is_due)."',
								'".addslashes($balance_due_days)."',
								'".addslashes($discount_days)."',
								'".addslashes($early_payment_discount)."',
								'".addslashes($late_payment_charge)."',
								now(),
								now(),
								'1',
								'1'
							 )";
			}
			$this->_db->query(Database::INSERT, $sql, False);
		}}
		return true;
	}

}