<?php
defined('SYSPATH') or die('No direct script access.');

/*
 * @File : 			jobs.php
 * @Class : 		Model_Jobs
 * @Description: 	Holds Jobs related database operations.
 * @Created Date: 	04-09-2013 
 * @Created By: 	Rahul Thakur
 * @Modified:		04-09-2013 - created 
 */
class Model_Jobs extends Model
{
	// Function to bring all the jobs of the company
	public function get_all_jobs() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			$sql	=	"SELECT * FROM jobs WHERE company_id = '".$_SESSION['company_id']."' AND employee_id = '0' AND status = '1' ORDER BY updated_date ASC LIMIT 0,10";
		} else {
			$sql	=	"SELECT * FROM jobs WHERE company_id = '".$_SESSION['company_id']."' AND (employee_id = '".$_SESSION['employee_id']."' || employee_id = '0') AND status = '1' ORDER BY updated_date ASC LIMIT 0,10";
		}
		$result	=	DB::query(Database::SELECT, $sql)->execute()->as_array();
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
	 * @Function	:	get_sub_job
	 * @Description	:	Returns the total sub_jobs pages as per company id
	 * @Params		:	none
	 */
	public function get_sub_job($job_id='') {
		if(isset($job_id) && !empty($job_id)){
			$sql		=	"SELECT job_number as sub_job_number, job_name as sub_job_name from jobs where company_id = '".$_SESSION['company_id']."' AND is_header_job = 1 AND id != '".$job_id."'";
		} else {
			$sql		=	"SELECT job_number as sub_job_number, job_name as sub_job_name from jobs where company_id = '".$_SESSION['company_id']."' AND is_header_job = 1";
		}
		$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(!empty($result)){
			return $result;
		}
	}
}
?>