<?php 
defined('SYSPATH') or die('No direct script access.');
/**
 * @File : activity.php Controller
 * @Class : Controller_Activity
 * @Created: 27-08-2010
 * @Modified: 24-10-2010
 * @Description: This class file holds the operations of new acticity, new activity save, activity update.
 * Copyright (c) 2011 Acclivity Group LLC 
 */
class Controller_Activity extends Controller_Template
{
	private $error_message = '';
	
	/**
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
	 * @Method: action_add 
	 * @Description: This method displays the activity create form.
	 */
	public function action_add($edit_flag = '', $slip_id = '')
	{	
		try{
			$activity_model 			= 	new Model_activity;
			$activity_sheet 			= 	new Model_activitysheet;
			$data_model 				= 	new Model_data;
			$json		 				= 	new Model_json;
			$dharmausers_m				=	new Model_Dharmausers;
			
			$this->template->title		= 	"New Activity Slip";
			$this->template->styles		=	array();
			$this->template->scripts	= 	array();
			$this->template->flag		= 	'add';
	    	$slip						=	'';	
	    	unset($_SESSION['week_start_date']);
			$last_auto_id				=	$activity_model->get_slip_auto_id();
			$last_auto_id				+=	1;
			
			if ($edit_flag == 'edit' && $slip_id !='')
			{ 
				$data 	 = $activity_sheet->get_activity_slip_details($slip_id);
				if($data['content']['sync_status'])
					Request::instance()->redirect(SITEURL.'/activitysheet');
				$slip[0] = $data['content'];
			}
			if(!empty($this->error_message) && $edit_flag != 'edit')
			{
				$slip[0]['CustomerCompanyOrLastName']			=	$_POST['customer_name'];
				$slip[0]['Activities']['ActivityName']			=	$_POST['activity_name'];
				$slip[0]['Activities']['IsActivityNonHourly']	=	$_POST['is_non_hourly'];
				$slip[0]['Units']								=	$_POST['units'];
				$slip[0]['Rate']								=	$_POST['rate'];
				$slip[0]['Jobs']['JobName']						=	$_POST['job_name'];
				$slip[0]['PayrollCategory']						=	$_POST['payroll_name'];
				$slip[0]['Notes']								=	$_POST['notes'];
				$slip[0]['RecordID']							=	null;
				$slip[0]['SlipDate']							=	null;
				$slip[0]['Activities']['ActivityID']			=	$_POST['activity'];
				$slip[0]['Jobs']['JobNumber']					=	$_POST['job'];
				$slip[0]['Is_non_hourly']						=	$_POST['is_non_hourly'];
				if($edit_flag) { 
					$edit_flag = 1;
					$slip['RecordID']	=	$_POST['slip_id'];
				}
			}
			
			$data_list 	= array('customers'	=> $activity_model->get_customers(),
									'activities'=> $activity_model->get_activities_by_tt(),
									'jobs'		=> $activity_model->get_jobs()
									);
			$data_names = $data_model->auto_list($data_list);
			
			// displaying of activity slip create page
			// payroll_flag default to 1. Changed according to user settings.
			$date_start				 =	date("Y-m-d")." 00:00:00";
			$date_end				 =	date("Y-m-d")." 23:59:59";
			$last_slipdate			 =	$activity_model->get_activity_last_date($date_start, $date_end);
			
			$this->template->content = 	View::factory('activity/create')
										->set( 'payroll_flag',	$dharmausers_m->get_payroll_flag())
										->set( 'data_names', $data_names)
										->set( 'employee_list', $dharmausers_m->get_all_employees())
										->set( 'slip_last_date', $last_slipdate)
										->set( 'linked_job', $dharmausers_m->get_show_jobs())
										->set( 'slip',	isset($slip[0])?$slip[0]:null)
										->set( 'slip_flag',	isset($edit_flag)?$edit_flag:null)
										->set( 'error',	$this->error_message)
										->set( 'slip_number',	empty($edit_flag)?$data_model->get_slip_number($last_auto_id):null);
		} catch(Exception $e){
			die($e->getMessage());
		}		
									
	}
	
	/**
	 * @Method: action_create
	 * @Description: This method saves the activity slips data.
	 */
	public function action_create()
	{
		try {
			$activity_model 	=	new Model_activity;
			$validations		=	new Model_validations;
			$json				=	new Model_json;
			$dropbox			=	new Model_dropbox;
			$activity_data		=	array();
			
		   	if ($_POST['save_create'] == '1' )	 	{	// for save and create
		   		$redirect_url = SITEURL.'/activity/add?';
		   	} else	{									// Direct save.
		   		$redirect_url = SITEURL.'/activitysheet?';
		   	}
		   	
		   	if(isset($_POST['slip_id']) AND $_POST['slip_id']!=''){ 
		   		$slip_id		=	$_POST['slip_id'];
		   		/*$date_str		=	$_POST['created_date'];
		   		$date 			=	explode('-', $date_str);
		   		$date_value		=	$date[2]."-".$date[1]."-".$date[0];*/
		   		$arr_slip_date	=	explode(" ",$_POST['timesheet_date']);
		   		//$date_value	=	$arr_slip_date[2]."-".date('m', strtotime($arr_slip_date[1]."1 2011"))."-".substr($arr_slip_date[0],0,2);	 
		   		$date_value 	=	$arr_slip_date[0];
		   	}else{
		   		$slip_id	=	"";
		   		$arr_slip_date	=	explode(" ",$_POST['timesheet_date']);
		   		//$date_value	=	$arr_slip_date[2]."-".date('m', strtotime($arr_slip_date[1]."1 2011"))."-".substr($arr_slip_date[0],0,2);				
	            $date_value 	=	$arr_slip_date[0];
		   	}
		   	
		   	$customer_name_field	=	$_POST['customer_name'] == "Enter name..."?"":$_POST['customer_name'];
		    $split_name				=	explode(' ',$_POST['emp_name']);
		    
	     	$params = array(
							'customer_id' 		=>	$_POST['customer_name'] == "Enter name..."?"":trim($_POST['customer']),
							'customer_name'		=>	trim(html_entity_decode($customer_name_field)),
							'activity_id' 		=>	trim($_POST['activity']),
							'activity_name'		=>	trim($_POST['activity_name']),
	 						'job_id' 			=>	trim(isset($_POST['job'])?$_POST['job']:''),
							'job_name' 			=>	trim($_POST['job_name']),
							'employee_name'		=>  trim(isset($split_name[0])?$split_name[0]:''),
							'employee_lastname'	=>	trim(isset($split_name[1])?$split_name[1]:''),
							'employee_id'		=>	trim(isset($_POST['emp_rec_id'])?$_POST['emp_rec_id']:''),
							'payroll_id' 		=>	trim(isset($_POST['payroll_id'])?$_POST['payroll_id']:''),
							'payroll_name' 		=>	trim(isset($_POST['payroll_name'])?$_POST['payroll_name']:''),
							'is_non_hourly'		=>	$_POST['is_non_hourly'],
							'units' 			=>	trim($_POST['units']),	
							'rate'				=>	trim($_POST['rate']),
							'total'				=>	trim($_POST['total']),
							'notes'				=>	trim($_POST['notes']),
							'date' 				=> 	$date_value,
							'slip_number'		=>  ($_POST['slip_number'] != '')?trim($_POST['slip_number']):null,
							'RecordID'			=>	$slip_id
							);	
			try			
			{
				$validations->validate_activity($params,$json);  // Validation of fields.
				$error	=	0;
			}
			catch(Exception $e)
			{
			    die($e->getMessage(). " ".$e->getFile()." ".$e->getLine());
				$this->error_message = $e->getMessage();
				$error	=	1;
			}
			if($error)
			{ 
			
				if(isset($_POST['slip_id']) AND $_POST['slip_id']!='')
				{
					$edit_flag		=	"edit";
					$slip_edit_id	=   $slip_id;
				}
				else
				{
					$edit_flag		=	"";
					$slip_edit_id	=   "";
				}
				$this->action_add($edit_flag, $slip_edit_id);
			}
			else
			{ 	
				array_push($activity_data, $params);	// pushing params into one single array.
				/*
				 * Make call to model to insert data.
				 * Save slip event then based on the slip id save the start time and end time. 
				 */
				try
				{
					if (!empty($_POST['slip_id'])) 		// updation of activity slip.
					{	
						$activity_model->update($activity_data);
						Request::instance()->redirect($redirect_url.'statusMessage=2');
					}
					else
					{ 
						if(!$activity_model->redundant_record($params)) {
							$activity_model->insert($activity_data);					// insertion of activity slip.
						}
						Request::instance()->redirect(SITEURL.'/activity/add?m=1');
					}
				} 
				catch(Exception $e)
				{
					$this->error_message = $e->getMessage();
					$this->action_add();   
					return;	
				}
			}
		} catch(Exception $e) {var_dump($e);}
	}
	
	/**
	 * @method : action_sync
	 * @desc   : display synced transaction and unsynced transactions.
	 * @param $view - display method, 0 - hide synced transaction, 1 - view synced, 2 - display all
	 */
	public function action_sync($view, $page_from=0)
	{
		switch($view)
		{
			case 0: $_SESSION['synced_slips_view']	= 0;
					break;
			case 1: $_SESSION['synced_slips_view']	= 1;
					break;
			case 2: $_SESSION['synced_slips_view']	= 2;
					break;
			default:Request::instance()->redirect(SITEURL.'/activitysheet');
		}
		if($page_from == 1) Request::instance()->redirect(SITEURL.'/timesheet');
		else Request::instance()->redirect(SITEURL.'/activitysheet');
	}
	
	/**
	 * @Method: action_synclater
	 * @Description: Click on synclater button in sync alert banner and hide the banner until logout.
	 */
	public function action_synclater()
	{
		$_SESSION['sync_alert_message']	=	0;
		Request::instance()->redirect(SITEURL.'/activitysheet');
	}
	
	/**
	 * @Method			 :	action_importcustomer
 	 * @description	     :	Import new customers from the dropbox. After importing customers from dropbox page will 
 	 * 					    automatically redirect to page from where it clicked. 
	 * @param $page_id	 :	Page identification (1. clicked button from activity add)
	 * @param $edit_slip :	slip number to be edited
	 */
	public function action_importcustomer($page_id, $edit_slip="")
	{
		$obj_user	=	new Model_User;
		$obj_user->create_customer_json_sessions();
		switch($page_id)
		{
			case 1: // clicked from activity add/edit page
					if(empty($edit_slip))
						Request::instance()->redirect(SITEURL.'/activity/add');
					else Request::instance()->redirect(SITEURL.'/activity/add/edit/'.$edit_slip);
					break;
			case 2: // clicked from activitysheet page
					Request::instance()->redirect(SITEURL.'/activitysheet');
					break;
			case 3: // clicked from timesheet page
					Request::instance()->redirect(SITEURL.'/timesheet');
					break;
		    case 4: // clicked from sales/tobesynced page
		            //echo (SITEURL);die;
					Request::instance()->redirect(SITEURL.'/sales/tobesynced');
					break;
		}
	}
	
	/**
	 * @Method: action_delete
	 * @Description: Function to delete slip
	 * @param slip id
	 */
	public function action_delete($slip_id)
	{
		$activity_model 	=	new Model_activity;
		try {
			$status	=	$activity_model->delete_activity($slip_id);
			if($status == 1) {
				Request::instance()->redirect(SITEURL.'/activitysheet?d=1');
			} else {
				throw new Exception("Slip not deleted due to some problem. Please try again later.");
			}
		} catch(Exception $e) {
			Request::instance()->redirect(SITEURL.'/activitysheet');
		}
	}
	
	/**
	 * @Method		:	action_customer_jobs
	 * @Description	:	Function to get customer related job if any, if not related then display all the jobs	
	 * @param		:	int $customer_id
	 */
	public function action_customer_jobs($customer_id) {
		$activity_model =	new Model_activity;
		$data_model 	= 	new Model_Data;
		$json_model		=	new Model_json;
		$dharmausers_m	=	new Model_Dharmausers;
		if(!$dharmausers_m->customer_job_related()) {
			$jobs	= $activity_model->get_jobs();
		} else {
			try {
				$json_model->file_content 	=	$activity_model->get_jobs();
				$jobs_list					=	$json_model->JSON_Query('*', array('LinkedCustomerRecordID' => $customer_id));
			} catch(Exception $e) {
				$jobs	= $activity_model->get_jobs();
			}
			if(empty($jobs_list)) {
				$jobs	= $activity_model->get_jobs();
			} else {
				$jobs					=	array_merge(array(), $jobs_list);
			}
			//$jobs	=	$activity_model->get_customer_related_jobs($customer_id);
		}
		$data_list 	= array('jobs' => $jobs);
		try {
			$data_names = $data_model->customer_job_list($data_list);
		} catch(Exception $e){}
		die(json_encode($data_names));
	}
}

/*
 *  End of Class.
 */