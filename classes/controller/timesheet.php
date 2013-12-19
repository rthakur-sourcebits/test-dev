<?php 
defined('SYSPATH') or die('No direct script access.');
/**
 * @File 		: timesheet.php Controller
 * @Class		: Controller_Timesheet
 * @Created Date: 30/Aug/2010
 * @Description	: Create the user timesheet of one week.
 * Copyright (c) 2011 Acclivity Group LLC
*/

class Controller_Timesheet extends Controller_Template 
{

	public $template = 'template';
    public $session  = '';
	/*
	 * @Method: __construct 
	 * @Description: This method calls the calidation session and checks user is loged in or not
	 * 				 if the user is not logged in he will redirected to login page. 
	 */
	public function __construct(Request $request)
	{
		parent::__construct($request);
		$validations	= 	new Model_validations;
		$validations->check_user_session();	
	}
	
	/**
	 * @Access		:	public
	 * @Function 	:	action_index();
	 * @Descritpion : 	Get user activity list from model and pass to the view file.
	 * @Return 	 	:	Array contains list of timesheet log between the input dates.
	 */
	public function action_index()
	{ 	
		try {
		$this->template->title	= 	"Dharma Timesheet";
		$timesheet				=	new Model_Timesheet;
		$activity_model			=	new Model_activity;
		$data_model				=	new Model_data;
		$dharmausers_m			=	new Model_Dharmausers;
		$arr_week_date			=	array();

		// calculate week start month
		if(isset($_POST['week_list'])) //check whether user selected dates from the week list icon
		{
			$timestamp_start	=	mktime(0, 0, 0, date('m', strtotime($_POST['timesheet_date'])), date('d', strtotime($_POST['timesheet_date'])) - date('w', strtotime($_POST['timesheet_date']))+1, date('Y', strtotime($_POST['timesheet_date'])));
			unset($_SESSION['week_start_date']);
		}
		elseif(empty($_POST['change_week'])) 
		{
			if(!empty($_SESSION['week_start_date'])) 
			{
				$week_start_time	=	$_SESSION['week_start_date'];
				$timestamp_start	=	$week_start_time;//mktime(0, 0, 0, date('m', $week_start_time), date('d', $week_start_time) - date('w', $week_start_time)+1, date('Y', $week_start_time));
			}
			else
			{
				$timestamp_start	=	mktime(0, 0, 0, date('m'), date('d') - date('w')+1, date('Y'));
			}
		}
		else 
		{
			$arr_week_date		=	empty($_POST['prev_button'])?explode(":",$_POST['next']):explode(":",$_POST['prev']);
			$timestamp_start	=	$arr_week_date[0];
			unset($_SESSION['week_start_date']);
		}
		$start_month			=	date('M', $timestamp_start);
		$start_year				=	date('Y', $timestamp_start);
		
		// calculate week end month
		if(isset($_POST['week_list']))  //check whether user selected dates from the week list icon
		{
			$timestamp_end				=	mktime(0, 0, 0, date('m', strtotime($_POST['timesheet_date'])), date('d', strtotime($_POST['timesheet_date'])) - date('w', strtotime($_POST['timesheet_date']))+7, date('Y', strtotime($_POST['timesheet_date'])));
		}
		elseif(empty($_POST['change_week'])) 
		{
			if(!empty($_SESSION['week_start_date'])) 
			{
				//$timestamp_start	=	mktime(0, 0, 0, date('m', $week_start_time), date('d', $week_start_time) - date('w', $week_start_time)+1, date('Y', $week_start_time));
				$timestamp_end		=	mktime(0, 0, 0, date('m', $week_start_time), date('d', $week_start_time) - date('w', $week_start_time)+7, date('Y', $week_start_time));
			}
			else
			{
				$timestamp_end					=	mktime(0, 0, 0, date('m'), date('d') - date('w')+7, date('Y'));
			}
		}
		else 
		{
			$timestamp_end	=	$arr_week_date[1];
		}
		$end_month							=	date('M', $timestamp_end);
		$end_year							=	date('Y', $timestamp_end);

		$timesheet_list['months']['start'] 	=   $start_month;
		$timesheet_list['months']['end'] 	=   $end_month;  
		if($start_year == $end_year) 
		{
			$timesheet_list['year']		=	$end_year;
		} 
		else 
		{
			$timesheet_list['year']		=	$start_year.",&nbsp;".$end_year;
		}
		if(isset($_POST['week_list']) || !empty($_SESSION['week_start_date']))  //check whether user selected dates from the week list icon
		{
			$d	=	date('d', $timestamp_start);
			$m	=	date('m', $timestamp_start);
			$w	=	date('w', $timestamp_start);
			$y	=	date('Y', $timestamp_start);
		}
		elseif(empty($_POST['change_week'])) 
		{
			$d	=	date('d');
			$m	=	date('m');
			$w	=	date('w');
			$y	=	date('Y');
		}
		else 
		{
			$d	=	date('d', $timestamp_start);
			$m	=	date('m', $timestamp_start);
			$w	=	date('w', $timestamp_start);
			$y	=	date('Y', $timestamp_start);
		}
		// calculate current week dates
		for($i=1;$i<=7;$i++) 	//making current week dates from Sunday to Saturday
		{ 
			$timestamp = mktime(0, 0, 0, $m, $d - $w+$i, $y);
			$timesheet_list['dates'][$i] 		=   date('j', $timestamp);
			$timesheet_list['days'][$i] 		=   date('Y-m-d', $timestamp);
		}
		// calculate next and prev week's start and end days
		$timesheet_list['next_start_week']	=	mktime(0, 0, 0, $m, $d - $w+8, $y);
		$timesheet_list['next_end_week']	=	mktime(0, 0, 0, $m, $d - $w+14, $y);
		$timesheet_list['prev_start_week']	=	mktime(0, 0, 0, $m, $d - $w-6, $y);
		$timesheet_list['prev_end_week']	=	mktime(0, 0, 0, $m, $d - $w, $y);
		//try {
		
		$timesheet_list['timesheet_list']	=	$timesheet->read_timesheet_log($timestamp_start,$timestamp_end);
		$timesheet->start_time				=	$timestamp_start;
		$timesheet->end_time				=	$timestamp_end;
		$timesheet_list['Model_timesheet']	=	$timesheet;
		
		$data_list 	= array('customers'	=> $activity_model->get_customers(),
							'activities'=> $activity_model->get_activities_by_tt(1),
							'jobs'		=> $activity_model->get_jobs()
							);
		$data_names = $data_model->auto_list($data_list);
		//success and error messages
		$timesheet_list['messages'][1]	=	"Selected activities has been synced";
		$timesheet_list['messages'][2]	=	"All activities of this user has beed synced.";
		$timesheet_list['messages'][3]	=	"Error! Select any activity.";
		
		$this->template->content 			= 	View::factory('timesheet/list',$timesheet_list)
												->set( 'data_names', $data_names)
												//->set('payroll', $activity_model->get_payroll())
												->set( 'employee_list', $dharmausers_m->get_all_employees())
												->set('payroll_flag', $dharmausers_m->get_payroll_flag())
												->set('week_start_date', $timestamp_start);
	} catch(Exception $e) {die($e->getMessage());}
	}
	
	/**
	 * @Access		:	Public
	 * @Function	:	action_create();
	 * @Description	:	Create a new weekly timesheet in timesheet display page.
	 */
	public function action_create()
	{
		try {
		$activity_model =	new Model_activity;
		$validations	=	new Model_validations;
		$json			=	new Model_json;
		$dropbox		=	new Model_dropbox;
		$data			=	new Model_data;
		$statusMessage	=	''; 
		$timesheet_data	=	array();
		$units_flag		= 	0;
		$record_id 		=	0;
		$r_index		=	$_POST['timesheet_row_index'];

		for($i=1; $i<8; $i++)
		{
			$arr_customer	=	array();
			
			// check for each units/hours
			
			if(isset($_POST['unit_'.$i.'_'.$r_index]) AND $_POST['unit_'.$i.'_'.$r_index] != null AND $_POST['unit_'.$i.'_'.$r_index] != '' AND $_POST['unit_'.$i.'_'.$r_index]>=0)
			{
				$current_date	=	explode('-', $_POST['date_'.$i.'_'.$r_index]);			// getting the date field.	
				
				if(isset($_POST['slip_id_'.$i.'_'.$r_index]) AND $_POST['slip_id_'.$i.'_'.$r_index]!='' AND $_POST['slip_action_'.$i.'_'.$r_index] == 'update')
				{
		   			$up_record_id		=	$_POST['slip_id_'.$i.'_'.$r_index];
		   			$slip_number		=	$_POST['slip_number_'.$i.'_'.$r_index];
		   		}
		   		else
		   		{
		   			if(empty($record_id) || $record_id == 0)
		   			//$new_record_id		=	$activity_model->get_last_auto_id();
		   			$new_record_id		=	$activity_model->get_slip_auto_id();		   					   			
		   			$new_record_id		+=	1;
		   			$slip_number		=	$data->get_slip_number($new_record_id);	// create the slip number and add with form  				 
		   		}	
			   	
		   		$customer_name_field	=	$_POST['customer_name'.'_'.$r_index];
				$date_value				=	$current_date[0]."-".$current_date[1]."-".$current_date[2];
				
				if($_SESSION['admin_user'] == "1") {
					$emp_last_name		=	$_SESSION['selected_employee_lastname'];
					$emp_first_name		=	$_SESSION['selected_employee_name'];
					$emp_id				=	$_SESSION['selected_emp_id'];
				} else {
					$emp_last_name		=	$_SESSION['employee_lastname'];
					$emp_first_name		=	$_SESSION['employee_name'];
					$emp_id				=	$_SESSION['employee_id'];
				}
				// create array all entered value
				
				$params = array('employee_id'		=>	$emp_id,
								'employee_lastname'	=> 	$emp_last_name,
								'employee_name'		=>	$emp_first_name,
								'customer_id' 		=>	trim($_POST['customer'.'_'.$r_index]),
								'customer_name'		=>	trim(html_entity_decode($customer_name_field)),
								'slip_number' 		=>	$slip_number,	
								'activity_id'		=>	trim($_POST['activity'.'_'.$r_index]),
								'activity_name'		=>	trim($_POST['activity_name'.'_'.$r_index]),
								'job_id'	 		=>	trim($_POST['job'.'_'.$r_index]),
								'job_name'	 		=>	trim($_POST['job_name'.'_'.$r_index]),
								'payroll_id' 		=>	trim($_POST['payroll'.'_'.$r_index]),		
								'payroll_name' 		=>	isset($_POST['payroll_name'.'_'.$r_index])?trim($_POST['payroll_name'.'_'.$r_index]):'',		
								'is_non_hourly'		=>	$_POST['is_non_hourly'.'_'.$r_index],
								'units' 			=>	isset($_POST['unit_'.$i.'_'.$r_index])?$_POST['unit_'.$i.'_'.$r_index]:0,	
								'id'	 			=>	isset($_POST['slip_id_'.$i.'_'.$r_index])?$_POST['slip_id_'.$i.'_'.$r_index]:0,
								'rate' 				=>	$_POST['rate'.'_'.$r_index],
								'total' 			=>	isset($_POST['unit_'.$i.'_'.$r_index])?$_POST['unit_'.$i.'_'.$r_index]:0*$_POST['rate'.'_'.$r_index],
								'notes' 			=>	trim($_POST['notes'.'_'.$r_index]),
								'date' 				=>	$date_value,
								'RecordID'			=> 	$_POST['slip_action_'.$i.'_'.$r_index] == 'update'?$up_record_id:$new_record_id
								);	
				    // Validation of fields.	
				try
				{
					$validations->validate_timesheet($params); // validate all the fields
				}
				catch(Exception $e)
				{
					$statusMessage = $e->getMessage();	
					Request::instance()->redirect(SITEURL.'/timesheet');	
					return;	  
				}
					try
					{
						$timesheet_data[0]	=	$params;		
						
						if ($_POST['slip_action_'.$i.'_'.$r_index] == 'update')					// updating the timesheet.
						{
							$activity_model->update($timesheet_data,1); // if its update time
							$statusMessage = '2';
						}
						else																						// New Time sheet entery.
						{
							$result = $activity_model->insert($timesheet_data); // insert new time sheet
						}
					} 
					catch(Exception $e)
					{
						$statusMessage = $e->getMessage();
						Request::instance()->redirect(SITEURL.'/timesheet');
						return;
					}
				$units_flag++;
			}//die("here");
		}
		//$this->action_index($_POST['week_start_date'], $_POST['week_end_date']);
		//$_SESSION['week_start_date']	=
		
		$_SESSION['week_start_date']	=	$_POST['week_start_date'.'_'.$r_index];
		Request::instance()->redirect(SITEURL.'/timesheet');	
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
	
	/**
	 * @Access			:	Public
	 * @Function		:	action_sync
	 * @Description		:	Sync the selected timesheet to dropbox.
	 */
	public function action_sync()
	{
		$this->template->title			=	"Dharma Time Sheet";
		$time_sheet						=	new Model_Timesheet;
		$obj_user						=	new Model_User;
		$activity_sheet					=	new Model_Activitysheet;
		$handshake_list					=	$activity_sheet->check_handshake_updates(); // check for previously synced transaction status.
		$_SESSION['week_start_date']	=	$_POST['week_start_date_sync'];
		try { 
			if(isset($_POST['sync'])) // check if user clicked on sync
			{
				$time_sheet->sync_timesheets();
				$success_message	=	1;
			} elseif(isset($_POST['syncall'])) { // user clicked on syncall
				$time_sheet->sync_timesheets();
				$success_message	=	2;
			} else {
					Request::instance()->redirect(SITEURL."/timesheet");
			}
		}
		catch(Exception $e) {
			if($e->getMessage() == "1")
				Request::instance()->redirect(SITEURL."/timesheet?sync=3");
			elseif($e->getMessage() == "2")
				Request::instance()->redirect(SITEURL."/timesheet?sync=4");
		}
			$obj_user->create_customer_json_sessions();
			Request::instance()->redirect(SITEURL."/timesheet?sync=$success_message");
		
	}
	
	/*
	 * @Method: action_selected_employee
	 * @Description: This method is used to store the selected employee in session variable.
	 */
	public function action_selected_employee(){
		try{
			if(isset($_POST['selected_employee']) && $_POST['selected_employee']!=''){
				$split_name								=	explode(' ',$_POST['selected_employee']);
				$_SESSION['selected_employee_name']		= 	isset($split_name[0])?$split_name[0]:'';
				$_SESSION['selected_employee_lastname']	=	isset($split_name[1])?$split_name[1]:'';
				$_SESSION['selected_emp_id']			=	$_POST['employee_id'];
				$result[0]['error']						=	0;
				$result[0]['description']				=	'success';
			}
		}catch(Exception $e){
			$result[0]['error']			=	1;
			$result[0]['description']	=	$e->getMessage();
		}
		die(json_encode($result));
	}
}