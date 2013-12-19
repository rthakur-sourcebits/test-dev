<?php
defined('SYSPATH') or die('No direct script access.');
/**
	@file			: timesheet.php Model
	@class			: Model_Timesheet
	@created date	: 30/Aug/2010
	@Descritpion	: Timesheet model which reads user one week time sheet and return back to the Controller.
*/
class Model_Timesheet extends Model {

	public $start_time;
	public $end_time;
	public $activity_slips;
	//public $start_time;
	//public $end_time;
	public function __construct($id = NULL)
	{
		// load database library into $this->db (can be omitted if not required)
		parent::__construct($id);
	}
/**
	@function 		: read_timesheet_log()
	@Description	: Read time entry table and get all the user one week activities.
*/
	public function read_timesheet_log($start_time,$end_time)
	{
		$this->start_time	=	$start_time;
		$this->end_time		=	$end_time;
		$user_id	 		=	$_SESSION['employee_id'];
		$day_start	 		=	date('d',$start_time);
		$month_start 		=	date('m',$start_time);
		$year_start	 		=	date('Y',$start_time);
		$day_end	 		=	date('d',$end_time);
		$month_end 	 		=	date('m',$end_time);
		$year_end	 		=	date('Y',$end_time);
		$json		 		=	new Model_Json;
		$activitysheet	 	=	new Model_activitysheet;
		
		//$json->file_content	=	$_SESSION['ActivitySlips'];
		switch($_SESSION['synced_slips_view'])
		{
			case 0: $where_sync_view	= " AND sync_status = 0 ";
					break;
			case 1: $where_sync_view	= " AND sync_status = 1 ";
					break;
			case 2: $where_sync_view	= "";
					break;
		}
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
			if(isset($_SESSION['selected_emp_id'])){
				$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
			} else{
				$emp_cond	=	"";
			} 
			$sql	=	"SELECT *
						FROM activity_slip_lists
						WHERE company_id = '".$_SESSION['company_id']."'
						$emp_cond
						AND SlipDate BETWEEN '".date("Y-m-d",$start_time)."' AND '".date("Y-m-d",$end_time)."'
						AND Is_non_hourly = 0
						AND Units != 0 
						$where_sync_view
						GROUP BY ActivityID, CustomerCompanyOrLastName, Notes, JobNumber, PayrollCategory, sync_status";
		} else{
			if($_SESSION['synced_slips_view'] == '1'){
				$admin_check	=	"";
			}else{
				$admin_check	=	"AND is_admin = '".$_SESSION['admin_user']."'";
			}
			$sql	=	"SELECT *
						 FROM activity_slip_lists
						 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."'
						 AND company_id = '".$_SESSION['company_id']."'
						 $admin_check
						 AND SlipDate BETWEEN '".date("Y-m-d",$start_time)."' AND '".date("Y-m-d",$end_time)."'
						 AND Is_non_hourly = 0
						 AND Units != 0 
						 $where_sync_view
						 GROUP BY ActivityID, CustomerCompanyOrLastName, Notes, JobNumber, PayrollCategory, sync_status";
		}
		$arr_order_slips	=	 $this->_db->query(Database::SELECT, $sql, False)->as_array();
		return  $json->join($arr_order_slips,   array(0 => array('0'  => 'RecordID',
															   '1'  => $activitysheet->get_synced_slips(),
															   '2' 	=> 'ActivitySync'		
															   )  
															   ));
	}
	
	
	/**
	 * @function	:	get_timesheet_per_day
	 * @param1		:	Array contains list of activities.
	 * @description	:	read each day time entries from active logs.
	 */
	public function get_timesheet_per_day($timesheet)
	{
		
		$user_id	=	$_SESSION['employee_id'];
		$json		=	new Model_Json;
		$json->file_content		=	$this->activity_slips;
		
		// check for activity display method
		switch($_SESSION['synced_slips_view'])
		{
			case 0: $where_sync_view	= " AND sync_status = 0 ";
					break;
			case 1: $where_sync_view	= " AND sync_status = 1 ";
					break;
			case 2: $where_sync_view	= "";
					break;
		}
		
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
			if(isset($_SESSION['selected_emp_id'])){
				$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
			} else{
				$emp_cond	=	"";
			}
		}else{	
			$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['employee_id']."'";
		}
	
		//if($_SESSION['synced_slips_view'] == '1'){
		if($_SESSION['admin_user'] == 1){
			$admin_check	=	"";
		} else {
			$admin_check	=	"AND is_admin = '0'";
		}

		$sql	=	"SELECT *, sum(units) as total_units, count(units) as total_slips
					 FROM activity_slip_lists
					 WHERE company_id = '".$_SESSION['company_id']."'
					 $emp_cond
					 AND SlipDate BETWEEN '".date("Y-m-d",$this->start_time)."' AND '".date("Y-m-d",$this->end_time)."'
					 AND ActivityID = '".addslashes($timesheet["ActivityID"])."'
					 AND CustomerCompanyOrLastName = '".addslashes($timesheet["CustomerCompanyOrLastName"])."'
					 AND Notes = '".addslashes($timesheet["Notes"])."'
					 AND JobNumber = '".addslashes($timesheet["JobNumber"])."'
					 AND PayrollCategory = '".addslashes($timesheet["PayrollCategory"])."'
					 AND Is_non_hourly = 0
					 AND Units != 0
					 $where_sync_view
					 $admin_check
					 GROUP BY SlipDate
					 ORDER BY SlipDate ASC
					 ";
		$arr_timesheet_reset	=	$this->_db->query(Database::SELECT, $sql, False)->as_array();
		$activitysheet	 		=	new Model_activitysheet;
		$arr_timesheet_reset 	=	$json->join($arr_timesheet_reset,   array(	0	=> array(
																						'0' => 'RecordID',
															   							'1' => $activitysheet->get_synced_slips(),
															   							'2' => 'ActivitySync')));
		return $arr_timesheet_reset;
	}

	
	/**
	@function 	 :  sync_timesheets
	@description :  Sync and Sync all timesheet ie, lock the time sheet.
	*/
	public function sync_timesheets()
	{
		$user_id		=	$_SESSION['employee_id'];
		$json			=   new Model_json;
		$dropbox		=   new Model_dropbox;
		$activity_sheet	=   new Model_activitysheet;
		$query_slip_id	=	"";
		$sync_status	=	false;
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
			if(isset($_SESSION['selected_emp_id'])){
				$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
			} else{
				$emp_cond	=	"";
			}
		}else{	
			$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['employee_id']."'
							 AND is_admin = '".$_SESSION['admin_user']."'";
		}
		
		if(isset($_POST['sync']))
		{
			if(empty($_POST['timesheet_check_list']))
			{
				throw new Exception("1");
			}
			$arr_slips			=	$_POST['timesheet_check_list'];
		}
		elseif (isset($_POST['syncall']))
		{
			
			$sql		=	"SELECT RecordID
							 FROM activity_slip_lists
							 WHERE company_id = '".$_SESSION['company_id']."'
							 $emp_cond
							 AND Units != 0
							 AND sync_status = 0";
			$data		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			foreach($data as $slip_recordid){
				$arr_slips[] = 	$slip_recordid['RecordID'];
			}
		}
		$json->file_content	=	'';	// empty the object.
		
		if(empty($arr_slips)) 
		{
			throw new Exception("1");
		}
		$slip_count	=	count($arr_slips);
		for($i=0;$i<$slip_count;$i++)//foreach($arr_slips as $slip_id)
		{
			
			try{
				$sql	=	"UPDATE activity_slip_lists
							 SET sync_status = 1,
							 	 sync_date = now()
							 WHERE RecordID = '".$arr_slips[$i]."'
							 $emp_cond
							 AND company_id = '".$_SESSION['company_id']."'";
				$this->_db->query(Database::INSERT, $sql, False);

				$sql	=	"INSERT INTO activities_sync (RecordID, company_id, SyncDate)
							 	VALUES ('".$arr_slips[$i]."','".$_SESSION['company_id']."', now()) 
							 	";
				$qresult=	$this->_db->query(Database::INSERT, $sql, False);
				$sync_status	=	true;
				
				/*********Create json file for syncing process*****************/
				$sql	=	"SELECT *
							 FROM activity_slip_lists
							 WHERE RecordID = '".$arr_slips[$i]."'";
				$slip_info		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
				$arr_slip_date	=	explode("-",$slip_info[0]['SlipDate']);
				$slip_date		=	array(
											"Day" => $arr_slip_date[2],
											"Month" => $arr_slip_date[1],
											"Year" => $arr_slip_date[0]
									);
			
				$sync_slips[$i]['ActivityID']					=	html_entity_decode($slip_info[0]['ActivityID']);
				$sync_slips[$i]['CustomerCompanyOrLastName']	=	html_entity_decode($slip_info[0]['CustomerCompanyOrLastName']);
				$sync_slips[$i]['CustomerRecordID']				=	html_entity_decode($slip_info[0]['CustomerRecordID']);
				$sync_slips[$i]['EmployeeCardID']				=	$slip_info[0]['EmployeeCardID'];
				$sync_slips[$i]['EmployeeCompanyOrLastName']	=	$slip_info[0]['EmployeeCompanyOrLastName'];//$emp_last_name;//$slip_info[0]['EmployeeFirstName'];
				$sync_slips[$i]['EmployeeFirstName']			=	$slip_info[0]['EmployeeFirstName'];//""
				$sync_slips[$i]['EmployeeRecordID']				=	$slip_info[0]['EmployeeRecordID'];
				$sync_slips[$i]['JobNumber']					=	html_entity_decode($slip_info[0]['JobNumber']);
				$sync_slips[$i]['Notes']						=	$slip_info[0]['Notes'];
				$sync_slips[$i]['Rate']							=	$slip_info[0]['Rate'];
				$sync_slips[$i]['SlipDate']						=	$slip_date;
				$sync_slips[$i]['SlipIDNumber']					=	$slip_info[0]['SlipIDNumber'];
				$sync_slips[$i]['Units']						=	$slip_info[0]['Units'];
				$sync_slips[$i]['ThirdPartyUniqueID']			=	$slip_info[0]['RecordID'];
				if(!empty($slip_info[0]['PayrollCategory']))
				{
					$sync_slips[$i]['PayrollCategory']			=	$slip_info[0]['PayrollCategory'];
				}
				$sync_slips[$i]['RecordID']						=	$slip_info[0]['RecordID'];
				/****************************/
			}
			catch (Exception $e){
				die($e->getMessage());
				throw new Exception("Error while updating the sync file.");
			}
			$sql       =	"INSERT INTO aed_updated_files (thirdparty_id, company_id)
							 VALUES ('".$sync_slips[$i]['RecordID']."','".$_SESSION['company_id']."')";
		
			$aedresult = $this->_db->query(Database::INSERT, $sql, False);
		}
		$sql	=	"INSERT INTO sync_process (company_id)
					 VALUES ('".$_SESSION['company_id']."') 
					";
		$qresult=	$this->_db->query(Database::INSERT, $sql, False);
		$activity_file		=	"ActivitySlips".$qresult[0].".json";
		$json->file_content	=	$sync_slips;
		
		
		return $json->insert_array(DROPBOX_ACTIVITY_FOLDERPATH.$activity_file,
									CACHE_DROPBOXFOLDERPATH."ActivitySlips".$_SESSION['employee_id'].".json");
		if(!$sync_status)
			throw new Exception("2");
	}	
}