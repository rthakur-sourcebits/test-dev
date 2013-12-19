<?php
defined('SYSPATH') or die('No direct script access.');
/*
 * 	@File			: activitysheet.php Model
 * 	@Class			: Model_Activitysheet
 * 	@Created date	: 30/Aug/2010
 * 	@Descritpion	: Activity model which reads the user activity table and return back to the Controller.
 */
class Model_Activitysheet extends Model {

	/*
	 * @Function	: __construct
	 * @Description	: loads the id value.
	 */
	public function __construct($id = NULL)
	{
		// load database library into $this->db (can be omitted if not required)
		parent::__construct($id);
	}
	/*
	 * 	@Function 		: read_activity_log()
	 * 	@Description	: Read activity table and get all the user activities.
	 */
	public function read_activity_log($limit=0)
	{
		$user_id				=	$_SESSION['employee_id'];
		$jsonObj				=   new Model_json;
		$activity				=	new Model_Activity;
		try{
			// Check visibility of activities, 0 : view unsynced activities,1: view synced, 2: view both
			switch($_SESSION['synced_slips_view'])
			{
				case 0: $where_sync_view	= " AND sync_status = 0 ";
						break;
				case 1: $where_sync_view	= " AND sync_status = 1 ";
						break;
				case 2: $where_sync_view	= "";
						break;
			}
			if(isset($_POST['act_search'])) 
			{	
				$search_val	=	addslashes(trim(htmlentities($_POST['act_search'])));
				if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
						if(isset($_SESSION['selected_emp_id'])){
							$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
						} else{
							$emp_cond	=	"";
						}
					$sel_query	=	"SELECT * 
									FROM activity_slip_lists 
									WHERE company_id = '".$_SESSION['company_id']."'
									$emp_cond
									AND Units != 0
									$where_sync_view
									AND 
									(ActivityID LIKE '%".$search_val."%' OR
									CustomerCompanyOrLastName LIKE '%".$search_val."%' OR
									JobNumber LIKE '%".$search_val."%' OR
									SlipIDNumber LIKE '%".$search_val."%' OR
									PayrollCategory LIKE '%".$search_val."%' OR
									Units LIKE '%".$search_val."%' OR
									Notes LIKE '%".$search_val."%'
									)
									ORDER BY SlipDate DESC";
				} else {
					$sel_query	=	"SELECT * 
									 FROM activity_slip_lists 
									 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."' 
									 AND company_id = '".$_SESSION['company_id']."'
									 AND is_admin = '".$_SESSION['admin_user']."' 
									 AND Units != 0
									 $where_sync_view
									 AND 
									 (ActivityID LIKE '%".$search_val."%' OR
									  CustomerCompanyOrLastName LIKE '%".$search_val."%' OR
									  JobNumber LIKE '%".$search_val."%' OR
									  SlipIDNumber LIKE '%".$search_val."%' OR
									  PayrollCategory LIKE '%".$search_val."%' OR
									  Units LIKE '%".$search_val."%' OR
									  Notes LIKE '%".$search_val."%'
									 )
									 ORDER BY SlipDate DESC";
				}
			} else {
				try{
					if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
						if(isset($_SESSION['selected_emp_id'])){
							$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
						} else{
							$emp_cond	=	"";
						}
						$sel_query	=	"SELECT *
										 FROM activity_slip_lists 
										 WHERE company_id = '".$_SESSION['company_id']."'
										 $emp_cond
										 AND Units != 0
										 $where_sync_view
										 ORDER BY SlipDate DESC";
					}else {
							switch($_SESSION['synced_slips_view']){
							case 0: $admin_check	= " AND is_admin = '".$_SESSION['admin_user']."'";
									break;
							case 1: $admin_check	= "";
									break;
							case 2: $admin_check	= "";
									break;
						}
						$sel_query	=	"SELECT * 
										 FROM activity_slip_lists 
										 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."'
										 $admin_check 
										 AND company_id = '".$_SESSION['company_id']."'
										 AND Units != 0
										 $where_sync_view
										 ORDER BY SlipDate DESC";
					}
				}catch(Exception $e){
					die($e->getMessage());
				}
			}
			$start		=	isset($_POST['page'])?$_POST['page']:0;
			$per_page	=	!empty($_POST['view_per_page'])?$_POST['view_per_page']:10;
			
			if(!empty($_POST['view_per_page']) && $limit == 1) {
				$start	=	$start*$per_page;
				$sel_query	.=	" LIMIT $start, ".$per_page;
			} else if($limit == 1) {
				$sel_query	.=	" LIMIT $start, 10";
			}
			$query		=	DB::query(Database::SELECT, $sel_query);
			$order_slips=	$query->execute()->as_array();
		
		// display how many activities needs to be synced, this message will displayed in activity sheet page
		$activities_synced	=  $this->get_synced_slips();	
		$arr_slips	= $jsonObj->join($order_slips, array(0=> array('0'  => 'ActivityID',
													   '1'  => $activity->get_activities_by_tt(),
													   '2' 	=> 'Activities'		
													   ),
											 1=> array('0'  => 'JobNumber',
													   '1'  => $activity->get_jobs(),
													   '2' 	=> 'Jobs'		
													   ),
											 2=> array('0'  => 'RecordID',
													   '1'  => $activities_synced,
													   '2' 	=> 'ActivitySync'		
													   )
											));
		return $arr_slips;
		}catch(Exception $e) { die($e->getFile());}
	}
	
	/*
	 * @Function : date_search_activity
	 * @Param1	  :	Starting date
	 * @Param2	  :	End date
	 * @Return   :	Activities between to date and from date are returned.
	 */
	public function date_search_activity($from_date, $to_date)
	{
		$select				=   new Model_json;
		
		if	($from_date == "")
		{
			throw new Exception("From date field should not be null.");
		}
		
		if	($to_date == "")
		{
			throw new Exception("To date field should not be null.");
		}

		if	($from_date > $to_date)
		{
			throw new Exception("Invalid date format. From date always less than To date");
		}
		
		$from_date_array	=	explode('-', $from_date); // [0]=> year, [1]=> month, [2]=>day
		$to_date_array		=	explode('-', $to_date);
		$content  			= 	$this->read_activity_log();
	
		return $select->between($content, 'SlipDate', 'date', 
								mktime('0','0','0',$from_date_array[1],$from_date_array[2],$from_date_array[0]),
								mktime('0','0','0',$to_date_array[1],$to_date_array[2],$to_date_array[0]));
	}
	
	/*
	 * @Function		:	get_activity_slip_details()
	 * @Pparam1			:	Activity slip id
	 * @Return			:	Activity details
	 * @Description		:	Gets all the details belongs to that activity.
	 */
	public function get_activity_slip_details($auto_id)
	{
		$dropbox	=   new Model_dropbox;	
		$jsonObj	=   new Model_json;	
		$activity	=	new Model_Activity;
		
		// type of activity display
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
			//$this->show_rate($auto_id);
			if(isset($_SESSION['selected_emp_id'])){
				$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
			} else{
				$emp_cond	=	"";
			} 
			$query		=	"SELECT *
							 FROM activity_slip_lists
							 WHERE company_id = '".$_SESSION['company_id']."'
							 $emp_cond
							 $where_sync_view
							 ORDER BY SlipDate DESC
							 ";
			$query		=	DB::query(Database::SELECT, $query);
			$all_slips	=	$query->execute()->as_array();
			
			$query		=	"SELECT *
							 FROM activity_slip_lists
							 WHERE RecordID = '".$auto_id."'
							 $emp_cond
							 AND company_id = '".$_SESSION['company_id']."'";
			$query		=	DB::query(Database::SELECT, $query);
			$data		=	$query->execute()->as_array();
			
			
		}else{
			$query		=	"SELECT *
							 FROM activity_slip_lists
							 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."'
							 AND company_id = '".$_SESSION['company_id']."'
							 AND is_admin = '".$_SESSION['admin_user']."'
							 $where_sync_view
							 ORDER BY SlipDate DESC
							 ";
			$query		=	DB::query(Database::SELECT, $query);
			$all_slips	=	$query->execute()->as_array();
			
			$query		=	"SELECT *
							 FROM activity_slip_lists
							 WHERE RecordID = '".$auto_id."'
							 AND EmployeeRecordID = '".$_SESSION['employee_id']."'
							 AND company_id = '".$_SESSION['company_id']."'";
			$query		=	DB::query(Database::SELECT, $query);
			$data		=	$query->execute()->as_array();
		}
		if($data)
		{
			$activities_synced	=  $this->get_synced_slips();	
			$content	=	$jsonObj->join($data, array(0=> array('0'  => 'ActivityID',
													   '1'  => $activity->get_activities_by_tt(),
													   '2' 	=> 'Activities'		
													   ),
													 1=> array('0'  => 'JobNumber',
													   '1'  => $activity->get_jobs(),
													   '2' 	=> 'Jobs'		
													   ),
													 2=> array('0'  => 'RecordID',
													   '1'  => $activities_synced,
													   '2' 	=> 'ActivitySync'		
													   )));
		}
		else {
			return;
		}
		// below code for finding next and prev pagination in activity view page
		foreach ($content as $key=>$arr)
		{
			$content =	$arr;
		}
		$data_new	=	array_merge(array(),$all_slips);
		$count		=	count($all_slips);
		if($count == 1)	{
			$next	=	0;
			$prev	=	0;
		} else {
			for ($i=0; $i<$count; $i++)
			{
				if ($data_new[$i]['RecordID'] == $auto_id){
					if ($i == 0){
						$prev = 0;
						$next = $data_new[$i+1]['RecordID'];
					} elseif ($i == $count-1){
						$prev = $data_new[$i-1]['RecordID'];
						$next = 0;  
					} else{
						$prev = $data_new[$i-1]['RecordID'];
						$next = $data_new[$i+1]['RecordID'];
					}
				}
			}
		}
		$all_data =  array(	'content' 	=>	$content,
							'prev'		=>	$prev,	
							'next'		=>	$next	
							);
							
		return $all_data;
	}
	
	/*
	 * 	@Function		:	update_slips()
	 * 	@Param1			:	Selected slips if user clicked Sync else null
	 * 	@Description	:	Sync selected or all slips.
	 */
	public function sync_activity_slips()
	{ 
		$json			=   new Model_json;
		$dropbox		=   new Model_dropbox;
		$query_slip_id	=	"";
		$sync_status	=	false;
		$sync_slips		=	array();
		try{
			if(isset($_POST['sync'])){
				if(empty($_POST['slip_id'])){
					throw new Exception(Kohana::message('error', 'select_activity'));
				}
				$arr_slips[]		=	$_POST['slip_id'];
			} elseif (isset($_POST['syncall'])) {	// getting all activity slip ids from activity slips.
				if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
					if(isset($_SESSION['selected_emp_id'])){
						$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
					} else{
						$emp_cond	=	"AND is_admin = '".$_SESSION['admin_user']."'";
					} 
					$sql		=	"SELECT RecordID
									 FROM activity_slip_lists
									 WHERE company_id = '".$_SESSION['company_id']."'
									 $emp_cond
									 AND Units != 0
									 AND sync_status = 0";
				}else{
					$sql		=	"SELECT RecordID
									 FROM activity_slip_lists
									 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."'
									 AND company_id = '".$_SESSION['company_id']."'
									 AND is_admin = '".$_SESSION['admin_user']."'
									 AND Units != 0
									 AND sync_status = 0";
				}
				$data		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
				foreach($data as $slip_recordid){
					$arr_slips[] = 	$slip_recordid['RecordID'];
				}
			}
		} catch(Exception $e){
			die($e->getMessage());
		}

		$json->file_content	=	'';	// empty the object.
		if (empty($arr_slips)) {
			throw new Exception(Kohana::message('error', 'select_activity'));
		}
		$slip_count			=	count($arr_slips);		
		
		// read each slips and sync it to dropbox	
		for($i=0;$i<$slip_count;$i++)	{//($arr_slips as $slip_id)
			$result =	1;//$this->check_synced_slip($slip_id); 
			try{
				if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='1'){
					$sql		=	"UPDATE activity_slip_lists
									SET sync_status = 1,
							 	 	sync_date = now()
								 	WHERE RecordID = '".$arr_slips[$i]."'
								 	AND company_id = '".$_SESSION['company_id']."'";
				}else{
					$sql		=	"UPDATE activity_slip_lists
									SET sync_status = 1,
							 		sync_date = now()
									WHERE RecordID = '".$arr_slips[$i]."'".
									//AND EmployeeRecordID = '".$_SESSION['employee_id']."'
									"AND company_id = '".$_SESSION['company_id']."'";
				}	
				$this->_db->query(Database::INSERT, $sql, False);
				
				$sql			=	"INSERT INTO activities_sync (RecordID, company_id, SyncDate)
									VALUES ('".$arr_slips[$i]."','".$_SESSION['company_id']."', now())";
				
				$qresult		=	$this->_db->query(Database::INSERT, $sql, False);
				$sync_status	=	true;
				
				/*********Create json file for syncing process*****************/
				$sql			=	"SELECT *
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
				$sync_slips[$i]['EmployeeCompanyOrLastName']	=	$slip_info[0]['EmployeeCompanyOrLastName'];//$slip_info[0]['EmployeeFirstName'];//$emp_last_name;
				$sync_slips[$i]['EmployeeFirstName']			=	$slip_info[0]['EmployeeFirstName'];
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
				
				throw new Exception("Error while updating the sync file.");
			}
			$sql       =	"INSERT INTO aed_updated_files (thirdparty_id, company_id)
							 VALUES ('".$sync_slips[$i]['RecordID']."','".$_SESSION['company_id']."')";
		
			$aedresult = $this->_db->query(Database::INSERT, $sql, False);	
		}
		
		// update sync status in the table sync_process
		$sql				=	"INSERT INTO sync_process (company_id) VALUES ('".$_SESSION['company_id']."')";
		$qresult			=	$this->_db->query(Database::INSERT, $sql, False);
		
		$activity_file		=	"ActivitySlips".$qresult[0].".json";
		$json->file_content	=	$sync_slips;

		// function to sync file to dropbox
		return $json->insert_array(DROPBOX_ACTIVITY_FOLDERPATH.$activity_file,
									CACHE_DROPBOXFOLDERPATH."ActivitySlips".$_SESSION['employee_id'].".json");
		if(!$sync_status)
			throw new Exception(Kohana::message('error', 'sync_no_activity'));							 

	}
	/*
	 * 	@Function		:	get_unsynced_count()
	 * 	@Description	:	get Unsynced slips list. 
	 */	
	public function get_unsynced_count(){
		$json				=   new Model_json;
		$activity_list		=	$this->read_activity_log(0);
		$unsynced_activity	=	array();
		
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='0'){
			if(isset($_SESSION['selected_emp_id'])){
				$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
			} else{
				$emp_cond	=	"";
			}
			$sql	=	"SELECT *
						 FROM activity_slip_lists
						 WHERE company_id = '".$_SESSION['company_id']."'
						 $emp_cond
						 AND Units != 0
						 AND sync_status = 0";
		} else{
			$sql	=	"SELECT *
						 FROM activity_slip_lists
						 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."'
						 AND company_id = '".$_SESSION['company_id']."'
						 AND is_admin = '".$_SESSION['admin_user']."'
						 AND Units != 0
						 AND sync_status = 0";
		}
		$unsynced_activity		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		
		if(!empty($unsynced_activity))
		{		
			$unsynced_activity_count	=	count($unsynced_activity);
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user']=='0'){
				if(isset($_SESSION['selected_emp_id'])){
					$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
				} else{
					$emp_cond	=	"";
				}
				$sql	=	"SELECT *
							 FROM activity_slip_lists
							 WHERE company_id = '".$_SESSION['company_id']."'
							 $emp_cond
							 AND Units != 0
							 AND sync_status = 0
							 AND Is_non_hourly = 0
							 GROUP BY ActivityID, CustomerCompanyOrLastName, Notes, JobNumber,PayrollCategory";
			}else{
				$sql	=	"SELECT *
							 FROM activity_slip_lists
							 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."'
							 AND company_id = '".$_SESSION['company_id']."'
							 AND is_admin = '".$_SESSION['admin_user']."'
							 AND Units != 0
							 AND sync_status = 0
							 AND Is_non_hourly = 0
							 GROUP BY ActivityID, CustomerCompanyOrLastName, Notes, JobNumber,PayrollCategory";
			}
			
			$timesheet_unsynced			=	DB::query(Database::SELECT, $sql)->execute()->as_array();
			
			//$arr_group_slips			= 	$json->group_by($unsynced_activity, array("ActivityID", "JobNumber", "Notes", "CustomerCompanyOrLastName"));
			$unsynced_timesheet_count	=	count($timesheet_unsynced);	 
			$count						=	array(
													'timesheet_usync_count'	=>	$unsynced_timesheet_count,
													'activity_unsync_count'	=>	$unsynced_activity_count
												);
		}else{
			$count						=	array(
													'timesheet_usync_count'	=>	0,
													'activity_unsync_count'	=>	0
												);
		}
		return $count;
	}
	/*
	 * 	@Function		:	get_synced_slips()
	 * 	@Description	:	get Unsynced slips list. 
	 */	
	public function get_synced_slips()
	{
		$query	=	"SELECT 
					DATE_FORMAT(SyncDate, '%M %d, %Y') as SyncDate,RecordID,company_id 
					FROM activities_sync
					WHERE company_id = '".$_SESSION['company_id']."'";
		$query	=	DB::query(Database::SELECT, $query);
		$data	=	$query->execute()->as_array();
		return $data;
	}
	
	/*
	 * 	@Function		:	check_synced_slip()
	 * 	@Description	:	check the id wheather slip is synced or not. 
	 */	
	public function check_synced_slip($slipID)
	{
		$query	=	"SELECT * 
					 FROM activities_sync 
					 WHERE RecordID = '".$slipID."'	AND company_id = '".$_SESSION['company_id']."'";
		$query	=	DB::query(Database::SELECT, $query);
		$data	=	$query->execute()->as_array();
		return $data;
	}
	
	/**
 	 * @description	: Checks for activity slips handshake files. If found then update database and delete it. 
	 */
	public function check_handshake_updates()
	{
		$dropbox		=   new Model_dropbox;
		try {
			$metadata_info	=	$dropbox->getmetadata(DROPBOXFOLDERPATH."DesktopChanges/Handshake"); // fetch file from dropbox and find previously updated status
		} catch(Exception $e) {
			return true;
		}
		$files_count	=	count($metadata_info['contents']);
		try{
			for($i=0;$i<$files_count;$i++) // check in all the files
			{
				$file_name		=	$metadata_info['contents'][$i]['path'];
				$filepath 		=	str_replace("%2F", "/", rawurlencode($file_name));
				$fcontent		=	$dropbox->get_file($filepath);
				$field_count	=	count($fcontent);
				for($j=0;$j<$field_count;$j++)
				{
					$record_id	=	$fcontent[$j]['RecordID'];
					$unique_id	=	$fcontent[$j]['ThirdPartyUniqueID'];
					
					// if ThirdPartyUniqueID found in json file then updated that entry in table aed_updated_files, means that json file synced to AED
					$sql	=	"UPDATE aed_updated_files
								 SET aed_record_id = '".$record_id."',
								 aed_sync_status   = 1
								 WHERE thirdparty_id = '".$unique_id."'
								 ";
					$qresult=	$this->_db->query(Database::INSERT, $sql, False);
				}
				$dropbox->delete_file($file_name); // remove this file from dropbox
			}
		}catch(Exception $e){
			die($e->getMessage());
		}
	}
	
	
	// Function to get slips by ajax call
// Paginated customers
	public function paginated_slips($start, $end) {
		switch($_SESSION['synced_slips_view'])
		{
			case 0: $where_sync_view	= " AND sync_status = 0 ";
					break;
			case 1: $where_sync_view	= " AND sync_status = 1 ";
					break;
			case 2: $where_sync_view	= "";
					break;
		}
		if(isset($_POST['act_search'])) {
			$search_val	=	addslashes(trim(htmlentities($_POST['act_search'])));
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
				if(isset($_SESSION['selected_emp_id'])){
					$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
				} else{
					$emp_cond	=	"";
				}
					$sel_query	=	"SELECT * 
									FROM activity_slip_lists 
									WHERE company_id = '".$_SESSION['company_id']."'
									$emp_cond
									AND Units != 0
									$where_sync_view
									AND 
									(ActivityID LIKE '%".$search_val."%' OR
									 CustomerCompanyOrLastName LIKE '%".$search_val."%' OR
									 JobNumber LIKE '%".$search_val."%' OR
									 SlipIDNumber LIKE '%".$search_val."%' OR
									 PayrollCategory LIKE '%".$search_val."%' OR
									 Units LIKE '%".$search_val."%' OR
									 Notes LIKE '%".$search_val."%'
									)
									ORDER BY SlipDate DESC
									LIMIT $start, $end";
			} else {
					$sel_query	=	"SELECT * 
									FROM activity_slip_lists 
									WHERE EmployeeRecordID = '".$_SESSION['employee_id']."' 
									AND company_id = '".$_SESSION['company_id']."'
									AND is_admin = '".$_SESSION['admin_user']."' 
									AND Units != 0
									$where_sync_view
									AND 
									(ActivityID LIKE '%".$search_val."%' OR
									 CustomerCompanyOrLastName LIKE '%".$search_val."%' OR
									 JobNumber LIKE '%".$search_val."%' OR
									 SlipIDNumber LIKE '%".$search_val."%' OR
									 PayrollCategory LIKE '%".$search_val."%' OR
									 Units LIKE '%".$search_val."%' OR
									 Notes LIKE '%".$search_val."%'
									)
									ORDER BY SlipDate DESC
									LIMIT $start, $end";	
			}
		} else {
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
				if(isset($_SESSION['selected_emp_id'])){
					$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
				} else{
					$emp_cond	=	"";
				} 
				$sel_query	=	"SELECT *
								 FROM activity_slip_lists 
								 WHERE company_id = '".$_SESSION['company_id']."'
								 $emp_cond
								 AND Units != 0
								 $where_sync_view
								 ORDER BY SlipDate DESC
								 LIMIT $start, $end";
			}else {
				$sel_query	=	"SELECT * 
								 FROM activity_slip_lists 
								 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."' 
								 AND company_id = '".$_SESSION['company_id']."'
								 AND Units != 0
								 $where_sync_view
								 ORDER BY SlipDate DESC
								 LIMIT $start, $end";
			}
		}
		$result	=	DB::query(Database::SELECT, $sel_query)->execute()->as_array();
		return $result;
	}
	
	// Get total customers
	public function total_slips() {
		switch($_SESSION['synced_slips_view'])
		{
			case 0: $where_sync_view	= " AND sync_status = 0 ";
					break;
			case 1: $where_sync_view	= " AND sync_status = 1 ";
					break;
			case 2: $where_sync_view	= "";
					break;
		}
		if(isset($_POST['act_search'])) {
			$search_val	=	addslashes(trim(htmlentities($_POST['act_search'])));
			if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
				if(isset($_SESSION['selected_emp_id'])){
					$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
				} else{
					$emp_cond	=	"";
				} 
					$sel_query	=	"SELECT * 
									FROM activity_slip_lists 
									WHERE company_id = '".$_SESSION['company_id']."'
									$emp_cond
									AND Units != 0
									$where_sync_view
									AND 
									(ActivityID LIKE '%".$search_val."%' OR
									 CustomerCompanyOrLastName LIKE '%".$search_val."%' OR
									 JobNumber LIKE '%".$search_val."%' OR
									 SlipIDNumber LIKE '%".$search_val."%' OR
									 PayrollCategory LIKE '%".$search_val."%' OR
									 Units LIKE '%".$search_val."%' OR
									 Notes LIKE '%".$search_val."%'
									)
									ORDER BY SlipDate DESC";
			} else {
					$sel_query	=	"SELECT * 
									 FROM activity_slip_lists 
									 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."' 
									 AND company_id = '".$_SESSION['company_id']."'
									 AND is_admin = '".$_SESSION['admin_user']."' 
									 AND Units != 0
									 $where_sync_view
									 AND 
									 (ActivityID LIKE '%".$search_val."%' OR
									  CustomerCompanyOrLastName LIKE '%".$search_val."%' OR
									  JobNumber LIKE '%".$search_val."%' OR
									  SlipIDNumber LIKE '%".$search_val."%' OR
									  PayrollCategory LIKE '%".$search_val."%' OR
									  Units LIKE '%".$search_val."%' OR
									  Notes LIKE '%".$search_val."%'
									 )
									 ORDER BY SlipDate DESC";	
				}
			} else {
				if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
					if(isset($_SESSION['selected_emp_id'])){
						$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
					} else{
						$emp_cond	=	"";
					} 
					$sel_query		=	"SELECT *
										 FROM activity_slip_lists 
										 WHERE company_id = '".$_SESSION['company_id']."'
										 $emp_cond
										 AND Units != 0
									 	 $where_sync_view
									 	 ORDER BY SlipDate DESC";
				}else {
					$sel_query		=	"SELECT * 
										 FROM activity_slip_lists 
										 WHERE EmployeeRecordID = '".$_SESSION['employee_id']."' 
										 AND company_id = '".$_SESSION['company_id']."'
										 AND is_admin = '".$_SESSION['admin_user']."'
										 AND Units != 0
										 $where_sync_view
										 ORDER BY SlipDate DESC";
				}
			}
		$result	=	DB::query(Database::SELECT, $sel_query)->execute()->as_array();
		return $result[0]['total'];
	}
	

	// Get total customer pages
	public function total_slips_pages() {
		if(isset($_SESSION['admin_user']) && $_SESSION['admin_user'] == "1") {
			if(isset($_SESSION['selected_emp_id'])){
				$emp_cond	=	"AND EmployeeRecordID = '".$_SESSION['selected_emp_id']."'";
			} else{
				$emp_cond	=	"";
			}
			$sql	=	"SELECT count(*) as total 
						 FROM customers 
						 WHERE company_id = '".$_SESSION['company_id']."' 
						 $emp_cond 
						 AND status = '1'
						";
		} else {
			$sql	=	"SELECT count(*) as total 
			 			 FROM customers 
			 			 WHERE company_id = '".$_SESSION['company_id']."' 
			 			 AND (employee_id = '".$_SESSION['employee_id']."' || employee_id = '0') 
			 			 AND status = '1'
			 			";
		}
		$result			=	DB::query(Database::SELECT, $sql)->execute()->as_array();
		if(empty($result)){
			return 0;
		}else{
			$page_count		=	intval($result[0]['total'])/10;
			if($result[0]['total']%10 != 0) 
				$page_count	+=	1;
			return $page_count;	
		}
		
	}
	
	/**
	 * @Method		:	show_rate
	 * @Description	:	Whether to show the rate to the employee or not.
	 * @Param		:	int
	 * @Return		:	not returning	
	 */
	/*
	private function show_rate($id){
			try{	
				$sql		=	"SELECT du.display_rate 
								 FROM dharma_users du, activity_slip_lists asl 
								 WHERE du.company_id = asl.company_id 
								 AND du.record_id = asl.EmployeeRecordID 
								 AND asl.RecordID = '".$id."' 
								";
				$result		=	DB::query(Database::SELECT, $sql)->execute()->as_array();
				if(empty($result)){
						//not yet decided what need to be shown.
				}else{
					if(isset($_SESSION['display_rate'])){
						$_SESSION['display_rate']	=	$result[0]['display_rate'];
					}
				}
			} catch(Exception $e){
				die($e->getMessage());
			}
		}*/
	
	/* End of class*/
}